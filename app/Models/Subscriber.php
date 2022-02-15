<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author_id'
    ];

    public function scopeWithSubscriber($query, $subscriberId)
    {
        return $query->where('user_id', $subscriberId);
    }

    public function scopeWithAuthor($query, $authorrId)
    {
        return $query->where('author_id', $authorrId);
    }
}
