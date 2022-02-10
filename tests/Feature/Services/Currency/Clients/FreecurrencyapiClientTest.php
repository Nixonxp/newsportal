<?php

namespace Tests\Feature\Services\Currency\Clients;

use App\Exceptions\ServiceException;
use App\Services\Currency\Clients\FreecurrencyapiClient;
use Tests\TestCase;
use ReflectionClass;

class FreecurrencyapiClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->clientObj = app(FreecurrencyapiClient::class);
    }

    public function testClientInitial()
    {
        $baseUrl = $this->callProtectedMethod($this->clientObj, 'getBaseUrl');
        $this->assertEquals($baseUrl, 'https://freecurrencyapi.net/api/v2/latest?apikey='
            . config('currency.freecurrencyapi.api_key'));
    }

    public function dataQuery()
    {
        return [
            [
                ['param' => 'value'], '&param=value'
            ],
            [
                [
                    'param' => 'value',
                    'param2' => 'value2',
                ],
                '&param=value&param2=value2'
            ],
        ];
    }

    /**
     * @dataProvider dataQuery
     * @param $params
     * @param $expectResult
     * @return void
     */
    public function testQueryBuilder($params, $expectResult)
    {
        $this->clientObj->queryWithParams($params);
        $endpoint = $this->callProtectedMethod($this->clientObj, 'getEndpoint');
        $this->assertEquals($expectResult, $endpoint);
    }

    public function testQueryFail()
    {
        $this->expectException(ServiceException::class);
        $this->clientObj->queryWithParams([]);
    }

    protected function callProtectedMethod($object, $method, array $args=array()) {
        $class = new ReflectionClass(get_class($object));
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }
}
