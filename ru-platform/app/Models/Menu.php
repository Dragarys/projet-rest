<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['menu_date', 'service'];

    protected $casts = [
        'menu_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
