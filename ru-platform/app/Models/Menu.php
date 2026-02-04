<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menu
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Menu where(string $column, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Menu orderBy(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Menu findOrFail(mixed $id)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
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
