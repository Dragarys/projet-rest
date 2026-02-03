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
        ]);
    }
}
