# University Education Management Portal (UEMP) — Backend API

A role-based REST API for managing a university's academic records: faculties, departments, programs,
sessions/semesters, students, lecturers, courses, course registration, result entry, departmental
approval, GPA/CGPA and reports.

**Stack:** Laravel 13 · PHP 8.3 · MySQL · Laravel Sanctum (token auth)

---

## 1. Setup

```bash
composer install
cp .env.example .env          # set DB_* for your MySQL database
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve             # http://localhost:8000
```

### Test credentials (password for every account: `password`)

| Role | Email | Notes |
|---|---|---|
| Super Admin | `superadmin@uemp.test` | University-wide access |
| Department Admin (Computer Science) | `deptadmin.cs@uemp.test` | Has 2 pending CSC 302 results to approve |
| Department Admin (Software Eng.) | `deptadmin.sen@uemp.test` | Used to show department isolation |
| Lecturer | `lecturer1@uemp.test` | CSC 301 (approved), CSC 302 (submitted) |
| Lecturer | `lecturer2@uemp.test` | CSC 201 / CSC 305 (current semester) |
| Student (300L) | `student1@uemp.test` | Has 2 semesters of approved results + CGPA |
| Student (200L) | `student2@uemp.test` | **Not registered yet** — use to demo registration end-to-end |
| Student (SEN 300L) | `student4@uemp.test` | Other department |

### Bruno collection

Import `docs/uemp-api.bruno.json` in Bruno (**Import Collection → Bruno Collection**), select the **Local**
environment, then run a login in **00 Auth**. The token is saved automatically for that role's folder.

Login: `POST /api/auth/login` with `{ "email", "password" }` → returns `token` and `user.role`.
Send `Authorization: Bearer <token>` and `Accept: application/json` on every other request.
The frontend redirects on `user.role` (`super_admin`, `department_admin`, `lecturer`, `student`).

---

## 2. Architecture

```
Route (role middleware) → Controller → FormRequest (validation + ownership) → Action → Model
                                                                       ↘ Service (shared domain logic)
```

| Layer | Location | Responsibility |
|---|---|---|
| Routes | `routes/api.php` | One prefix per role: `super-admin`, `department-admin`, `lecturer`, `student` |
| Middleware | `app/Http/Middleware/EnsureUserHasRole.php` | Rejects wrong role and **deactivated** accounts (even with a valid token) |
| Controllers | `app/Http/Controllers/{Role}` | Thin: resolve the actor, guard ownership, return `{ message, data }` |
| Base controllers | `DepartmentAdminController`, `BaseLecturerController`, `BaseStudentController` | Resolve the actor's department / lecturer / student profile and abort `403` on foreign records |
| Form Requests | `app/Http/Requests/{Role}` | Input validation; scoped `exists` rules (e.g. program must belong to *your* department); `authorize()` ownership checks |
| Actions | `app/Actions/{Role}/{Module}` | One use case per class (`CreateStudentAction`, `SubmitResultsAction`, …) |
| Services | `app/Services` | Logic shared across roles (see below) |

**Services**

| Service | Purpose |
|---|---|
| `Auth/AuthService` | Login, token issue/revoke, role-based profile |
| `Academic/AcademicPeriodService` | Resolves the current session & semester |
| `Result/GradingService` | Total score → grade & grade point from the configurable grading scale |
| `Result/GpaService` | Credit-unit-weighted GPA per semester, CGPA, class of degree |
| `Result/ResultApprovalService` | Approve / reject / reopen state transitions (transactional, audited) |
| `Result/ResultAuditService` | Writes the audit trail (old/new values, actor, comment) |
| `Report/ReportService` | Student, registration and academic-performance reports (department-scoped or university-wide) |

Configuration lives in `config/uemp.php` (CA/exam maximums, unit limits, fallback grading scale).

---

## 3. Database design

```
faculties 1─* departments 1─* programs 1─* students *─1 users
                         1─* lecturers *─1 users
                         1─* courses
                         1─* department_admins *─1 users
academic_sessions 1─* semesters
courses ─ course_allocations (course + lecturer + session + semester, UNIQUE course/session/semester)
students ─ course_registrations (UNIQUE student/course/session/semester)
course_registrations 1─1 results (ca, exam, total, grade, grade_point, status, lecturer, approver)
results 1─* result_audits (action, performed_by, old_values, new_values, comment)
grading_scales (min_score, max_score, grade, grade_point)
```

- `users.role` ∈ `student | lecturer | department_admin | super_admin`; role profiles are 1-to-1 tables.
- `semesters.number` (1/2) links a semester to `courses.semester`, so the system knows what is offered.
- Unique indexes enforce no duplicate registration and one lecturer allocation per course per semester.
- Soft deletes on academic records; deletes are refused when dependent records exist.

---

## 4. Role-based access control

