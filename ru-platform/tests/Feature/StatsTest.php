<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user, string $plainToken = 'test-token'): array
    {
        $user->api_token = hash('sha256', $plainToken);
        $user->save();

        return ['Authorization' => 'Bearer ' . $plainToken];
    }

    public function test_attendance_and_top_dishes_and_stock_alerts(): void
    {
        $admin = User::create(['role' => 'admin', 'name' => 'Admin', 'email' => 'admin_stats@example.com', 'password' => Hash::make('secret')]);
        $headers = $this->authHeadersFor($admin);

        $dishA = Dish::create(['name' => 'A', 'price' => 5.0]);
        $dishB = Dish::create(['name' => 'B', 'price' => 6.0]);

        $menu = Menu::create(['menu_date' => '2026-02-03', 'service' => 'lunch']);

        $order1 = Order::create(['user_id' => $admin->id, 'menu_id' => $menu->id, 'status' => 'paid', 'total_amount' => 5.0]);
        OrderItem::create(['order_id' => $order1->id, 'dish_id' => $dishA->id, 'quantity' => 2, 'unit_price' => 5.0]);

        $order2 = Order::create(['user_id' => $admin->id, 'menu_id' => $menu->id, 'status' => 'reserved', 'total_amount' => 6.0]);
        OrderItem::create(['order_id' => $order2->id, 'dish_id' => $dishB->id, 'quantity' => 1, 'unit_price' => 6.0]);

        // Stock: one ingredient below threshold
        $ing1 = Ingredient::create(['name' => 'Low', 'unit' => 'kg']);
        $ing2 = Ingredient::create(['name' => 'High', 'unit' => 'kg']);
        StockMovement::create(['ingredient_id' => $ing1->id, 'delta_qty' => 2, 'reason' => 'Init']);
        StockMovement::create(['ingredient_id' => $ing2->id, 'delta_qty' => 100, 'reason' => 'Init']);

        $attendance = $this->getJson('/api/stats/attendance', $headers);
        $attendance->assertStatus(200)->assertJsonCount(1);

        $top = $this->getJson('/api/stats/top-dishes', $headers);
        $top->assertStatus(200)->assertJsonFragment(['dish_id' => $dishA->id]);

        $alerts = $this->getJson('/api/stats/stock-alerts?threshold=10', $headers);
        $alerts->assertStatus(200)->assertJsonCount(1);
    }
}
