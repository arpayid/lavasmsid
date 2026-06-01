<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin is created by RolePermissionSeeder only
        $this->call(RolePermissionSeeder::class);

        Schema::disableForeignKeyConstraints();

        DB::table('academic_years')->insertOrIgnore([
            'id' => 1, 'name' => '2026/2027', 'start_date' => '2026-07-01',
            'end_date' => '2027-06-30', 'is_active' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('semesters')->insertOrIgnore([
            'id' => 1, 'academic_year_id' => 1, 'name' => 'Ganjil',
            'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $departments = [
            ['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak'],
            ['code' => 'TKJ', 'name' => 'Teknik Komputer dan Jaringan'],
            ['code' => 'DKV', 'name' => 'Desain Komunikasi Visual'],
            ['code' => 'AKL', 'name' => 'Akuntansi'],
            ['code' => 'TKR', 'name' => 'Teknik Kendaraan Ringan'],
        ];
        foreach ($departments as $department) {
            DB::table('departments')->insertOrIgnore(
                $department + ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        DB::table('payment_categories')->insertOrIgnore([
            'id' => 1, 'name' => 'SPP Bulanan',
            'description' => 'Tagihan SPP reguler siswa',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
