<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';

    public function getSlugAttribute()
    {
        return strtolower($this->level_name);
    }
}
