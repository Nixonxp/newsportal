<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $title
 * @property string $code
 * @property float $rate
 * @property bool $base_currency
 * @property bool $crypt
 * @property int|null $trend
 * @property string $date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property float|null $trend_diff
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereBaseCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCrypt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereTrend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereTrendDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Currency extends Model
{
    use HasFactory;

    /**
     * trend attribute constants
     */
    public const UP = 1;
    public const DOWN = 0;
    public const EQ = null;

    protected $fillable = [
        'title',
        'code',
        'rate',
        'base_currency',
        'crypt',
        'date',
        'trend',
        'trend_diff'
    ];
}
