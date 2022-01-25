<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Jenssegers\Date\Date;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    /**
     * Return parent category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Return format like in current lang site - Ноябрь 27, 2021
     *
     * @return string
     */
    public function getMiddleFormatDateAttribute()
    {
        return Str::ucfirst(Date::parse($this->published_at)->format('F d, Y'));
    }

    /**
     * Return format like in current lang site - Ноя 27, 2021
     *
     * @return string
     */
    public function getMiddleShortMonthFormatDateAttribute()
    {
        return Str::ucfirst(Date::parse($this->published_at)->format('M d, Y'));
    }

    /**
     * Return time format like - 12:54
     *
     * @return string
     */
    public function getShortTimeFormatAttribute()
    {
        return Carbon::parse($this->published_at)->format('H:m');
    }

    /**
     * Return time format like - 12:54 Ноя 27, 2021
     *
     * @return string
     */
    public function getFullShortTimeFormatAttribute()
    {
        return Date::parse($this->published_at)->format('H:m M d, Y');
    }
}
