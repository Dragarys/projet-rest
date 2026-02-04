<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property float|null $total
 * @property float|null $total_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order where(string $column, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Order create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Order selectRaw(string $columns)
 * @method static \Illuminate\Database\Eloquent\Builder|Order paginate(int $perPage = null)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'menu_id', 'status', 'total_amount', 'reserved_for'];

    protected $casts = [
        'reserved_for' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
