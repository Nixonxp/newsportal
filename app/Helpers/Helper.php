<?php

namespace App\Helpers;

use Request;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;

class Helper
{
    public static function getCurrentDate()
    {
        return Str::ucfirst(Date::parse(now())->format('F d, Y'));
    }

    public static function classActivePath($path)
    {
        return Request::is($path) ? ' active' : '';
    }

    public static function classActiveSegment($segment, $value)
    {
        if(!is_array($value)) {
            return Request::segment($segment) == $value ? ' active' : '';
        }
        foreach ($value as $v) {
            if(Request::segment($segment) == $v) return ' active';
        }
        return '';
    }
}
