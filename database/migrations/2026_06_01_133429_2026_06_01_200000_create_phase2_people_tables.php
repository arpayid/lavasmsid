<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === Create teachers table (was missing) ===
        if (! Schema::hasTable('teachers')) {
            Schema::create('teachers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('nip', 50)->nullable()->unique();
                $table->string('nuptk', 30)->nullable()->unique();
                $table->string('name');
                $table->string('gender', 1)->nullable();
                $table->string('birth_place')->nullable();
                $table->date('birth_date')->nullable();
                $table->string('email')->nullable()->unique();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('qualification', 100)->nullable();
                $table->string('certification_number', 50)->nullable();
                $table->string('status')->default('active');
                $table->string('photo_path')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index(['name', 'status'], 'idx_teachers_search');
            });
        }

        // Add columns to existing teachers table if it already existed
        Schema::table('teachers', function (Blueprint $table) {
            if (! Schema::hasColumn('teachers', 'nuptk')) {
                $table->string('nuptk', 30)->nullable()->unique()->after('nip');
            }
            if (! Schema::hasColumn('teachers', 'birth_place')) {
                $table->string('birth_place')->nullable()->after('gender');
            }
            if (! Schema::hasColumn('teachers', 'email')) {
                $table->string('email')->nullable()->unique()->after('phone');
            }
            if (! Schema::hasColumn('teachers', 'qualification')) {
                $table->string('qualification', 100)->nullable()->after('email');
            }
        });

        // === Guardians ===
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('relation', 30)->default('guardian');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('occupation')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['name', 'phone', 'email'], 'idx_guardians_search');
        });

        // === Staff ===
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number', 50)->nullable()->unique();
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('gender', 1)->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('photo_path')->nullable();
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status'], 'idx_staff_search');
        });

        // === Add columns to students (guardians FK now valid) ===
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'birth_place')) {
                $table->string('birth_place')->nullable()->after('gender');
            }
            if (! Schema::hasColumn('students', 'religion')) {
                $table->string('religion', 50)->nullable()->after('birth_date');
            }
            if (! Schema::hasColumn('students', 'guardian_id')) {
                $table->foreignId('guardian_id')->nullable()->constrained('guardians')->nullOnDelete()->after('classroom_id');
            }
            $table->index('nis', 'idx_students_nis');
            $table->index('nisn', 'idx_students_nisn');
            $table->index('status', 'idx_students_status');
            $table->index(['classroom_id', 'department_id'], 'idx_students_class_dept');
        });

        // === Teacher-Subject pivot ===
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete();
            $table->timestamps();
            $table->unique(['teacher_id', 'subject_id', 'classroom_id', 'academic_year_id', 'semester_id'], 'idx_ts_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_subjects');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('guardians');
        Schema::dropIfExists('teachers');

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('idx_students_search');
            $table->dropForeign(['guardian_id']);
            $table->dropColumn(['birth_place', 'religion', 'guardian_id']);
        });
    }
};
