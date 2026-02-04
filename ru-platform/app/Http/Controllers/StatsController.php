<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function attendance()
    {
        $data = Order::selectRaw('DATE(created_at) as day, count(*) as reservations')
            ->whereIn('status', ['reserved', 'paid'])
            ->groupBy('day')
            ->orderBy('day', 'desc')
            ->limit(30)
            ->get();

        return $data;
    }

    public function topDishes()
    {
        $data = OrderItem::selectRaw('dish_id, sum(quantity) as total')
            ->groupBy('dish_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return $data;
    }

    public function stockAlerts(Request $request)
    {
        $threshold = (int) ($request->query('threshold') ?? 10);

        $stocks = StockMovement::selectRaw('ingredient_id, sum(delta_qty) as qty')
            ->groupBy('ingredient_id')
            ->pluck('qty', 'ingredient_id');

        return Ingredient::all()->map(function ($ingredient) use ($stocks) {
            $qty = $stocks->get($ingredient->id, 0) ?? 0;

            return ['ingredient' => $ingredient, 'qty' => $qty];
        })->filter(function ($row) use ($threshold) {
            return $row['qty'] < $threshold;
        })->values();
    }
}
