<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'user.view', 'user.create', 'user.update', 'user.delete',
            'role.view', 'role.create', 'role.update', 'role.delete',
            'academic.view', 'academic.create', 'academic.update', 'academic.delete',
            'schedule.view', 'schedule.create', 'schedule.update', 'schedule.delete',
            'attendance.view', 'attendance.create', 'attendance.update', 'attendance.delete', 'attendance.export',
            'grade.view', 'grade.create', 'grade.update', 'grade.delete', 'grade.export',
            'report.view', 'report.export',
            'student.view', 'student.create', 'student.update', 'student.delete', 'student.import', 'student.export',
            'teacher.view', 'teacher.create', 'teacher.update', 'teacher.delete', 'teacher.import', 'teacher.export',
            'staff.view', 'staff.create', 'staff.update', 'staff.delete', 'staff.import', 'staff.export',
            'guardian.view', 'guardian.create', 'guardian.update', 'guardian.delete',
            'finance.view', 'finance.create', 'finance.update', 'finance.verify', 'finance.export', 'finance.print',
            'ppdb.view', 'ppdb.verify', 'ppdb.approve', 'ppdb.convert', 'ppdb.export', 'ppdb.print',
            'industry.view', 'industry.create', 'industry.update', 'industry.delete',
            'internship.view', 'internship.create', 'internship.update', 'internship.delete',
            'alumni.view', 'alumni.create', 'alumni.update', 'alumni.delete', 'alumni.export',
            'bkk.view', 'bkk.create', 'bkk.update', 'bkk.export',
            'website.view', 'website.create', 'website.update', 'website.delete',
            'communication.view', 'communication.create', 'communication.update', 'communication.delete',
            'settings.view', 'settings.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $rolePermissions = [
            'Super Admin' => $permissions,
            'Admin Sekolah' => ['student.view', 'student.create', 'student.update', 'student.delete', 'teacher.view', 'teacher.create', 'teacher.update', 'teacher.delete', 'staff.view', 'staff.create', 'staff.update', 'staff.delete', 'guardian.view', 'guardian.create', 'guardian.update', 'guardian.delete', 'academic.view', 'schedule.view', 'attendance.view', 'attendance.create', 'grade.view', 'grade.create', 'grade.update', 'report.view', 'report.export', 'website.view'],
            'Kepala Sekolah' => ['report.view', 'report.export', 'academic.view', 'schedule.view', 'attendance.view', 'grade.view', 'student.view', 'teacher.view'],
            'Waka Kurikulum' => ['academic.view', 'academic.create', 'academic.update', 'schedule.view', 'schedule.create', 'schedule.update', 'schedule.delete', 'grade.view', 'grade.create', 'grade.update', 'grade.export', 'report.view', 'report.export'],
            'Waka Kesiswaan' => ['student.view', 'student.create', 'attendance.view', 'attendance.create'],
            'Guru' => ['schedule.view', 'attendance.view', 'attendance.create', 'grade.view', 'grade.create', 'grade.update', 'student.view', 'guardian.view'],
            'Wali Kelas' => ['attendance.view', 'attendance.create', 'grade.view', 'grade.create', 'grade.update', 'student.view', 'guardian.view', 'report.view'],
            'Siswa' => ['attendance.view', 'grade.view'],
            'Orang Tua / Wali' => ['attendance.view', 'grade.view'],
            'Bendahara' => ['finance.view', 'finance.create', 'finance.update', 'finance.verify', 'finance.export', 'finance.print'],
            'Staff TU' => ['student.view', 'student.create', 'student.update', 'teacher.view', 'staff.view', 'guardian.view', 'academic.view', 'schedule.view', 'attendance.view', 'grade.view', 'report.view'],
            'Panitia PPDB' => ['ppdb.view', 'ppdb.verify', 'ppdb.approve', 'ppdb.convert', 'ppdb.export', 'ppdb.print'],
            'Pembimbing PKL' => ['internship.view', 'internship.create', 'internship.update'],
            'Admin BKK' => ['alumni.view', 'alumni.create', 'alumni.update', 'alumni.export', 'bkk.view', 'bkk.create', 'bkk.update', 'bkk.export'],
        ];

        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }

        $this->createSuperAdminUser();
    }

    protected function createSuperAdminUser(): void
    {
        if (App::environment('testing')) {
            return;
        }

        $user = User::firstOrCreate(
            ['email' => config('app.super_admin_email', 'admin@lavasmsid.test')],
            [
                'name' => 'Super Admin',
                'password' => bcrypt(config('app.super_admin_password', 'password')),
                'is_active' => true,
            ]
        );

        $user->assignRole('Super Admin');
    }
}
