<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
