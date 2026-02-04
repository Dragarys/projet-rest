<?php

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Dish;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

DB::transaction(function () {
    $menu = Menu::with('items')->orderBy('id', 'desc')->first();
    if (! $menu) {
        echo "No menus found\n";

        return;
    }

    $keepDishIds = $menu->items->pluck('dish_id')->unique()->values()->all();

    Order::query()->delete();
    Payment::query()->delete();
    OrderItem::query()->delete();
    Review::query()->delete();

    MenuItem::whereNot('menu_id', $menu->id)->delete();
    Menu::where('id', '!=', $menu->id)->delete();

    Dish::whereNotIn('id', $keepDishIds)->delete();
    Category::query()->delete();

    echo "Kept menu ID {$menu->id}\n";
});
