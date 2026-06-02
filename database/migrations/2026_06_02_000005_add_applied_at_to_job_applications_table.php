<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('job_applications', 'applied_at')) {
                $table->date('applied_at')->nullable()->after('job_vacancy_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('applied_at');
        });
    }
};
