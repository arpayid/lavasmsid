<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = ['student', 'teacher', 'staff', 'academic', 'attendance', 'grade', 'finance', 'ppdb', 'schedule', 'internship', 'alumni', 'bkk', 'website', 'communication', 'report', 'user'];
        $actions = ['view', 'create', 'update', 'delete', 'export', 'import', 'approve', 'verify', 'print'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$module.$action"]);
            }
        }

        $roles = [
            'Super Admin', 'Admin Sekolah', 'Kepala Sekolah', 'Waka Kurikulum', 'Waka Kesiswaan', 'Guru', 'Wali Kelas', 'Siswa', 'Orang Tua / Wali', 'Bendahara', 'Staff TU', 'Panitia PPDB', 'Pembimbing PKL', 'Admin BKK',
        ];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            if ($roleName === 'Super Admin') {
                $role->syncPermissions(Permission::all());
            }
        }

        Role::findByName('Admin Sekolah')->givePermissionTo(Permission::where('name', 'not like', 'finance.%')->get());
        Role::findByName('Bendahara')->givePermissionTo(Permission::where('name', 'like', 'finance.%')->get());
        Role::findByName('Panitia PPDB')->givePermissionTo(Permission::where('name', 'like', 'ppdb.%')->get());
        Role::findByName('Guru')->givePermissionTo(['attendance.view', 'attendance.create', 'grade.view', 'grade.create', 'schedule.view', 'student.view']);
        Role::findByName('Siswa')->givePermissionTo(['grade.view', 'attendance.view', 'schedule.view']);
    }
}
