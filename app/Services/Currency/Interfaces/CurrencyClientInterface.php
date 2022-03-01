<?php

namespace App\Services\Currency\Interfaces;

use App\Services\ApiClient\Interfaces\ApiClientInterface;

interface CurrencyClientInterface
{
    public function exec();

    public function setClientParams(array $params);

    public function queryWithParams(array $params, string $type): ApiClientInterface;
}
