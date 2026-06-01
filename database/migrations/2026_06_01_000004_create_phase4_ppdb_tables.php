<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ppdb_periods')) {
            Schema::create('ppdb_periods', function (Blueprint $table) {
                $table->id();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->string('name');
                $table->date('start_date');
                $table->date('end_date');
                $table->unsignedInteger('quota')->nullable();
                $table->string('status')->default('draft')->index();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(false)->index();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('ppdb_registrations')) {
            Schema::create('ppdb_registrations', function (Blueprint $table) {
                $table->id();
                $table->string('registration_number')->unique();
                $table->foreignId('ppdb_period_id')->nullable()->constrained('ppdb_periods')->nullOnDelete();
                $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
                $table->foreignId('chosen_department_id')->nullable()->constrained('departments')->nullOnDelete();
                $table->foreignId('chosen_classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
                $table->string('nisn')->nullable();
                $table->string('name');
                $table->string('candidate_name')->nullable();
                $table->string('gender')->nullable();
                $table->string('birth_place')->nullable();
                $table->date('birth_date')->nullable();
                $table->string('religion')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->string('previous_school')->nullable();
                $table->string('parent_name')->nullable();
                $table->string('parent_phone')->nullable();
                $table->string('document_path')->nullable();
                $table->text('notes')->nullable();
                $table->text('verification_note')->nullable();
                $table->string('status')->default('submitted')->index();
                $table->timestamp('accepted_at')->nullable();
                $table->timestamp('converted_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('ppdb_registrations', function (Blueprint $table) {
                if (! Schema::hasColumn('ppdb_registrations', 'ppdb_period_id')) {
                    $table->foreignId('ppdb_period_id')->nullable()->after('registration_number')->constrained('ppdb_periods')->nullOnDelete();
                }
                if (! Schema::hasColumn('ppdb_registrations', 'chosen_department_id')) {
                    $table->foreignId('chosen_department_id')->nullable()->after('department_id')->constrained('departments')->nullOnDelete();
                }
                if (! Schema::hasColumn('ppdb_registrations', 'chosen_classroom_id')) {
                    $table->foreignId('chosen_classroom_id')->nullable()->after('chosen_department_id')->constrained('classrooms')->nullOnDelete();
                }
                if (! Schema::hasColumn('ppdb_registrations', 'nisn')) {
                    $table->string('nisn')->nullable()->after('chosen_classroom_id');
                }
                if (! Schema::hasColumn('ppdb_registrations', 'name')) {
                    $table->string('name')->nullable()->after('nisn');
                }
                if (! Schema::hasColumn('ppdb_registrations', 'religion')) {
                    $table->string('religion')->nullable()->after('birth_date');
                }
                if (! Schema::hasColumn('ppdb_registrations', 'verification_note')) {
                    $table->text('verification_note')->nullable()->after('notes');
                }
                if (! Schema::hasColumn('ppdb_registrations', 'accepted_at')) {
                    $table->timestamp('accepted_at')->nullable()->after('status');
                }
                if (! Schema::hasColumn('ppdb_registrations', 'converted_at')) {
                    $table->timestamp('converted_at')->nullable()->after('accepted_at');
                }
                if (! Schema::hasColumn('ppdb_registrations', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        if (! Schema::hasTable('ppdb_registration_documents')) {
            Schema::create('ppdb_registration_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ppdb_registration_id')->constrained('ppdb_registrations')->cascadeOnDelete();
                $table->string('type');
                $table->string('file_path');
                $table->string('original_name');
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('size')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_registration_documents');
        Schema::dropIfExists('ppdb_periods');
    }
};
