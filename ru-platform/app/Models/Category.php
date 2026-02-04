<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Category where(string $column, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Category orderBy(string $column, string $direction = 'asc')
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_category');
    }
}
