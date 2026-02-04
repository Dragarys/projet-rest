<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Dish;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DishTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user, string $plainToken = 'test-token'): array
    {
        $user->api_token = hash('sha256', $plainToken);
        $user->save();

        return ['Authorization' => 'Bearer '.$plainToken];
    }

    public function test_can_list_dishes(): void
    {
        Dish::create(['name' => 'Test Dish', 'price' => 5.0]);
        $response = $this->getJson('/api/dishes');
        $response->assertStatus(200)->assertJsonStructure([['id', 'name', 'price']]);
    }

    public function test_can_create_dish_with_categories(): void
    {
        $admin = User::create([
            'role' => 'admin',
            'name' => 'Admin',
            'email' => 'admin_create_dish@example.com',
            'password' => Hash::make('secret'),
        ]);

        $cat = Category::create(['name' => 'Entree']);

        $headers = $this->authHeadersFor($admin);

        $response = $this->postJson('/api/dishes', [
            'name' => 'Salad',
            'price' => 3.5,
            'category_ids' => [$cat->id],
        ], $headers);

        $response->assertStatus(201)->assertJsonFragment(['name' => 'Salad']);
    }
}
