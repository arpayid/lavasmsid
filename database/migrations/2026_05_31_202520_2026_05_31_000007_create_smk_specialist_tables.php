<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_factory_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->default('product');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('teaching_factory_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('teaching_factory_products')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('planned');
            $table->timestamps();
        });

        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('level')->nullable();
            $table->string('organizer')->nullable();
            $table->timestamps();
        });

        Schema::create('certification_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete();
            $table->string('result')->default('pending');
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['certification_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certification_results');
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('teaching_factory_projects');
        Schema::dropIfExists('teaching_factory_products');
    }
};
