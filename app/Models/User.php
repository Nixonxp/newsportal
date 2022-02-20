<?php

namespace App\Models;

use App\Services\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }

    public function scopeWithAuthorRoles($query)
    {
       $roleIds = Role::select('id')
           ->whereIn('name', ['Chief-editor', 'Editor'])
           ->get()
           ->map(function($role) {
               return $role->id;
           })->toArray();

        return $query->whereIn('role_id', $roleIds);
    }

    public function scopeWithAdminRole($query)
    {
       $roleAdminId = Role::select('id')
           ->admin()
           ->first()
           ->id;

        return $query->where('role_id', $roleAdminId);
    }

    /**
     * Check of roles entry of user
     *
     * @param $roles
     * @return bool
     */
    public function hasAnyRole($roles): bool
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            if (strtolower($role) === strtolower($this->role->name)) {
                return true;
            }
        }

        return false;
    }
}
