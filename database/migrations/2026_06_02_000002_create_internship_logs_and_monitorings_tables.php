<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained()->cascadeOnDelete();
            $table->date('activity_date');
            $table->string('activity');
            $table->text('result')->nullable();
            $table->text('obstacle')->nullable();
            $table->string('status')->default('submitted'); // submitted, reviewed, rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('internship_monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained()->cascadeOnDelete();
            $table->date('monitor_date');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note');
            $table->string('follow_up')->nullable();
            $table->string('status')->default('completed'); // completed, visited
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_monitorings');
        Schema::dropIfExists('internship_logs');
    }
};
