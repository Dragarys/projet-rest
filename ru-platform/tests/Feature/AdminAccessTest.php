<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user, string $plainToken = 'admin-token'): array
    {
        $user->api_token = hash('sha256', $plainToken);
        $user->save();

        return ['Authorization' => 'Bearer '.$plainToken];
    }

    public function test_student_cannot_list_users(): void
    {
        $student = User::create([
            'role' => 'student',
            'name' => 'Student',
            'email' => 'student1@example.com',
            'password' => Hash::make('secret'),
        ]);

        $headers = $this->authHeadersFor($student, 'student-token');
        $response = $this->getJson('/api/users', $headers);

        $response->assertStatus(403);
    }

    public function test_staff_cannot_list_users(): void
    {
        $staff = User::create([
            'role' => 'staff',
            'name' => 'Staff',
            'email' => 'staff1@example.com',
            'password' => Hash::make('secret'),
        ]);

        $headers = $this->authHeadersFor($staff, 'staff-token');
        $response = $this->getJson('/api/users', $headers);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::create([
            'role' => 'admin',
            'name' => 'Admin',
            'email' => 'admin1@example.com',
            'password' => Hash::make('secret'),
        ]);

        $headers = $this->authHeadersFor($admin, 'admin-token');
        $response = $this->postJson('/api/users', [
            'role' => 'student',
            'name' => 'New Student',
            'email' => 'newstudent@example.com',
            'password' => 'secret123',
        ], $headers);

        $response->assertStatus(201)
            ->assertJsonFragment(['email' => 'newstudent@example.com']);
    }

    public function test_invalid_email_is_rejected(): void
    {
        $admin = User::create([
            'role' => 'admin',
            'name' => 'Admin',
            'email' => 'admin2@example.com',
            'password' => Hash::make('secret'),
        ]);

        $headers = $this->authHeadersFor($admin, 'admin-token-2');
        $response = $this->postJson('/api/users', [
            'role' => 'student',
            'name' => 'Bad Email',
            'email' => 'not-an-email',
            'password' => 'secret123',
        ], $headers);

        $response->assertStatus(422);
    }
}
