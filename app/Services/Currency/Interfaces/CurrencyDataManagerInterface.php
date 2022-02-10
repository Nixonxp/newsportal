<?php

namespace App\Services\Currency\Interfaces;

interface CurrencyDataManagerInterface
{
    public function getCurrencyDto($baseCurrency);
}
