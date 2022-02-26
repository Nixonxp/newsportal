<?php

use Illuminate\Support\Str;
use Jenssegers\Date\Date;

if(!function_exists('implode_r'))
{
	function implode_r_key($glue, array $arr)
	{
		$ret = '';
        $i = 0;

		foreach($arr as $key => $piece)
		{
            $glue_c = $i++ === 0 ? '' : $glue;

			if(is_array($piece))
				$ret .= ' ' . $key . ' - ' . $glue_c . implode_r_key($glue, $piece);
			else
				$ret .= $glue_c . $piece;
		}

		return $ret;
	}
}

if(!function_exists('getMiddleFormatDateAttribute'))
{
	function getMiddleFormatDateAttribute($date)
	{
        return Str::ucfirst(Date::parse($date)->format('F d, Y'));
	}
}

if(!function_exists('arrayNullFilters'))
{
	function arrayNullFilters(array $array) :array
	{
        $filter = function($var) {
            return ($var !== null);
        };

        return array_filter($array, $filter);
	}
}

if(!function_exists('getCurrentDate'))
{
    function getCurrentDate()
    {
        return Str::ucfirst(Date::parse(now())->format('F d, Y'));
    }
}

if(!function_exists('classActivePath'))
{
    function classActivePath($path)
    {
        return Request::is($path) ? ' active' : '';
    }
}

if(!function_exists('classActiveSegment'))
{
    function classActiveSegment($segment, $value)
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
