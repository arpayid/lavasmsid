<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('invoices') && ! Schema::hasIndex('invoices', 'idx_invoices_status')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->index('status', 'idx_invoices_status');
            });
        }

        if (Schema::hasTable('payments') && ! Schema::hasIndex('payments', 'idx_payments_status')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->index('status', 'idx_payments_status');
            });
        }

        if (Schema::hasTable('internships') && ! Schema::hasIndex('internships', 'idx_internships_status')) {
            Schema::table('internships', function (Blueprint $table) {
                $table->index('status', 'idx_internships_status');
            });
        }

        if (Schema::hasTable('alumni') && ! Schema::hasIndex('alumni', 'idx_alumni_status')) {
            Schema::table('alumni', function (Blueprint $table) {
                $table->index('status', 'idx_alumni_status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasIndex('invoices', 'idx_invoices_status')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropIndex('idx_invoices_status');
            });
        }

        if (Schema::hasIndex('payments', 'idx_payments_status')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropIndex('idx_payments_status');
            });
        }

        if (Schema::hasIndex('internships', 'idx_internships_status')) {
            Schema::table('internships', function (Blueprint $table) {
                $table->dropIndex('idx_internships_status');
            });
        }

        if (Schema::hasIndex('alumni', 'idx_alumni_status')) {
            Schema::table('alumni', function (Blueprint $table) {
                $table->dropIndex('idx_alumni_status');
            });
        }
    }
};
