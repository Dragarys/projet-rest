<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_protected_routes_require_auth(): void
    {
        $response = $this->postJson('/api/categories', ['name' => 'Test']);
        $response->assertStatus(401);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::create([
            'role' => 'student',
            'name' => 'Student',
            'email' => 'fail@example.com',
            'password' => Hash::make('secret'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'fail@example.com',
            'password' => 'wrong',
        ]);

        $response->assertStatus(401);
    }
}
