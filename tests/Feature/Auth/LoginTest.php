<?php

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('guest can view login page', function () {
    $this->get(route('login'))->assertOk();
});

test('user can login successfully', function () {
    $user = User::factory()->create(['password' => Hash::make('password')]);
    $response = $this->post(route('login'), ['email' => $user->email, 'password' => 'password']);
    $response->assertRedirect();
    $this->assertAuthenticated();
});

test('invalid login is rejected', function () {
    $user = User::factory()->create(['password' => Hash::make('password')]);
    $this->post(route('login'), ['email' => $user->email, 'password' => 'wrongpassword'])
        ->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('admin dashboard requires authentication', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('authenticated user can see dashboard', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('admin.dashboard'));
    $response->assertOk();
});

test('user can logout', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->withoutMiddleware(VerifyCsrfToken::class)
        ->post(route('logout'));
    $response->assertRedirect(route('public.home'));
    $this->assertGuest();
});
