<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('nis')->nullable();
            $table->smallInteger('graduation_year');
            $table->string('status')->default('unemployed');
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('salary_range')->nullable();
            $table->string('institution_name')->nullable();
            $table->string('study_program')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('industry_partner_id')->nullable()->constrained()->nullOnDelete();
            $table->string('company_name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('salary_range')->nullable();
            $table->string('status')->default('active');
            $table->date('deadline')->nullable();
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->cascadeOnDelete();
            $table->foreignId('job_vacancy_id')->constrained('job_vacancies')->cascadeOnDelete();
            $table->string('status')->default('applied');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_vacancies');
        Schema::dropIfExists('alumni');
    }
};
