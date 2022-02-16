<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;

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

    public function scopeWithAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function getMiddleFormatDateAttribute()
    {
        return Str::ucfirst(Date::parse($this->created_at)->format('F d, Y'));
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }
}
