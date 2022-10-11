<?php

namespace App\Models;

use App\Services\Filters\QueryFilter;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Jenssegers\Date\Date;
use Illuminate\Support\Str;

/**
 * Model for news entities
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $image
 * @property int $category_id
 * @property int|null $user_id
 * @property string|null $excerpt
 * @property string $content
 * @property bool $is_published
 * @property string|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property bool $main_slider
 * @property bool $popular
 * @property string|null $source_name
 * @property string|null $source_link
 * @property string|null $source_image
 * @property bool|null $partner_news
 * @property-read \App\Models\Category $category
 * @property-read string $full_short_time_format
 * @property-read string $middle_format_date
 * @property-read string $middle_short_month_format_date
 * @property-read string $short_time_format
 * @property-read object $status
 * @property-read \App\Models\User|null $user
 * @method static Builder|Post category($cid)
 * @method static Builder|Post external()
 * @method static \Database\Factories\PostFactory factory(...$parameters)
 * @method static Builder|Post filter(\App\Services\Filters\QueryFilter $filters)
 * @method static Builder|Post newModelQuery()
 * @method static Builder|Post newQuery()
 * @method static Builder|Post nowPublished()
 * @method static \Illuminate\Database\Query\Builder|Post onlyTrashed()
 * @method static Builder|Post popular()
 * @method static Builder|Post published()
 * @method static Builder|Post query()
 * @method static Builder|Post whereCategoryId($value)
 * @method static Builder|Post whereContent($value)
 * @method static Builder|Post whereCreatedAt($value)
 * @method static Builder|Post whereDeletedAt($value)
 * @method static Builder|Post whereExcerpt($value)
 * @method static Builder|Post whereId($value)
 * @method static Builder|Post whereImage($value)
 * @method static Builder|Post whereIsPublished($value)
 * @method static Builder|Post whereMainSlider($value)
 * @method static Builder|Post wherePartnerNews($value)
 * @method static Builder|Post wherePopular($value)
 * @method static Builder|Post wherePublishedAt($value)
 * @method static Builder|Post whereSlug($value)
 * @method static Builder|Post whereSourceImage($value)
 * @method static Builder|Post whereSourceLink($value)
 * @method static Builder|Post whereSourceName($value)
 * @method static Builder|Post whereTitle($value)
 * @method static Builder|Post whereUpdatedAt($value)
 * @method static Builder|Post whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Post withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Post withoutTrashed()
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'user_id',
        'category_id',
        'excerpt',
        'is_published',
        'published_at',
        'popular',
        'main_slider',
        'content',
        'source_name',
        'source_link',
        'source_image',
        'partner_news',
    ];

    /**
     * Return parent category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getExcerpt()
    {
        return new HtmlString($this->excerpt);
    }

    public function isPopular()
    {
        return $this->popular;
    }

    public function isPublished()
    {
        return $this->is_published;
    }

    public function isExternal()
    {
        return $this->partner_news;
    }

    public function isShowInMainSlider()
    {
        return $this->main_slider;
    }

    public function getContent()
    {
        return new HtmlString($this->content);
    }

    public function getImageSrc()
    {
        return $this->isExternal() && !empty($this->source_image)
            ? $this->source_image
            : ($this->image ? Storage::url($this->image) : null);
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

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public function scopeExternal($query)
    {
        return $query->where('partner_news', true);
    }

    public function scopeCategory($query, $cid)
    {
        return $query->where('category_id', $cid);
    }

    public function scopeNowPublished($query)
    {
        return $query->published()->where('published_at', '<=', now());
    }

    public function scopePopular($query)
    {
        return $query->where('popular', true);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
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

    public function getStatusAttribute(bool $withClassName = false): object
    {
        $result = (object)[];

        if (isset($this->deleted_at)) {
            $result->value = 'deleted';
            $class = 'danger';
        } elseif ($this->is_published === false) {
            $result->value = 'draft';
            $class = 'warning';
        } else {
            $result->value = 'published';
            $class = 'success';
        }

        if ($withClassName) {
            $result->class = $class;
        }

        return $result;
    }
}
