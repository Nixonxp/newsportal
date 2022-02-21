<?php

namespace App\Models;

use App\Services\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
