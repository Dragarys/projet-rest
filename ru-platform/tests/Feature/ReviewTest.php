<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user, string $plainToken = 'test-token'): array
    {
        $user->api_token = hash('sha256', $plainToken);
        $user->save();

        return ['Authorization' => 'Bearer '.$plainToken];
    }

    public function test_can_create_review_for_dish(): void
    {
        $user = User::create(['role' => 'student', 'name' => 'Student', 'email' => 'r1@example.com', 'password' => Hash::make('secret')]);
        $dish = Dish::create(['name' => 'Sushi', 'price' => 10]);

        $headers = $this->authHeadersFor($user);

        $response = $this->postJson('/api/reviews', ['dish_id' => $dish->id, 'rating' => 5, 'comment' => 'Great'], $headers);
        $response->assertStatus(201)->assertJsonFragment(['rating' => 5]);
    }

    public function test_rating_must_be_1_to_5(): void
    {
        $user = User::create(['role' => 'student', 'name' => 'Student', 'email' => 'r2@example.com', 'password' => Hash::make('secret')]);
        $dish = Dish::create(['name' => 'Sushi', 'price' => 10]);

        $headers = $this->authHeadersFor($user);

        $response = $this->postJson('/api/reviews', ['dish_id' => $dish->id, 'rating' => 6], $headers);
        $response->assertStatus(422);
    }
}
