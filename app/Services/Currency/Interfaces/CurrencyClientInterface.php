<?php

namespace App\Services\Currency\Interfaces;

interface CurrencyClientInterface
{
    public function exec();

    public function setClientParams(array $params);

    public function queryWithParams(array $params, string $type): CurrencyClientInterface;
}
