<?php

namespace App\Services\Currency\Clients;


use App\Exceptions\ServiceException;
use App\Services\Currency\Interfaces\CurrencyClientInterface;

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

    /**
     * @param array $params
     * @param string $type
     * @return CurrencyClientInterface
     * @throws ServiceException
     */
    public function queryWithParams(array $params, string $type = 'get'): CurrencyClientInterface
    {
        if (empty($params)) {
            throw new ServiceException('Missing query params');
        }
        $this->type = $type;

        foreach ($params as $key => $endpoint) {
            $this->endpoint .= "/" . $key . "/" . $endpoint;
        }

        return $this;
    }
}
