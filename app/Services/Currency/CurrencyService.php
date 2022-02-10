<?php

namespace App\Services\Currency;

class CurrencyService
{
    /**
     * Convert from rates
     *
     * @param float $targetCurrencyRate
     * @return float
     */
    public static function convertFromTargetRateToOrigin(float $targetCurrencyRate): float
    {
        return round(1 / $targetCurrencyRate, 2);
    }
}
