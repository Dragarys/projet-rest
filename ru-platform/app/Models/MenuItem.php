<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['menu_id', 'dish_id', 'quantity_limit', 'sold_count'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
