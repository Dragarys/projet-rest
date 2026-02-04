<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ingredient
 *
 * @property int $id
 * @property string $name
 * @property int|null $stock
 * @property-read \Illuminate\Database\Eloquent\Relations\Pivot $pivot
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient where(string $column, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient orderBy(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient findOrFail(mixed $id)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
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
