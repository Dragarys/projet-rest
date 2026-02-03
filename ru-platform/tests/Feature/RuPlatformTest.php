<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RuPlatformTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user, string $plainToken = 'test-token'): array
    {
        $user->api_token = hash('sha256', $plainToken);
        $user->save();

        return ['Authorization' => 'Bearer ' . $plainToken];
    }

    public function test_login_returns_token(): void
    {
        User::create([
            'role' => 'student',
            'name' => 'Test Student',
            'email' => 'student@example.com',
            'password' => Hash::make('secret'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'student@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user' => ['id', 'email', 'role']]);
    }

    public function test_public_menus_endpoint(): void
    {
        $dish = Dish::create([
            'name' => 'Pasta',
            'description' => 'Test dish',
            'price' => 5.5,
        ]);

        $menu = Menu::create([
            'menu_date' => '2026-02-03',
            'service' => 'lunch',
        ]);

        MenuItem::create([
            'menu_id' => $menu->id,
            'dish_id' => $dish->id,
            'quantity_limit' => 50,
            'sold_count' => 0,
        ]);

        $response = $this->getJson('/api/menus');

        $response->assertStatus(200)
            ->assertJsonFragment(['service' => 'lunch']);

        $this->assertStringContainsString('2026-02-03', $response->getContent());
    }

    public function test_order_flow_decrements_stock_and_limits(): void
    {
        $user = User::create([
            'role' => 'student',
            'name' => 'Student',
            'email' => 's1@example.com',
            'password' => Hash::make('secret'),
        ]);

        $ingredient = Ingredient::create(['name' => 'Riz', 'unit' => 'kg']);
        $dish = Dish::create(['name' => 'Poulet Riz', 'description' => 'Test', 'price' => 6.5]);
        $dish->ingredients()->attach($ingredient->id, ['qty' => 0.5]);

        StockMovement::create([
            'ingredient_id' => $ingredient->id,
            'delta_qty' => 10,
            'reason' => 'Initial stock',
        ]);

        $menu = Menu::create(['menu_date' => '2026-02-03', 'service' => 'lunch']);
        MenuItem::create([
            'menu_id' => $menu->id,
            'dish_id' => $dish->id,
            'quantity_limit' => 5,
            'sold_count' => 0,
        ]);

        $headers = $this->authHeadersFor($user);

        $response = $this->postJson('/api/orders', [
            'menu_id' => $menu->id,
            'items' => [
                ['dish_id' => $dish->id, 'quantity' => 2],
            ],
        ], $headers);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => 'reserved']);

        $this->assertDatabaseHas('menu_items', [
            'menu_id' => $menu->id,
            'dish_id' => $dish->id,
            'sold_count' => 2,
        ]);

        $remaining = StockMovement::where('ingredient_id', $ingredient->id)->sum('delta_qty');
        $this->assertSame(9.0, (float) $remaining);
    }

    public function test_payment_marks_order_as_paid(): void
    {
        $user = User::create([
            'role' => 'student',
            'name' => 'Student',
            'email' => 's2@example.com',
            'password' => Hash::make('secret'),
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'menu_id' => Menu::create(['menu_date' => '2026-02-03', 'service' => 'dinner'])->id,
            'status' => 'reserved',
            'total_amount' => 12.5,
        ]);

        $headers = $this->authHeadersFor($user);
        $response = $this->postJson('/api/orders/' . $order->id . '/pay', [], $headers);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'paid']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);
    }

    public function test_stats_endpoints(): void
    {
        $admin = User::create([
            'role' => 'admin',
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
        ]);

        $headers = $this->authHeadersFor($admin);

        $dish = Dish::create(['name' => 'Burger', 'price' => 8.0]);
        $menu = Menu::create(['menu_date' => '2026-02-03', 'service' => 'lunch']);
        $order = Order::create([
            'user_id' => $admin->id,
            'menu_id' => $menu->id,
            'status' => 'paid',
            'total_amount' => 8.0,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'dish_id' => $dish->id,
            'quantity' => 1,
            'unit_price' => 8.0,
        ]);

        $attendance = $this->getJson('/api/stats/attendance', $headers);
        $attendance->assertStatus(200);

        $top = $this->getJson('/api/stats/top-dishes', $headers);
        $top->assertStatus(200);

        $stockAlerts = $this->getJson('/api/stats/stock-alerts', $headers);
        $stockAlerts->assertStatus(200);
    }
}
