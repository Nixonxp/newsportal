<?php

namespace Tests\Feature\Services\Currency\Clients;

use App\Exceptions\ServiceException;
use App\Services\Currency\Clients\ClientAbstract;
use App\Services\Currency\Interfaces\CurrencyClientInterface;
use ReflectionClass;
use Tests\TestCase;

class ClientAbstractTest extends TestCase
{
    protected $testClientAbstract;

    protected function setUp():void
    {
        parent::setUp();

        $this->testClientAbstract = new class extends ClientAbstract {

            protected function initBaseUrl(): string
            {
                return '';
            }

            public function queryWithParams(array $params, string $type = 'get'): CurrencyClientInterface
            {
                return $this;
            }
        };
    }

    public function testRequestCheckerCode200()
    {
        $result = $this->callProtectedMethod($this->testClientAbstract, 'checkRequestCode', [200]);
        $this->assertEquals(true, $result);
    }

    public function dataErrorCodes()
    {
        return [
            [404],
            [429],
            [500],
            [431]
        ];
    }

    /**
     * @dataProvider dataErrorCodes
     *
     * @return void
     */
    public function testRequestCheckerCodeErrorCodes($code)
    {
        $this->expectException(ServiceException::class);
        $this->callProtectedMethod($this->testClientAbstract, 'checkRequestCode', [$code]);
    }

    protected function callProtectedMethod($object, $method, array $args=array()) {
        $class = new ReflectionClass(get_class($object));
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }
}
