<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Review
 *
 * @property int $id
 * @property int $user_id
 * @property int $dish_id
 * @property int $rating
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Review where(string $column, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Review create(array $attributes = [])
 * @property-read \App\Models\Dish $dish
 * @property-read \App\Models\User $user
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'dish_id', 'menu_id', 'rating', 'comment'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dish(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }

    public function menu(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
