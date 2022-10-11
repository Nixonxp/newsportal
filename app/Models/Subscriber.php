<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;

/**
 * App\Models\Subscriber
 *
 * @property int $id
 * @property int $user_id
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $author
 * @property-read mixed $middle_format_date
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber withAuthor($authorId)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber withSubscriber($subscriberId)
 * @mixin \Eloquent
 */
class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author_id',
        'created_at',
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
