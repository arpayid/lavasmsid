<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        SchoolSetting::updateOrCreate(
            ['id' => 1],
            [
                'school_name' => config('app.name', 'LavaSMSID'),
                'school_email' => 'info@lavasmsid.local',
                'school_phone' => null,
                'school_address' => null,
                'logo_path' => null,
            ]
        );
    }
}
