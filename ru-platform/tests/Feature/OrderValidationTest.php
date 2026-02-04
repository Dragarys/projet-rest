<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderValidationTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user, string $plainToken = 'test-token'): array
    {
        $user->api_token = hash('sha256', $plainToken);
        $user->save();

        return ['Authorization' => 'Bearer '.$plainToken];
    }

    public function test_order_rejected_when_quantity_limit_exceeded(): void
    {
        $user = User::create([
            'role' => 'student',
            'name' => 'Student',
            'email' => 'limit@example.com',
            'password' => Hash::make('secret'),
        ]);

        $ingredient = Ingredient::create(['name' => 'Riz', 'unit' => 'kg']);
        $dish = Dish::create(['name' => 'Plat', 'price' => 6.0]);
        $dish->ingredients()->attach($ingredient->id, ['qty' => 1.0]);

        StockMovement::create([
            'ingredient_id' => $ingredient->id,
            'delta_qty' => 10,
            'reason' => 'Initial stock',
        ]);

        $menu = Menu::create(['menu_date' => '2026-02-03', 'service' => 'lunch']);
        MenuItem::create([
            'menu_id' => $menu->id,
            'dish_id' => $dish->id,
            'quantity_limit' => 1,
            'sold_count' => 0,
        ]);

        $headers = $this->authHeadersFor($user);
        $response = $this->postJson('/api/orders', [
            'menu_id' => $menu->id,
            'items' => [
                ['dish_id' => $dish->id, 'quantity' => 2],
            ],
        ], $headers);

        $response->assertStatus(422);
    }

    public function test_order_rejected_when_stock_insufficient(): void
    {
        $user = User::create([
            'role' => 'student',
            'name' => 'Student',
            'email' => 'stock@example.com',
            'password' => Hash::make('secret'),
        ]);

        $ingredient = Ingredient::create(['name' => 'Poulet', 'unit' => 'kg']);
        $dish = Dish::create(['name' => 'Poulet', 'price' => 7.0]);
        $dish->ingredients()->attach($ingredient->id, ['qty' => 5.0]);

        StockMovement::create([
            'ingredient_id' => $ingredient->id,
            'delta_qty' => 4.0,
            'reason' => 'Initial stock',
        ]);

        $menu = Menu::create(['menu_date' => '2026-02-03', 'service' => 'dinner']);
        MenuItem::create([
            'menu_id' => $menu->id,
            'dish_id' => $dish->id,
            'quantity_limit' => 10,
            'sold_count' => 0,
        ]);

        $headers = $this->authHeadersFor($user);
        $response = $this->postJson('/api/orders', [
            'menu_id' => $menu->id,
            'items' => [
                ['dish_id' => $dish->id, 'quantity' => 1],
            ],
        ], $headers);

        $response->assertStatus(422);
    }
}
