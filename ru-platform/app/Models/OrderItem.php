<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int $dish_id
 * @property int $quantity
 * @property float|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem where(string $column, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem selectRaw(string $columns)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class OrderItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['order_id', 'dish_id', 'quantity', 'unit_price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
