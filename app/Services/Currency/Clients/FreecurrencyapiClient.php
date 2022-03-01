<?php

namespace App\Services\Currency\Clients;


use App\Exceptions\ServiceException;
use App\Services\ApiClient\ClientAbstract;

class FreecurrencyapiClient extends ClientAbstract
{
    /**
     * @var string
     */
    protected string $base_url = 'https://freecurrencyapi.net/api/v2/latest';

    /**
     * @return string
     * @throws ServiceException
     */
    protected function initBaseUrl(): string
    {
        if (($apiKey = config('currency.freecurrencyapi.api_key')) !== null) {
            $this->disableQM = true;
            return $this->base_url .= '?apikey=' . $apiKey;
        }

        throw new ServiceException('Missing config value for currency freecurrencyapi api_key');
    }
}
