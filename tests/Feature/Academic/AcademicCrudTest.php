<?php

use App\Models\User;
use App\Modules\Academic\Models\Competency;
use App\Modules\Academic\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['academic.view', 'academic.create', 'academic.update', 'academic.delete'] as $p) {
        Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }

    $this->admin = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());
    $this->admin->assignRole($role);

    $this->regularUser = User::factory()->create(['email' => 'regular@test.com']);
});

test('guest cannot access academic pages', function () {
    foreach (['academic-years.index', 'departments.index', 'subjects.index'] as $route) {
        $this->get(route('admin.'.$route))->assertRedirect(route('login'));
    }
});

test('user without permission is denied access', function () {
    $this->actingAs($this->regularUser)->get(route('admin.academic-years.index'))->assertForbidden();
});

test('super admin can create academic year', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.academic-years.store'), [
        'name' => '2027/2028',
        'start_date' => '2027-07-01',
        'end_date' => '2028-06-30',
    ]);

    $response->assertRedirect(route('admin.academic-years.index'));
    $this->assertDatabaseHas('academic_years', ['name' => '2027/2028']);
});

test('validation error on create academic year', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.academic-years.store'), [
        'name' => '',
        'start_date' => 'invalid',
    ]);

    $response->assertSessionHasErrors(['name', 'start_date']);
});

test('super admin can create department', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.departments.store'), [
        'code' => 'TEST',
        'name' => 'Test Jurusan',
    ]);

    $response->assertRedirect(route('admin.departments.index'));
    $this->assertDatabaseHas('departments', ['code' => 'TEST']);
});

test('super admin can delete department with no related data', function () {
    $dept = Department::create([
        'code' => 'DEL',
        'name' => 'To Delete',
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.departments.destroy', $dept));
    $response->assertRedirect(route('admin.departments.index'));
    $this->assertSoftDeleted('departments', ['id' => $dept->id]);
});

test('super admin cannot delete department with related data', function () {
    $dept = Department::create([
        'code' => 'HAS',
        'name' => 'Has Data',
    ]);
    Competency::create([
        'department_id' => $dept->id,
        'code' => 'C1',
        'name' => 'Test',
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.departments.destroy', $dept));
    $response->assertStatus(403);
});
