<?php

use App\Models\User;
use App\Modules\Student\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Permission::firstOrCreate(['name' => 'student.view', 'guard_name' => 'web']);
    $this->superAdmin = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());
    $this->superAdmin->assignRole($role);
});

test('super admin can view student list', function () {
    $this->actingAs($this->superAdmin)->get(route('admin.students.index'))->assertOk();
});

test('guest cannot access student list', function () {
    $this->get(route('admin.students.index'))->assertRedirect(route('login'));
});

test('student model can be created with valid data', function () {
    $student = Student::create([
        'nis' => 'NIS' . time(),
        'name' => 'Budi Santoso',
        'gender' => 'L',
        'status' => 'active',
    ]);
    expect($student)->toBeInstanceOf(Student::class);
    expect($student->name)->toBe('Budi Santoso');
});