| Capability | Student | Lecturer | Dept Admin | Super Admin |
|---|:-:|:-:|:-:|:-:|
| Own dashboard & profile | ✔ | ✔ | ✔ | ✔ |
| Register / drop own courses | ✔ | | | |
| View own **approved** results, GPA, CGPA, statement | ✔ | | | |
| View class lists of **assigned** courses | | ✔ | ✔ (dept) | ✔ |
| Enter CA/exam, save draft, submit | | ✔ (assigned only) | | |
| Approve / reject / reopen results | | | ✔ (own dept) | view |
| Manage students, lecturers, programs, courses, allocations | | | ✔ (own dept) | ✔ |
| Faculties, departments, sessions, semesters, users, roles, grading scale | | | | ✔ |
| Audit logs & university-wide reports | | | dept reports | ✔ |

How restrictions are enforced (server side, never trusting the client):

- **Role**: `role:<name>` middleware on each route group → `403`.
- **Department scope**: admin's department comes from `department_admins`, never from the request;
  `department_id` in payloads is ignored and list queries are always filtered.
- **Lecturer ownership**: `course_allocations.lecturer_id` must equal the caller's lecturer id.
- **Student ownership**: the student is always resolved from the token; `student_id` is never accepted.
- **Separation of duties**: reviewers can never approve results they entered.
- Deactivating a user revokes all their tokens; login is throttled (10/min).

---

## 5. Business workflows

**Course registration** (`/api/student`)
1. `GET courses/available` — department + level + current semester + active courses only.
2. `POST course-registrations { course_ids: [] }` — validated for eligibility, duplicates and the
   per-semester unit limit (`UEMP_MAX_REGISTRATION_UNITS`, default 24). Only `active` students.
3. `DELETE course-registrations/{id}` — drop (current semester, no result yet). Re-registering restores it.
4. `GET course-registrations/slip` — printable registration summary.

**Result lifecycle**

```
 draft ──submit──▶ submitted ──approve──▶ approved ──(visible to student)
   ▲                  │                      │
   └──edit── rejected ◀┘ reject (feedback)   │ reopen (dept admin, with reason)
   └─────────────────────────────────────────┘
```

1. Lecturer `POST lecturer/courses/{allocation}/results` — CA (≤30) and exam (≤70); total, grade and
   grade point are computed by the server. Only `draft`/`rejected` rows are editable.
2. Lecturer `POST .../results/submit` — whole course requires every registered student to have scores.
3. Dept admin `GET department-admin/results/submissions` → `GET results/courses/{allocation}` →
   `POST .../approve` or `POST .../reject { reason }` (optionally `result_ids` for partial action).
4. Submitted/approved results can only change after `POST .../reopen { reason }`.
5. Every create/update/submit/approve/reject/reopen writes a `result_audits` row.

**GPA** = Σ(units × grade point) / Σ(units), approved results only. Default 5-point scale:

| Score | 70–100 | 60–69 | 50–59 | 45–49 | 40–44 | 0–39 |
|---|---|---|---|---|---|---|
| Grade / Point | A / 5 | B / 4 | C / 3 | D / 2 | E / 1 | F / 0 |

The Super Admin can change it via `/api/super-admin/grading-scales` (overlapping bands are rejected).
Class of degree: ≥4.50 First, ≥3.50 2:1, ≥2.40 2:2, ≥1.50 Third, ≥1.00 Pass.

---

## 6. API reference

All responses: `{ "message": string, "data": ... }`. Validation errors: `422 { message, errors }`.
List endpoints are paginated (`?per_page=`) and accept filters such as `search`, `academic_session_id`,
`semester_id`, `department_id`, `status`, `academic_level`.

**Auth** — `POST auth/login`, `GET auth/me`, `POST auth/logout`, `POST auth/logout-all`

**Super Admin** — prefix `/api/super-admin`
- `GET dashboard`
- CRUD: `faculties`, `departments`, `department-admins`, `programs`, `academic-sessions`, `semesters`,
  `students`, `lecturers`, `courses`, `course-allocations`, `grading-scales`, `users`
- `POST academic-sessions/{id}/set-current`, `POST semesters/{id}/set-current`
- `PATCH users/{id}/status`, `PATCH users/{id}/role`
- `GET course-registrations`, `GET results`, `GET results/{id}`, `GET audit-logs`
- `GET reports/students`, `GET reports/course-registrations`, `GET reports/academic-performance`

**Department Admin** — prefix `/api/department-admin`
- `GET dashboard`
- CRUD: `students`, `lecturers`, `programs`, `courses`, `course-allocations`
- `GET course-registrations`
- `GET results/submissions`, `GET results/courses/{allocation}`, `GET results/{id}`
- `POST results/courses/{allocation}/approve|reject|reopen`
- `GET reports/students`, `GET reports/course-registrations`, `GET reports/academic-performance`

**Lecturer** — prefix `/api/lecturer`
- `GET dashboard`, `GET courses` (`?current=1`), `GET courses/{allocation}` (class list),
  `GET courses/{allocation}/summary`
- `GET|POST courses/{allocation}/results`, `POST courses/{allocation}/results/submit`, `GET results/{id}`

**Student** — prefix `/api/student`
- `GET dashboard`, `GET|PATCH profile` (name, password with `current_password`)
- `GET courses/available`, `GET|POST course-registrations`, `DELETE course-registrations/{id}`,
  `GET course-registrations/slip`
- `GET results`, `GET results/statement`

