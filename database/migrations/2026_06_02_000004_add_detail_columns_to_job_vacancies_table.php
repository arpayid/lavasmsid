<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            if (! Schema::hasColumn('job_vacancies', 'type')) {
                $table->string('type', 100)->nullable()->after('location');
            }
            if (! Schema::hasColumn('job_vacancies', 'requirements')) {
                $table->text('requirements')->nullable()->after('description');
            }
            if (! Schema::hasColumn('job_vacancies', 'salary_min')) {
                $table->decimal('salary_min', 14, 2)->nullable()->after('salary_range');
            }
            if (! Schema::hasColumn('job_vacancies', 'salary_max')) {
                $table->decimal('salary_max', 14, 2)->nullable()->after('salary_min');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropColumn(['type', 'requirements', 'salary_min', 'salary_max']);
        });
    }
};
