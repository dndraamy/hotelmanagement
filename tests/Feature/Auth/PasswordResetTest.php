<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    User::factory()->create(['email' => 'test@example.com']);

    $response = $this->post('/forgot-password', ['email' => 'test@example.com']);

    $response->assertSessionHasNoErrors();
});

test('reset password screen can be rendered', function () {
    $response = $this->get('/reset-password/token');

    $response->assertStatus(200);
});

test('password can be reset with valid token', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    $token = Password::createToken($user);

    $response = $this->post('/reset-password', [
        'token' => $token,
        'email' => 'test@example.com',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect('/login');
});