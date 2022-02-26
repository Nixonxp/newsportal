<?php

namespace Tests\Feature\Services\ApiClient;

use App\Exceptions\ServiceException;
use App\Services\ApiClient\ClientAbstract;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Tests\TestCase;
use GuzzleHttp\Psr7\Response;
use Throwable;

class ClientAbstractTest extends TestCase
{
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
            $this->expectException(Throwable::class);
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

    public function dataQuery()
    {
        return [
            [
                [
                    'pathParams' => [
                        'param'
                    ],
                    'queryParams' => [
                        'paramq' => 'valueq'
                    ]
                ], '/param?paramq=valueq'
            ],
            [
                [
                    'pathParams' => [],
                    'queryParams' => [
                        'paramq' => 'valueq'
                    ]
                ],
                '?paramq=valueq'
            ],
            [
                [
                    'pathParams' => [
                        'param1',
                        'param2',
                    ],
                    'queryParams' => []
                ],
                '/param1/param2'
            ],
        ];
    }

    /**
     * @dataProvider dataQuery
     * @param $params
     * @param $expectResult
     * @return void
     * @throws ServiceException
     */
    public function testQueryWithParams($params, $expectResult)
    {
        $objClientAbstract = $this->getMockClientResponse(200, [], '{}');
        $objClientAbstract->queryWithParams($params['pathParams'], $params['queryParams']);
        $endpoint = $this->callProtectedMethod($objClientAbstract, 'getEndpoint');
        $this->assertEquals($expectResult, $endpoint);
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
        };
    }
}
