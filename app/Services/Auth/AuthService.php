<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Login user and return token + user data
     */
    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been deactivated. Please contact the administrator.'],
            ]);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'token' => $token,
            'user'  => $this->formatUser($user),
        ];
    }

    /**
     * Get authenticated user with profile
     */
    public function me(User $user): array
    {
        return $this->formatUser($user);
    }

    /**
     * Logout (revoke current token)
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Logout from all devices
     */
    public function logoutAll(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Format user response with role-based profile
     */
    private function formatUser(User $user): array
    {
        $data = [
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'role'      => $user->role,
            'is_active' => $user->is_active,
        ];

        // Attach profile based on role
        if ($user->isStudent() && $user->student) {
            $student = $user->student()->with(['department', 'program'])->first();

            $data['profile'] = [
                'matric_number'   => $student->matric_number,
                'academic_level'  => $student->academic_level,
                'department'      => $student->department?->name,
                'department_id'   => $student->department_id,
                'program'         => $student->program?->name,
                'program_id'      => $student->program_id,
                'status'          => $student->status,
            ];
        }

        if ($user->isLecturer() && $user->lecturer) {
            $lecturer = $user->lecturer()->with('department')->first();

            $data['profile'] = [
                'staff_id'      => $lecturer->staff_id,
                'title'         => $lecturer->title,
                'rank'          => $lecturer->rank,
                'department'    => $lecturer->department?->name,
                'department_id' => $lecturer->department_id,
            ];
        }

        if ($user->isDepartmentAdmin()) {
            // You can later attach department_id if you store it
            $data['profile'] = [
                'role_label' => 'Department Administrator',
            ];
        }

        if ($user->isSuperAdmin()) {
            $data['profile'] = [
                'role_label' => 'Super Administrator',
            ];
        }

        return $data;
    }
}