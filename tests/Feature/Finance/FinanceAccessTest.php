<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Permission::firstOrCreate(['name' => 'finance.view', 'guard_name' => 'web']);
    $this->superAdmin = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(Permission::all());
    $this->superAdmin->assignRole($role);
});

test('super admin can access finance dashboard', function () {
    $this->actingAs($this->superAdmin)->get(route('admin.finance.dashboard'))->assertOk();
});

test('guest cannot access finance dashboard', function () {
    $this->get(route('admin.finance.dashboard'))->assertRedirect(route('login'));
});
