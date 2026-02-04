<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user, string $plainToken = 'test-token'): array
    {
        $user->api_token = hash('sha256', $plainToken);
        $user->save();

        return ['Authorization' => 'Bearer ' . $plainToken];
    }

    public function test_admin_can_add_stock_movement(): void
    {
        $admin = User::create([
            'role' => 'admin',
            'name' => 'Admin',
            'email' => 'admin_stock@example.com',
            'password' => Hash::make('secret'),
        ]);

        $ingredient = Ingredient::create(['name' => 'Tomato', 'unit' => 'kg']);

        $headers = $this->authHeadersFor($admin);

        $response = $this->postJson('/api/stock/movements', [
            'ingredient_id' => $ingredient->id,
            'delta_qty' => 5,
            'reason' => 'Restock',
        ], $headers);

        $response->assertStatus(201)->assertJsonFragment(['ingredient_id' => $ingredient->id]);

        $this->assertDatabaseHas('stock_movements', ['ingredient_id' => $ingredient->id, 'delta_qty' => 5]);
    }

    public function test_stock_balance_calculated_correctly(): void
    {
        $user = User::create([
            'role' => 'admin',
            'name' => 'Admin',
            'email' => 'admin_stock2@example.com',
            'password' => Hash::make('secret'),
        ]);

        $ingredient = Ingredient::create(['name' => 'Tomato', 'unit' => 'kg']);
        StockMovement::create(['ingredient_id' => $ingredient->id, 'delta_qty' => 10, 'reason' => 'Init']);
        StockMovement::create(['ingredient_id' => $ingredient->id, 'delta_qty' => -3, 'reason' => 'Used']);

        $response = $this->getJson('/api/ingredients/' . $ingredient->id, $this->authHeadersFor($user));
        $response->assertStatus(200)->assertJsonFragment(['stock' => 7]);
    }
}
