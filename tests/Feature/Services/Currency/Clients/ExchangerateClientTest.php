<?php

namespace Tests\Feature\Services\Currency\Clients;

use App\Exceptions\ServiceException;
use App\Services\Currency\Clients\ExchangerateClient;
use App\Services\Currency\Clients\FreecurrencyapiClient;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use ReflectionClass;

class ExchangerateClientTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientObj = app(ExchangerateClient::class);
    }

    public function testClientInitial()
    {
        $baseUrl = $this->callProtectedMethod($this->clientObj, 'getBaseUrl');
        $this->assertEquals($baseUrl, 'https://v6.exchangerate-api.com/v6/'
            . config('currency.exchangerate.api_key'));
    }

    public function dataQuery()
    {
        return [
            [
                ['param' => 'value'], '/param/value'
            ],
            [
                [
                    'param' => 'value',
                    'param2' => 'value2',
                ],
                '/param/value/param2/value2'
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
