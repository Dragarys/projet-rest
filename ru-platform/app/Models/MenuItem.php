<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MenuItem
 *
 * @property int $id
 * @property int $menu_id
 * @property int $dish_id
 * @property int|null $limit
 * @property int|null $quantity_limit
 * @property int|null $sold_count
 *
 * @method bool increment(string $column, int $amount = 1)
 *
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem where(string $column, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem findOrFail(mixed $id)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
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
