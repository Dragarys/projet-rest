<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StockMovement
 *
 * @property int $id
 * @property int $ingredient_id
 * @property int $delta_qty
 * @property string $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\Ingredient $ingredient
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StockMovement where(string $column, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|StockMovement create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|StockMovement selectRaw(string $columns)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = ['ingredient_id', 'delta_qty', 'reason'];

    public function ingredient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
