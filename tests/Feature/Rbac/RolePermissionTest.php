<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class)->in(__DIR__);

test('super admin can access dashboard', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Super Admin']);
    $user->assignRole($role);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));
    $response->assertOk();
});

test('guest cannot access admin dashboard', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertRedirect(route('login'));
});
