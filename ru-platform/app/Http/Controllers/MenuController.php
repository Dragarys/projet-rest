<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        return Menu::with('items.dish')->orderBy('menu_date', 'desc')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_date' => 'required|date',
            'service' => 'required|in:lunch,dinner',
            'items' => 'nullable|array',
            'items.*.dish_id' => 'required|integer|exists:dishes,id',
            'items.*.quantity_limit' => 'nullable|integer|min:0',
        ]);

        return DB::transaction(function () use ($data) {
            $menu = Menu::create($data);

            if (!empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    MenuItem::create([
                        'menu_id' => $menu->id,
                        'dish_id' => $item['dish_id'],
                        'quantity_limit' => $item['quantity_limit'] ?? null,
                        'sold_count' => 0,
                    ]);
                }
            }

            return $menu->load('items.dish');
        });
    }

    public function show(Menu $menu)
    {
        return $menu->load('items.dish');
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'menu_date' => 'sometimes|date',
            'service' => 'sometimes|in:lunch,dinner',
            'items' => 'nullable|array',
            'items.*.dish_id' => 'required|integer|exists:dishes,id',
            'items.*.quantity_limit' => 'nullable|integer|min:0',
        ]);

        return DB::transaction(function () use ($data, $menu) {
            $menu->update($data);

            if (array_key_exists('items', $data)) {
                MenuItem::where('menu_id', $menu->id)->delete();
                foreach ($data['items'] ?? [] as $item) {
                    MenuItem::create([
                        'menu_id' => $menu->id,
                        'dish_id' => $item['dish_id'],
                        'quantity_limit' => $item['quantity_limit'] ?? null,
                        'sold_count' => 0,
                    ]);
                }
            }

            return $menu->load('items.dish');
        });
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
