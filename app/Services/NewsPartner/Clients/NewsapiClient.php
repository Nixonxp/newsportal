<?php

namespace App\Services\NewsPartner\Clients;

use App\Exceptions\ServiceException;
use App\Services\ApiClient\ClientAbstract;

class NewsapiClient extends ClientAbstract
{
    /**
     * @var string
     */
    protected string $base_url = 'https://newsapi.org/v2';

    /**
     * @return string
     * @throws ServiceException
     */
    protected function initBaseUrl(): string
    {
        if (($apiKey = config('news.newsapi.api_key')) !== null) {
            $this->headers['Accept'] = 'application/json';
            $this->headers['Authorization'] = "Bearer {$apiKey}";
            return $this->base_url;
        }

        throw new ServiceException('Missing config value for currency newsapi api_key');
    }
}
