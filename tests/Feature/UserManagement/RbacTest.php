<?php

use App\Models\User;
use App\Modules\UserManagement\Services\RoleService;
use App\Modules\UserManagement\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create permissions first
    foreach (['user.view', 'user.create', 'user.update', 'user.delete', 'role.view', 'role.create', 'role.update', 'role.delete'] as $p) {
        Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }

    $this->admin = User::factory()->create();
    $adminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $adminRole->givePermissionTo(Permission::all());
    $this->admin->assignRole($adminRole);

    $this->regularUser = User::factory()->create(['email' => 'regular@test.com']);
    $regularRole = Role::firstOrCreate(['name' => 'Guru', 'guard_name' => 'web']);
    $this->regularUser->assignRole($regularRole);
});

test('guest cannot access admin dashboard', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('user without permission cannot access user management users index', function () {
    $this->actingAs($this->regularUser)->get(route('admin.user-management.users.index'))->assertForbidden();
});

test('super admin can access user management users index', function () {
    $this->actingAs($this->admin)->get(route('admin.user-management.users.index'))->assertOk();
});

test('role permissions are seeded', function () {
    $this->artisan('db:seed', ['--class' => 'Database\Seeders\RolePermissionSeeder'])->assertExitCode(0);

    $role = Role::findByName('Super Admin');
    expect($role->permissions->count())->toBeGreaterThan(0);
    expect(Permission::where('name', 'role.view')->exists())->toBeTrue();
    expect(Permission::where('name', 'role.create')->exists())->toBeTrue();
    expect(Permission::where('name', 'role.update')->exists())->toBeTrue();
    expect(Permission::where('name', 'role.delete')->exists())->toBeTrue();
});

test('cannot delete Super Admin role', function () {
    $this->artisan('db:seed', ['--class' => 'Database\Seeders\RolePermissionSeeder'])->assertExitCode(0);

    $roleService = app(RoleService::class);
    $roleService->delete('Super Admin');
})->throws(HttpException::class, 'The Super Admin role cannot be deleted');

test('user cannot delete own account', function () {
    $this->actingAs($this->admin);
    $userService = app(UserService::class);

    $threw = false;
    try {
        $userService->delete($this->admin->id);
    } catch (HttpException $e) {
        $threw = true;
        expect($e->getMessage())->toContain('cannot delete your own account');
    }

    expect($threw)->toBeTrue();
});

test('login page does not contain default credential text', function () {
    $response = $this->get(route('login'));
    $response->assertDontSee('Default:');
    $response->assertDontSee('admin@lavasmsid.local');
    $response->assertDontSeeText('admin@lavasmsid.local / password');
});
