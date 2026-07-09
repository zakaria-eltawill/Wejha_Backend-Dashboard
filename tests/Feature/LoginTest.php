<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_username_and_password(): void
    {
        // 1. Create a user
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
        ]);

        // 2. Attempt login using Filament's custom login logic or directly
        $credentials = [
            'username' => 'testuser',
            'password' => 'password123',
        ];

        $this->assertTrue(auth()->attempt($credentials));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        // 1. Create a user
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
        ]);

        // 2. Attempt login with wrong password
        $credentials = [
            'username' => 'testuser',
            'password' => 'wrongpassword',
        ];

        $this->assertFalse(auth()->attempt($credentials));
        $this->assertGuest();
    }
}
