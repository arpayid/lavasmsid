<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ppdb_registrations') && ! Schema::hasColumn('ppdb_registrations', 'notes')) {
            Schema::table('ppdb_registrations', function (Blueprint $table) {
                $table->text('notes')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ppdb_registrations') && Schema::hasColumn('ppdb_registrations', 'notes')) {
            Schema::table('ppdb_registrations', function (Blueprint $table) {
                $table->dropColumn('notes');
            });
        }
    }
};
