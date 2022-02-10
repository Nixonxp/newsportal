<?php

namespace App\Dto\Currency;

use Carbon\Carbon;

class CurrencyDto
{
    public ?string $title;
    public string $code;
    public float $rate;
    public bool $base_currency;
    public bool $crypt;
    public Carbon $date;
}
