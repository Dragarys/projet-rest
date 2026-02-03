<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return Review::with(['dish', 'menu', 'user'])
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dish_id' => 'nullable|integer|exists:dishes,id',
            'menu_id' => 'nullable|integer|exists:menus,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        if (empty($data['dish_id']) && empty($data['menu_id'])) {
            abort(422, 'dish_id or menu_id is required');
        }

        $data['user_id'] = $request->user()->id;
        return Review::create($data);
    }
}
