<?php

namespace App\Services\Currency\Clients;

use App\Exceptions\ServiceException;
use App\Services\Currency\Interfaces\CurrencyClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

abstract class ClientAbstract implements CurrencyClientInterface
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string
     */
    protected string $endpoint = '';

    /**
     * @var array
     */
    private array $clientParams = [];

    public function __construct()
    {
        $this->base_url = $this->initBaseUrl();
        $this->client = $this->initClient();
    }

    /**
     * @return Client
     */
    protected function initClient(): Client
    {
        return new Client($this->clientParams);
    }

    /**
     * @param array $params
     * @return void
     */
    public function setClientParams(array $params):void
    {
        $this->clientParams = $params;
    }

    /**
     * @return ResponseInterface
     * @throws GuzzleException
     * @throws ServiceException
     */
    protected function doRequest(): ResponseInterface
    {
        $this->type = mb_strtoupper($this->type);
        $result = $this->client->request($this->getType(), $this->getBaseUrl() . $this->getEndpoint());

        $code   = $result->getStatusCode();
        $reason = $result->getReasonPhrase();

        $this->checkRequestCode($code, $reason);

        return $result;
    }

    /**
     * @param int $code
     * @param $reason
     * @return bool
     * @throws ServiceException
     */
    protected function checkRequestCode(int $code, $reason = null): bool
    {
        switch ($code) {
            case 200:
            case 201:
                return true;
            case 429:
                throw new ServiceException('Rate-limit exception');
            default:
                throw new ServiceException('Exchange currency service error' . $code . ' ' . $reason);
        }
    }

    /**
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return $this->base_url;
    }

    /**
     * @return mixed
     * @throws GuzzleException
     * @throws ServiceException|\JsonException
     */
    public function exec(): mixed
    {
        $result = json_decode($this->doRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->setEndpoint('');
        return $result;
    }

    /**
     * @return string
     */
    protected function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    protected function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    /**
     * @param string|null $endpoint
     */
    protected function setEndpoint(?string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }
}
