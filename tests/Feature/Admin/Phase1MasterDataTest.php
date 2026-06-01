<?php

use App\Models\User;
use App\Modules\Academic\Models\Competency;
use App\Modules\Academic\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach ([
        'academic.view', 'academic.create', 'academic.update', 'academic.delete',
        'user.view', 'role.view', 'settings.view', 'settings.update',
    ] as $p) {
        Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }

    $this->admin = User::factory()->create();
    $adminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $adminRole->givePermissionTo(Permission::all());
    $this->admin->assignRole($adminRole);

    $this->regularUser = User::factory()->create(['email' => 'regular@test.com']);
});

test('guest cannot access admin academic-years index', function () {
    $this->get(route('admin.academic-years.index'))->assertRedirect(route('login'));
});

test('user without academic.view cannot access departments index', function () {
    $this->actingAs($this->regularUser)->get(route('admin.departments.index'))->assertForbidden();
});

test('user with academic.view can access departments index', function () {
    $this->actingAs($this->admin)->get(route('admin.departments.index'))->assertOk();
});

test('user with academic.create can create department', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.departments.store'), [
        'code' => 'PHASE1',
        'name' => 'Phase 1 Test',
    ]);
    $response->assertRedirect(route('admin.departments.index'));
    $this->assertDatabaseHas('departments', ['code' => 'PHASE1']);
});

test('validation fails when department code or name is missing', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.departments.store'), [
        'code' => '', 'name' => '',
    ]);
    $response->assertSessionHasErrors(['code', 'name']);
});

test('department cannot be deleted if it has related data', function () {
    $dept = Department::create(['code' => 'DEL1', 'name' => 'Has Data']);
    Competency::create(['department_id' => $dept->id, 'code' => 'C1', 'name' => 'Test']);
    $response = $this->actingAs($this->admin)->delete(route('admin.departments.destroy', $dept));
    $response->assertStatus(403);
});

test('user with settings.view can access settings page', function () {
    $this->actingAs($this->admin)->get(route('admin.settings.index'))->assertOk();
});

test('user without settings.view cannot access settings page', function () {
    $this->actingAs($this->regularUser)->get(route('admin.settings.index'))->assertForbidden();
});

test('Super Admin can access users index', function () {
    $this->actingAs($this->admin)->get(route('admin.user-management.users.index'))->assertOk();
});

test('Super Admin can access roles index', function () {
    $this->actingAs($this->admin)->get(route('admin.user-management.roles.index'))->assertOk();
});
