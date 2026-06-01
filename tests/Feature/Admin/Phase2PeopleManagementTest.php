<?php

use App\Models\User;
use App\Modules\Academic\Models\Subject;
use App\Modules\Student\Models\Student;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $perms = ['student.view', 'student.create', 'student.update', 'student.delete',
        'teacher.view', 'teacher.create', 'teacher.update', 'teacher.delete',
        'staff.view', 'staff.create', 'staff.update', 'staff.delete',
        'guardian.view', 'guardian.create', 'guardian.update', 'guardian.delete'];
    foreach ($perms as $p) {
        Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }

    $this->admin = User::factory()->create();
    $adminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $adminRole->givePermissionTo(Permission::all());
    $this->admin->assignRole($adminRole);

    $this->user = User::factory()->create(['email' => 'test@test.com']);
});

test('guest cannot access students index', function () {
    $this->get(route('admin.students.index'))->assertRedirect(route('login'));
});

test('user without student.view cannot access students index', function () {
    $this->actingAs($this->user)->get(route('admin.students.index'))->assertForbidden();
});

test('user with student.view can access students index', function () {
    $this->actingAs($this->admin)->get(route('admin.students.index'))->assertOk();
});

test('user with student.create can create student', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.students.store'), ['nis' => 'TEST001', 'name' => 'Test Student', 'gender' => 'L', 'status' => 'active']);
    $response->assertRedirect();
    $this->assertDatabaseHas('students', ['nis' => 'TEST001']);
});

test('validation fails when nis or name is empty', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.students.store'), ['nis' => '', 'name' => '']);
    $response->assertSessionHasErrors(['nis', 'name']);
});

test('user with teacher.view can access teachers index', function () {
    $this->actingAs($this->admin)->get(route('admin.teachers.index'))->assertOk();
});

test('user with teacher.create can create teacher', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.teachers.store'), ['name' => 'Test Teacher', 'status' => 'active']);
    $response->assertRedirect();
    $this->assertDatabaseHas('teachers', ['name' => 'Test Teacher']);
});

test('teacher validation fails when name missing', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.teachers.store'), ['name' => '']);
    $response->assertSessionHasErrors(['name']);
});

test('user with staff.view can access staff index', function () {
    $this->actingAs($this->admin)->get(route('admin.staff.index'))->assertOk();
});

test('user with staff.create can create staff', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.staff.store'), ['name' => 'Test Staff', 'status' => 'active']);
    $response->assertRedirect();
    $this->assertDatabaseHas('staff', ['name' => 'Test Staff']);
});

test('user with guardian.view can access guardians index', function () {
    $this->actingAs($this->admin)->get(route('admin.guardians.index'))->assertOk();
});

test('user with guardian.create can create guardian', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.guardians.store'), ['name' => 'Test Guardian', 'relation' => 'father']);
    $response->assertRedirect();
    $this->assertDatabaseHas('guardians', ['name' => 'Test Guardian']);
});

test('Super Admin can access all Phase 2 indexes', function () {
    foreach (['admin.students.index', 'admin.teachers.index', 'admin.staff.index', 'admin.guardians.index'] as $route) {
        $this->actingAs($this->admin)->get(route($route))->assertOk();
    }
});

test('soft delete student works', function () {
    $student = Student::create(['nis' => 'SDEL', 'name' => 'Soft Delete', 'gender' => 'L', 'status' => 'active']);
    $student->delete();
    $this->assertSoftDeleted('students', ['id' => $student->id]);
});

test('teacher_subject assignment works when subject exists', function () {
    $teacher = Teacher::create(['name' => 'Subject Teacher', 'status' => 'active']);
    $subject = Subject::create(['code' => 'MATH', 'name' => 'Matematika']);
    $teacher->subjects()->attach($subject->id, ['classroom_id' => null, 'academic_year_id' => null, 'semester_id' => null]);
    $this->assertDatabaseHas('teacher_subjects', ['teacher_id' => $teacher->id, 'subject_id' => $subject->id]);
});
