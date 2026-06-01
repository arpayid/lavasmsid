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
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            // User Management
            'user.view', 'user.create', 'user.update', 'user.delete',
            'role.view', 'role.create', 'role.update', 'role.delete',
            // Academic
            'academic.view', 'academic.create', 'academic.update', 'academic.delete',
            // Student
            'student.view', 'student.create', 'student.update', 'student.delete', 'student.import', 'student.export',
            // Teacher
            'teacher.view', 'teacher.create', 'teacher.update', 'teacher.delete', 'teacher.import', 'teacher.export',
            // Staff
            'staff.view', 'staff.create', 'staff.update', 'staff.delete', 'staff.import', 'staff.export',
            // Guardian
            'guardian.view', 'guardian.create', 'guardian.update', 'guardian.delete',
            // Attendance
            'attendance.view', 'attendance.create', 'attendance.update', 'attendance.delete',
            // Grade
            'grade.view', 'grade.create', 'grade.update', 'grade.delete',
            // Finance
            'finance.view', 'finance.create', 'finance.update', 'finance.verify', 'finance.export', 'finance.print',
            // PPDB
            'ppdb.view', 'ppdb.verify', 'ppdb.approve', 'ppdb.convert', 'ppdb.export', 'ppdb.print',
            // Industry / Internship
            'industry.view', 'industry.create', 'industry.update', 'industry.delete',
            'internship.view', 'internship.create', 'internship.update', 'internship.delete',
            // BKK / Alumni
            'alumni.view', 'alumni.create', 'alumni.update', 'alumni.delete', 'alumni.export',
            'bkk.view', 'bkk.create', 'bkk.update', 'bkk.export',
            // Report
            'report.view', 'report.export',
            // Website CMS
            'website.view', 'website.create', 'website.update', 'website.delete',
            // Communication
            'communication.view', 'communication.create', 'communication.update', 'communication.delete',
            // Schedule
            'schedule.view', 'schedule.create', 'schedule.update', 'schedule.delete',
            // Import
            'student.import', 'teacher.import',
            // Settings
            'settings.view', 'settings.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define roles and sync permissions
        $rolePermissions = [
            'Super Admin' => $permissions,
            'Admin Sekolah' => ['student.view', 'student.create', 'student.update', 'student.delete', 'teacher.view', 'teacher.create', 'teacher.update', 'teacher.delete', 'staff.view', 'staff.create', 'staff.update', 'staff.delete', 'guardian.view', 'guardian.create', 'guardian.update', 'guardian.delete', 'academic.view', 'attendance.view', 'grade.view', 'report.view', 'website.view'],
            'Kepala Sekolah' => ['report.view', 'report.export', 'academic.view', 'attendance.view', 'grade.view', 'student.view', 'teacher.view'],
            'Waka Kurikulum' => ['academic.view', 'schedule.view', 'grade.view', 'grade.create', 'grade.update'],
            'Waka Kesiswaan' => ['student.view', 'attendance.view'],
            'Guru' => ['attendance.view', 'attendance.create', 'grade.view', 'grade.create', 'grade.update', 'student.view', 'guardian.view'],
            'Wali Kelas' => ['attendance.view', 'grade.view', 'student.view', 'guardian.view', 'report.view'],
            'Siswa' => ['grade.view', 'attendance.view'],
            'Orang Tua / Wali' => ['attendance.view', 'grade.view'],
            'Bendahara' => ['finance.view', 'finance.create', 'finance.update', 'finance.verify', 'finance.export', 'finance.print'],
            'Staff TU' => ['student.view', 'student.create', 'student.update', 'teacher.view', 'staff.view', 'guardian.view', 'academic.view', 'report.view'],
            'Panitia PPDB' => ['ppdb.view', 'ppdb.verify', 'ppdb.approve', 'ppdb.export', 'ppdb.print'],
            'Pembimbing PKL' => ['internship.view', 'internship.create', 'internship.update'],
            'Admin BKK' => ['alumni.view', 'alumni.create', 'alumni.update', 'alumni.export', 'bkk.view', 'bkk.create', 'bkk.update', 'bkk.export'],
        ];

        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }

        // Create Super Admin user
        $this->createSuperAdminUser();
    }

    protected function createSuperAdminUser(): void
    {
        $password = env('DEFAULT_ADMIN_PASSWORD');

        if (App::environment('production') && empty($password)) {
            throw new \RuntimeException(
                'DEFAULT_ADMIN_PASSWORD environment variable is required in production. '.
                'Set it in your .env file before running migrations.'
            );
        }

        if (empty($password)) {
            $password = 'password';
        }

        /** @var User $user */
        $user = User::updateOrCreate(
            ['email' => 'admin@lavasmsid.local'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt($password),
                'is_active' => true,
            ]
        );

        $user->syncRoles(['Super Admin']);
    }
}
