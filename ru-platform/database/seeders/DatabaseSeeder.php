<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'role' => 'admin',
            'name' => 'Admin RU',
            'email' => 'admin@ru.local',
            'password' => Hash::make('password'),
        ]);

        Category::insert([
            ['name' => 'Entree'],
            ['name' => 'Plat'],
            ['name' => 'Dessert'],
            ['name' => 'Boisson'],
        ]);

        Ingredient::insert([
            ['name' => 'Pates', 'unit' => 'kg'],
            ['name' => 'Tomate', 'unit' => 'kg'],
            ['name' => 'Poulet', 'unit' => 'kg'],
            ['name' => 'Fromage', 'unit' => 'kg'],
            ['name' => 'Lait', 'unit' => 'L'],
            ['name' => 'Oeuf', 'unit' => 'pcs'],
            ['name' => 'Riz', 'unit' => 'kg'],
        ]);

        // Create some dishes
        $d1 = \App\Models\Dish::create(['name' => 'Pates Bolognaise', 'description' => 'Pates maison', 'price' => 7.5]);
        $d2 = \App\Models\Dish::create(['name' => 'Salade', 'description' => 'Salade fraiche', 'price' => 4.0]);
        $d3 = \App\Models\Dish::create(['name' => 'Poulet Roti', 'description' => 'Poulet fermier', 'price' => 9.0]);

        // Attach ingredients to dishes
        $d1->ingredients()->attach(1, ['qty' => 0.2]); // Pates
        $d1->ingredients()->attach(2, ['qty' => 0.1]); // Tomate
        $d2->ingredients()->attach(2, ['qty' => 0.15]); // Tomate
        $d2->ingredients()->attach(4, ['qty' => 0.05]); // Fromage
        $d3->ingredients()->attach(3, ['qty' => 0.4]); // Poulet
        $d3->ingredients()->attach(7, ['qty' => 0.25]); // Riz

        // Create menus and menu items
        $m1 = \App\Models\Menu::create(['menu_date' => now()->addDay()->toDateString(), 'service' => 'lunch']);
        \App\Models\MenuItem::create(['menu_id' => $m1->id, 'dish_id' => $d1->id, 'quantity_limit' => 30, 'sold_count' => 0]);
        \App\Models\MenuItem::create(['menu_id' => $m1->id, 'dish_id' => $d2->id, 'quantity_limit' => 20, 'sold_count' => 0]);

        $m2 = \App\Models\Menu::create(['menu_date' => now()->addDays(2)->toDateString(), 'service' => 'dinner']);
        \App\Models\MenuItem::create(['menu_id' => $m2->id, 'dish_id' => $d3->id, 'quantity_limit' => 25, 'sold_count' => 0]);

        // Initial stock movements
        \App\Models\StockMovement::create(['ingredient_id' => 1, 'delta_qty' => 50, 'reason' => 'Initial']);
        \App\Models\StockMovement::create(['ingredient_id' => 2, 'delta_qty' => 40, 'reason' => 'Initial']);
        \App\Models\StockMovement::create(['ingredient_id' => 3, 'delta_qty' => 30, 'reason' => 'Initial']);

        // Sample orders and reviews
        $u1 = \App\Models\User::create(['role' => 'student', 'name' => 'S1', 'email' => 's1@ru.local', 'password' => \Illuminate\Support\Facades\Hash::make('password')]);
        $o1 = \App\Models\Order::create(['user_id' => $u1->id, 'menu_id' => $m1->id, 'status' => 'paid', 'total_amount' => 7.5]);
        \App\Models\OrderItem::create(['order_id' => $o1->id, 'dish_id' => $d1->id, 'quantity' => 1, 'unit_price' => 7.5]);
        \App\Models\Review::create(['user_id' => $u1->id, 'dish_id' => $d1->id, 'menu_id' => $m1->id, 'rating' => 5, 'comment' => 'Très bon']);
    }
}
