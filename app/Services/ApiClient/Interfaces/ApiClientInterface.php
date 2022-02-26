<?php

namespace App\Services\ApiClient\Interfaces;

interface ApiClientInterface
{
    public function exec();

    public function setClientParams(array $params);

    public function queryWithParams(array $pathParams, array $queryParams, string $type): ApiClientInterface;
}
