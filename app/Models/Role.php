<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    public const ADMIN = 'Admin';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeAdmin($query)
    {
        return $query->where('name', self::ADMIN);
    }
}
