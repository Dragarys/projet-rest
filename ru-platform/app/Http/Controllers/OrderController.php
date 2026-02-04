<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Order::with('items.dish', 'menu');

        if ($user->role === 'student') {
            $query->where('user_id', $user->id);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_id' => 'required|integer|exists:menus,id',
            'items' => 'required|array|min:1',
            'items.*.dish_id' => 'required|integer|exists:dishes,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        return DB::transaction(function () use ($data, $user) {
            $menuItems = MenuItem::where('menu_id', $data['menu_id'])->get()->keyBy('dish_id');
            $requiredIngredients = [];
            $total = 0;

            foreach ($data['items'] as $item) {
                $menuItem = $menuItems->get($item['dish_id']);
                if (!$menuItem) {
                    abort(422, 'Dish not in menu');
                }

                if ($menuItem->quantity_limit !== null) {
                    $remaining = $menuItem->quantity_limit - $menuItem->sold_count;
                    if ($item['quantity'] > $remaining) {
                        abort(422, 'Quantity limit reached');
                    }
                }

                $dish = Dish::with('ingredients')->findOrFail($item['dish_id']);
                $total += $dish->price * $item['quantity'];

                foreach ($dish->ingredients as $ingredient) {
                    $need = $ingredient->pivot->qty * $item['quantity'];
                    $requiredIngredients[$ingredient->id] = ($requiredIngredients[$ingredient->id] ?? 0) + $need;
                }
            }

            foreach ($requiredIngredients as $ingredientId => $qtyNeeded) {
                $current = StockMovement::where('ingredient_id', $ingredientId)->sum('delta_qty');
                if ($current < $qtyNeeded) {
                    abort(422, 'Insufficient stock for ingredient ' . $ingredientId);
                }
            }

            $order = Order::create([
                'user_id' => $user->id,
                'menu_id' => $data['menu_id'],
                'status' => 'reserved',
                'total_amount' => $total,
            ]);

            foreach ($data['items'] as $item) {
                $dish = Dish::findOrFail($item['dish_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'dish_id' => $dish->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $dish->price,
                ]);

                MenuItem::where('menu_id', $data['menu_id'])
                    ->where('dish_id', $dish->id)
                    ->increment('sold_count', $item['quantity']);
            }

            foreach ($requiredIngredients as $ingredientId => $qtyNeeded) {
                StockMovement::create([
                    'ingredient_id' => $ingredientId,
                    'delta_qty' => -1 * $qtyNeeded,
                    'reason' => 'Order reservation #' . $order->id,
                ]);
            }

            return $order->load('items.dish', 'menu');
        });
    }

    public function show(Order $order)
    {
        return $order->load('items.dish', 'menu', 'payment');
    }

    public function cancel(Request $request, Order $order)
    {
        $user = $request->user();
        if ($user->role === 'student' && $order->user_id !== $user->id) {
            abort(403);
        }

        if ($order->status === 'paid') {
            abort(422, 'Cannot cancel a paid order');
        }

        $order->status = 'cancelled';
        $order->save();

        return $order;
    }
}
