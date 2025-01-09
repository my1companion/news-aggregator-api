<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'token',
        ]);
    }

    public function test_user_can_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'user' => ['name', 'email'],
            'token',
        ]);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create([
         'email' => 'unique_' . uniqid() . '@example.com',
        ]);
        $token = $user->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logged out']);
    }

    public function test_user_can_request_password_reset()
    {
        // $user = User::factory()->create([ 'email' => 'unique_' . uniqid() . '@example.com',]);

        $response = $this->postJson('/api/password/forgot', [
            'email' => 'johndoe@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'We have emailed your password reset link.']);
    }

    public function test_user_can_reset_password()
    {
        $user = User::factory()->create(['email' => 'joh1ndoe@example.com']);
        $token = Password::createToken($user);

        $response = $this->postJson('/api/password/reset', [
            'email' => 'joh1ndoe@example.com',
            'token' => $token,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Your password has been reset.']);
    }
}
