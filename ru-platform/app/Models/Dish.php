<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image_url'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'dish_category');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'dish_ingredient')
            ->withPivot('qty');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
