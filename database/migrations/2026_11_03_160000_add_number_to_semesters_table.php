<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            // 1 = First, 2 = Second; matches courses.semester
            $table->unsignedTinyInteger('number')->default(1)->after('name');
        });

        // Back-fill existing rows before enforcing uniqueness
        DB::table('semesters')->where('name', 'like', '%second%')->update(['number' => 2]);

        Schema::table('semesters', function (Blueprint $table) {
            $table->unique(['academic_session_id', 'number'], 'session_semester_number_unique');
        });
    }

    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropUnique('session_semester_number_unique');
            $table->dropColumn('number');
        });
    }
};
