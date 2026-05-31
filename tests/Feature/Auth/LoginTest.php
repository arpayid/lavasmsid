<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in(__DIR__);

test('guest can visit login page', function () {
    $response = $this->get(route('login'));
    $response->assertOk();
});

test('authenticated user is redirected from login', function () {
    $user = \App\Models\User::factory()->create();
    $response = $this->actingAs($user)->get(route('login'));
    $response->assertRedirect('/');
});

test('user can logout', function () {
    $user = \App\Models\User::factory()->create();
    $response = $this->actingAs($user)->post(route('logout'));
    $response->assertRedirect(route('public.home'));
    $this->assertGuest();
});
