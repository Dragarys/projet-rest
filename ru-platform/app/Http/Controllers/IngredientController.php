<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngredientController extends Controller
{
    public function index()
    {
        return Ingredient::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'unit' => 'required|string|max:20',
        ]);

        return Ingredient::create($data);
    }

    public function show(Ingredient $ingredient)
    {
        $stock = StockMovement::where('ingredient_id', $ingredient->id)->sum('delta_qty');
        return ['ingredient' => $ingredient, 'stock' => $stock];
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:120',
            'unit' => 'sometimes|string|max:20',
        ]);

        $ingredient->update($data);
        return $ingredient;
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
