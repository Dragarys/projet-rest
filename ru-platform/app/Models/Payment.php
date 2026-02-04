<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $order_id
 * @property string $method
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Payment where(string $column, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Payment updateOrCreate(array $attributes, array $values = [])
 * @property-read \App\Models\Order $order
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'status', 'method', 'amount'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
