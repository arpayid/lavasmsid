<?php

use App\Models\User;
use App\Modules\Academic\Models\Subject;
use App\Modules\Student\Models\Student;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Teacher\Services\TeacherService;
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

// === Teacher Subject Assignment Tests ===

test('creating teacher without subjects must pass', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.teachers.store'), [
        'name' => 'No Subject Teacher',
        'status' => 'active',
    ]);
    $response->assertRedirect();
    $this->assertDatabaseHas('teachers', ['name' => 'No Subject Teacher']);
});

test('creating teacher with one subject stores pivot', function () {
    $subject = Subject::create(['code' => 'MATH1', 'name' => 'Math Test']);
    $response = $this->actingAs($this->admin)->post(route('admin.teachers.store'), [
        'name' => 'Subject Teacher',
        'status' => 'active',
        'subjects' => [['subject_id' => $subject->id]],
    ]);
    $response->assertRedirect();
    $teacher = Teacher::where('name', 'Subject Teacher')->first();
    $this->assertTrue($teacher->subjects->contains($subject));
});

test('updating teacher basic data without subjects must not delete existing pivot', function () {
    $teacher = Teacher::create(['name' => 'Pivot Test', 'status' => 'active']);
    $subject = Subject::create(['code' => 'SUB1', 'name' => 'Subject 1']);
    $teacher->subjects()->attach($subject->id);

    // Update without subjects key — should keep existing assignments
    $response = $this->actingAs($this->admin)->put(route('admin.teachers.update', $teacher), [
        'name' => 'Pivot Test Updated',
        'status' => 'active',
    ]);
    $response->assertRedirect();
    $teacher->refresh();
    $this->assertEquals('Pivot Test Updated', $teacher->name);
    $this->assertTrue($teacher->subjects->contains($subject), 'Existing pivot should remain');
});

test('updating teacher with changed subjects updates pivot', function () {
    $teacher = Teacher::create(['name' => 'Change Test', 'status' => 'active']);
    $subject1 = Subject::create(['code' => 'SUB1', 'name' => 'Subject 1']);
    $subject2 = Subject::create(['code' => 'SUB2', 'name' => 'Subject 2']);
    $teacher->subjects()->attach($subject1->id);

    $response = $this->actingAs($this->admin)->put(route('admin.teachers.update', $teacher), [
        'name' => 'Change Test',
        'status' => 'active',
        'subjects' => [['subject_id' => $subject2->id]],
    ]);
    $response->assertRedirect();
    $teacher->refresh();
    $this->assertFalse($teacher->subjects->contains($subject1));
    $this->assertTrue($teacher->subjects->contains($subject2));
});

test('updating teacher with empty subjects array intentionally clears pivot', function () {
    $teacher = Teacher::create(['name' => 'Clear Test', 'status' => 'active']);
    $subject = Subject::create(['code' => 'SUB1', 'name' => 'Subject 1']);
    $teacher->subjects()->attach($subject->id);

    // Send subjects key with all empty rows → should detach
    $response = $this->actingAs($this->admin)->put(route('admin.teachers.update', $teacher), [
        'name' => 'Clear Test',
        'status' => 'active',
        'subjects' => [['subject_id' => null]],
    ]);
    $response->assertRedirect();
    $teacher->refresh();
    $this->assertEquals(0, $teacher->subjects()->count(), 'Pivot should be cleared');
});

// === Service-level tests for hasSubjectsKey behavior ===

test('teacher service update without subjects key preserves existing assignments', function () {
    $teacher = Teacher::create(['name' => 'Service Test', 'status' => 'active']);
    $subject = Subject::create(['code' => 'SRV1', 'name' => 'Service Subject']);
    $teacher->subjects()->attach($subject->id);

    $service = app(TeacherService::class);
    $service->update($teacher, ['name' => 'Service Updated', 'status' => 'active']);

    $teacher->refresh();
    $this->assertEquals('Service Updated', $teacher->name);
    $this->assertTrue($teacher->subjects->contains($subject), 'Pivot must be preserved when subjects key not sent');
});
