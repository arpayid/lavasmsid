<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            if (! Schema::hasColumn('internships', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->after('industry_partner_id')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('internships', 'grade')) {
                $table->decimal('grade', 5, 2)->nullable()->after('status');
            }
            if (! Schema::hasColumn('internships', 'notes')) {
                $table->text('notes')->nullable()->after('grade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['teacher_id', 'grade', 'notes']);
        });
    }
};
