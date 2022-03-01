<?php

namespace App\Services\Currency\Clients;


use App\Exceptions\ServiceException;
use App\Services\ApiClient\ClientAbstract;

class ExchangerateClient extends ClientAbstract
{
    /**
     * @var string
     */
    protected string $base_url = 'https://v6.exchangerate-api.com/v6';

    /**
     * @return string
     * @throws ServiceException
     */
    protected function initBaseUrl(): string
    {
        if (($apiKey = config('currency.exchangerate.api_key')) !== null) {
            return $this->base_url .= '/' . $apiKey;
        }

        throw new ServiceException('Missing config value for currency exchangerate api_key');
    }
}
