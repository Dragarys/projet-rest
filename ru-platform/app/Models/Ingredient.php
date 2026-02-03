<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'unit'];

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_ingredient')
            ->withPivot('qty');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
