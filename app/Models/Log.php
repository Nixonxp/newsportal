<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log
 *
 * @property int $id
 * @property string|null $message
 * @property string|null $channel
 * @property int $level
 * @property string $level_name
 * @property int $unix_time
 * @property string|null $datetime
 * @property string|null $context
 * @property string|null $extra
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $slug
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereLevelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUnixTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Log extends Model
{
    protected $table = 'log';

    public function getSlugAttribute()
    {
        return strtolower($this->level_name);
    }
}
