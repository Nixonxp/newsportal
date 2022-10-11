<?php

namespace App\Models;

use App\Services\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Ad
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $link
 * @property string $type
 * @property string $showdate_start
 * @property string $showdate_end
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Ad active()
 * @method static Builder|Ad filter(\App\Services\Filters\QueryFilter $filters)
 * @method static Builder|Ad newModelQuery()
 * @method static Builder|Ad newQuery()
 * @method static Builder|Ad notActive()
 * @method static Builder|Ad query()
 * @method static Builder|Ad type($type)
 * @method static Builder|Ad whereCreatedAt($value)
 * @method static Builder|Ad whereId($value)
 * @method static Builder|Ad whereImage($value)
 * @method static Builder|Ad whereLink($value)
 * @method static Builder|Ad whereName($value)
 * @method static Builder|Ad whereShowdateEnd($value)
 * @method static Builder|Ad whereShowdateStart($value)
 * @method static Builder|Ad whereType($value)
 * @method static Builder|Ad whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'type',
        'image',
        'showdate_start',
        'showdate_end'
    ];

    public const TYPES = [
        'header',
        'side'
    ];

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query
            ->where('showdate_start', '<=', Carbon::now())
            ->where('showdate_end', '>', Carbon::now());
    }

    public function scopeNotActive($query)
    {
        return $query
            ->where('showdate_start', '>=', Carbon::now())
            ->orWhere('showdate_end', '<', Carbon::now());
    }

    public function isActive()
    {
        return ($this->showdate_start <= now())
            && ($this->showdate_end > now());
    }
}
