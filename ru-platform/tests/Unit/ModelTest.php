<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Review;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_dish_relationships(): void
    {
        $dish = Dish::create(['name' => 'UnitDish', 'price' => 3.0]);
        $cat = Category::create(['name' => 'C']);
        $ing = Ingredient::create(['name' => 'I', 'unit' => 'kg']);

        $dish->categories()->attach($cat->id);
        $dish->ingredients()->attach($ing->id, ['qty' => 0.1]);

        $this->assertTrue($dish->categories()->exists());
        $this->assertTrue($dish->ingredients()->exists());
    }

    public function test_stock_movements_sum(): void
    {
        $ing = Ingredient::create(['name' => 'I2', 'unit' => 'kg']);
        StockMovement::create(['ingredient_id' => $ing->id, 'delta_qty' => 10, 'reason' => 'Init']);
        StockMovement::create(['ingredient_id' => $ing->id, 'delta_qty' => -4, 'reason' => 'Used']);

        $sum = StockMovement::where('ingredient_id', $ing->id)->sum('delta_qty');
        $this->assertEquals(6, $sum);
    }

    public function test_payment_belongs_to_order(): void
    {
        $user = User::create(['role' => 'student', 'name' => 'U', 'email' => 'u@example.com', 'password' => Hash::make('secret')]);
        $menu = \App\Models\Menu::create(['menu_date' => now()->toDateString(), 'service' => 'lunch']);
        $order = Order::create(['user_id' => $user->id, 'menu_id' => $menu->id, 'status' => 'reserved', 'total_amount' => 5]);
        $pay = Payment::create(['order_id' => $order->id, 'status' => 'paid', 'method' => 'sim', 'amount' => 5]);

        $this->assertEquals($order->id, $pay->order->id);
    }

    public function test_review_relation(): void
    {
        $user = User::create(['role' => 'student', 'name' => 'U2', 'email' => 'u2@example.com', 'password' => Hash::make('secret')]);
        $dish = Dish::create(['name' => 'RDish', 'price' => 2.5]);
        $menu = \App\Models\Menu::create(['menu_date' => now()->toDateString(), 'service' => 'lunch']);

        $review = Review::create(['user_id' => $user->id, 'dish_id' => $dish->id, 'menu_id' => $menu->id, 'rating' => 4, 'comment' => 'ok']);

        $this->assertEquals($dish->id, $review->dish->id);
        $this->assertEquals($user->id, $review->user->id);
    }
}
