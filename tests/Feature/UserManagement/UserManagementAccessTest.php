<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Permission::firstOrCreate(['name' => 'user.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'user.create', 'guard_name' => 'web']);

    $this->admin = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());
    $this->admin->assignRole($role);

    $this->regularUser = User::factory()->create(['email' => 'regular@test.com']);
});

test('user without permission cannot access user management', function () {
    $this->actingAs($this->regularUser)->get(route('admin.user-management.users.index'))->assertForbidden();
});

test('super admin can access user management', function () {
    $this->actingAs($this->admin)->get(route('admin.user-management.users.index'))->assertOk();
});

test('role permissions exist after seeding', function () {
    $this->artisan('db:seed', ['--class' => 'Database\Seeders\RolePermissionSeeder'])->assertExitCode(0);
    $role = Role::findByName('Super Admin');
    expect($role->permissions->count())->toBeGreaterThan(0);
});
