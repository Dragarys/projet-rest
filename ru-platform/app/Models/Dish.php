<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Dish
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float|null $price
 * @property int|null $stock
 * @property int|null $sold_count
 * @property string|null $image_url
 * @property \Illuminate\Support\Collection<int, \App\Models\Ingredient> $ingredients
 * @property \Illuminate\Support\Collection<int, \App\Models\Category> $categories
 * @property \Illuminate\Support\Collection<int, \App\Models\Review> $reviews
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Dish where(string $column, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Dish create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Dish orderBy(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Dish findOrFail(mixed $id)
 * @method static \Illuminate\Database\Eloquent\Builder|Dish updateOrCreate(array $attributes, array $values = [])
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Dish extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image_url'];

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'dish_category');
    }

    public function ingredients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'dish_ingredient')
            ->withPivot('qty');
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }
}
