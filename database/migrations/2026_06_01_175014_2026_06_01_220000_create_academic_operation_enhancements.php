<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add columns to schedules table
        Schema::table('schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('schedules', 'academic_year_id')) {
                $table->foreignId('academic_year_id')->nullable()->after('teacher_id')->constrained('academic_years')->nullOnDelete();
            }
            if (! Schema::hasColumn('schedules', 'semester_id')) {
                $table->foreignId('semester_id')->nullable()->after('academic_year_id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('schedules', 'status')) {
                $table->string('status')->default('active')->after('room');
            }
            if (! Schema::hasColumn('schedules', 'deleted_at')) {
                $table->softDeletes();
            }
            // Fix teacher_id to proper FK
            if (! Schema::hasColumn('schedules', 'teacher_id_fk')) {
                // teacher_id already exists as unsignedBigInteger, just add FK
                try {
                    $table->foreign('teacher_id')->references('id')->on('teachers')->nullOnDelete();
                } catch (Exception $e) {
                    // FK might already exist
                }
            }
        });

        // Add columns to attendances table
        Schema::table('attendances', function (Blueprint $table) {
            if (! Schema::hasColumn('attendances', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Add columns to grades table
        Schema::table('grades', function (Blueprint $table) {
            if (! Schema::hasColumn('grades', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->after('subject_id')->constrained('teachers')->nullOnDelete();
            }
            if (! Schema::hasColumn('grades', 'classroom_id')) {
                $table->foreignId('classroom_id')->nullable()->after('teacher_id')->constrained('classrooms')->nullOnDelete();
            }
            if (! Schema::hasColumn('grades', 'academic_year_id')) {
                $table->foreignId('academic_year_id')->nullable()->after('classroom_id')->constrained('academic_years')->nullOnDelete();
            }
            if (! Schema::hasColumn('grades', 'grade_letter')) {
                $table->string('grade_letter')->nullable()->after('final_result');
            }
            if (! Schema::hasColumn('grades', 'predicate')) {
                $table->string('predicate')->nullable()->after('grade_letter');
            }
            if (! Schema::hasColumn('grades', 'note')) {
                $table->text('note')->nullable()->after('predicate');
            }
            // Add unique constraint
            try {
                $table->unique(['student_id', 'subject_id', 'semester_id'], 'grades_unique_student_subject_semester');
            } catch (Exception $e) {
                // Unique might already exist
            }
            if (! Schema::hasColumn('grades', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Create academic_events table
        Schema::create('academic_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('type')->default('event'); // exam, holiday, event, registration, report, other
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_public')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['start_date', 'end_date', 'type'], 'idx_academic_events_date_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_events');

        Schema::table('grades', function (Blueprint $table) {
            $table->dropUnique('grades_unique_student_subject_semester');
            $table->dropColumn(['teacher_id', 'classroom_id', 'academic_year_id', 'grade_letter', 'predicate', 'note', 'deleted_at']);
        });
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['academic_year_id', 'semester_id', 'status', 'deleted_at']);
        });
    }
};
