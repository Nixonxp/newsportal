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
