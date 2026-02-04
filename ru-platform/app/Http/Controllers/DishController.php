<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DishController extends Controller
{
    public function index()
    {
        return Dish::with(['categories', 'ingredients'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'required|integer|exists:ingredients,id',
            'ingredients.*.qty' => 'required|numeric|min:0.01',
        ]);

        return DB::transaction(function () use ($data, $request) {
            if ($request->hasFile('image')) {
                $path = Storage::disk('public')->putFile('dishes', $request->file('image'));
                $data['image_url'] = Storage::url($path);
            }

            $dish = Dish::create($data);
            if (! empty($data['category_ids'])) {
                $dish->categories()->sync($data['category_ids']);
            }
            if (! empty($data['ingredients'])) {
                $sync = [];
                foreach ($data['ingredients'] as $ing) {
                    $sync[$ing['id']] = ['qty' => $ing['qty']];
                }
                $dish->ingredients()->sync($sync);
            }

            return $dish->load(['categories', 'ingredients']);
        });
    }

    public function show(Dish $dish)
    {
        return $dish->load(['categories', 'ingredients']);
    }

    public function update(Request $request, Dish $dish)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:120',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'required|integer|exists:ingredients,id',
            'ingredients.*.qty' => 'required|numeric|min:0.01',
        ]);

        return DB::transaction(function () use ($data, $dish, $request) {
            if ($request->hasFile('image')) {
                // Optionally delete previous file (best-effort)
                if (! empty($dish->image_url)) {
                    $previous = preg_replace('#^/storage/#', '', $dish->image_url);
                    try {
                        Storage::disk('public')->delete($previous);
                    } catch (\Exception $e) { /* ignore */
                    }
                }
                $path = Storage::disk('public')->putFile('dishes', $request->file('image'));
                $data['image_url'] = Storage::url($path);
            }

            $dish->update($data);
            if (array_key_exists('category_ids', $data)) {
                $dish->categories()->sync($data['category_ids'] ?? []);
            }
            if (array_key_exists('ingredients', $data)) {
                $sync = [];
                foreach ($data['ingredients'] ?? [] as $ing) {
                    $sync[$ing['id']] = ['qty' => $ing['qty']];
                }
                $dish->ingredients()->sync($sync);
            }

            return $dish->load(['categories', 'ingredients']);
        });
    }

    public function destroy(Dish $dish)
    {
        $dish->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
