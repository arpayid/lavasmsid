<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('school_settings', 'tagline')) {
                $table->string('tagline')->nullable()->after('school_name');
            }
            if (! Schema::hasColumn('school_settings', 'principal_name')) {
                $table->string('principal_name')->nullable()->after('school_address');
            }
            if (! Schema::hasColumn('school_settings', 'principal_message')) {
                $table->text('principal_message')->nullable()->after('principal_name');
            }
            if (! Schema::hasColumn('school_settings', 'history')) {
                $table->text('history')->nullable()->after('principal_message');
            }
            if (! Schema::hasColumn('school_settings', 'vision')) {
                $table->text('vision')->nullable()->after('history');
            }
            if (! Schema::hasColumn('school_settings', 'mission')) {
                $table->text('mission')->nullable()->after('vision');
            }
            if (! Schema::hasColumn('school_settings', 'website_url')) {
                $table->string('website_url')->nullable()->after('mission');
            }
            if (! Schema::hasColumn('school_settings', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('website_url');
            }
            if (! Schema::hasColumn('school_settings', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('facebook_url');
            }
            if (! Schema::hasColumn('school_settings', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('instagram_url');
            }
            if (! Schema::hasColumn('school_settings', 'favicon_path')) {
                $table->string('favicon_path')->nullable()->after('logo_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            $table->dropColumn([
                'tagline', 'principal_name', 'principal_message', 'history',
                'vision', 'mission', 'website_url', 'facebook_url',
                'instagram_url', 'youtube_url', 'favicon_path',
            ]);
        });
    }
};
