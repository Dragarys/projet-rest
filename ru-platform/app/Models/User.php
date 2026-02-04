<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $student_id
 * @property string|null $staff_id
 * @property string|null $api_token
 * @property string $role
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|User where(string $column, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User orderBy(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|User findOrFail(mixed $id)
 * @method static \Illuminate\Database\Eloquent\Builder|User updateOrCreate(array $attributes, array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User paginate(int $perPage = null)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role',
        'name',
        'email',
        'password',
        'student_id',
        'staff_id',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
