<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        return StockMovement::with('ingredient')->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ingredient_id' => 'required|integer|exists:ingredients,id',
            'delta_qty' => 'required|numeric',
            'reason' => 'required|string|max:120',
        ]);

        return StockMovement::create($data);
    }
}
