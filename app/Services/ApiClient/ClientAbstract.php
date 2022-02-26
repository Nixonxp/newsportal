<?php

namespace App\Services\ApiClient;

use App\Exceptions\ServiceException;
use App\Services\ApiClient\Interfaces\ApiClientInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Http\Message\ResponseInterface;

abstract class ClientAbstract implements ApiClientInterface
{
    /**
     * @var ClientInterface
     */
    protected ClientInterface $client;

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

    /**
     * @var array
     */
    protected array $headers = [];

    public function __construct()
    {
        $this->base_url = $this->initBaseUrl();
        $this->client = $this->initClient();
    }

    /**
     * @return ClientInterface
     * @throws BindingResolutionException
     */
    protected function initClient(): ClientInterface
    {
        return app()->make(ClientInterface::class, ['config' => $this->clientParams]);
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
     */
    protected function doRequest(): ResponseInterface
    {
        $this->type = mb_strtoupper($this->type);
        $result = $this->client->request(
            $this->getType(),
            $this->getBaseUrl() . $this->getEndpoint(),
            [
                'headers' => $this->headers
            ]
        );
        return $result;
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

    /**
     * @param array $pathParams
     * @param array $queryParams
     * @param string $type
     * @return ApiClientInterface
     * @throws ServiceException
     */
    public function queryWithParams(array $pathParams = [], array $queryParams = [], string $type = 'get'): ApiClientInterface
    {
        if (empty($pathParams) && empty($queryParams)) {
            throw new ServiceException('Missing query params');
        }

        $this->type = $type;

        if (!empty($pathParams)) {
            foreach ($pathParams as $endpoint) {
                $this->endpoint .= "/" . $endpoint;
            }
        }

        if (!empty($queryParams)) {
            $this->endpoint .= '?';
            $i = 0;
            foreach ($queryParams as $paramKey => $endpoint) {
                $amp = $i++ !== 0 ? '&' : '';
                $this->endpoint .= $amp . $paramKey . "=" . $endpoint;
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    abstract protected function initBaseUrl(): string;
}
