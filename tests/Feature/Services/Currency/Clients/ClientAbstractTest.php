<?php

namespace Tests\Feature\Services\Currency\Clients;

use App\Exceptions\ServiceException;
use App\Services\Currency\Clients\ClientAbstract;
use App\Services\Currency\Interfaces\CurrencyClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use ReflectionClass;
use Tests\TestCase;

class ClientAbstractTest extends TestCase
{
    use DatabaseTransactions;

    public function dataErrorCodes()
    {
        return [
            [200, false],
            [201, false],
            [404, true],
            [429, true],
            [500, true],
            [431, true]
        ];
    }

    /**
     * @dataProvider dataErrorCodes
     *
     * @return void
     */
    public function testRequestCheckerCodeErrorCodes($code, $expectException)
    {

        $objClientAbstract = $this->getMockClientResponse($code);

        if ($expectException) {
            $this->expectException(ServiceException::class);
        }

        $type = 'get';
        $this->callProtectedMethod($objClientAbstract, 'setType', [$type]);
        $result = $this->callProtectedMethod($objClientAbstract, 'doRequest');

        $this->assertEquals($code, $result->getStatusCode());
    }

    public function testExec()
    {
        $objClientAbstract = $this->getMockClientResponse(200, [], '{"test":"test", "test2":"test2"}');

        $type = 'get';
        $this->callProtectedMethod($objClientAbstract, 'setType', [$type]);
        $result = $this->callProtectedMethod($objClientAbstract, 'exec');

        $this->assertEquals($result, [
                "test" => "test",
                "test2" => "test2"
            ]
        );
    }

    protected function callProtectedMethod($object, $method, array $args = array())
    {
        $class = new ReflectionClass(get_class($object));
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }

    private function getMockClientResponse($status, $header = [], $body = '')
    {
        $mock = new MockHandler([
            new Response($status, $header, $body),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $this->app->bind(ClientInterface::class, function () use ($handlerStack) {
            return new Client(['handler' => $handlerStack]);
        });

        return $this->getTestClientAbstract();
    }

    private function getTestClientAbstract()
    {

        return new class extends ClientAbstract {

            public function setType($type): void
            {
                $this->type = $type;
            }

            protected function initBaseUrl(): string
            {
                return 'http://localhost';
            }

            public function queryWithParams(array $params, string $type = 'get'): CurrencyClientInterface
            {
                return $this;
            }
        };
    }
}
