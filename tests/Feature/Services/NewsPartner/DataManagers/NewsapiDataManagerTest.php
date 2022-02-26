<?php

namespace Tests\Feature\Services\NewsPartner\DataManagers;

use App\Exceptions\ServiceException;
use App\Services\Currency\Clients\ExchangerateClient;
use App\Services\Currency\DataManagers\ExchangerateDataManager;
use App\Services\Currency\Interfaces\CurrencyClientInterface;
use App\Services\NewsPartner\Clients\NewsapiClient;
use App\Services\NewsPartner\DataManagers\NewsapiDataManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class NewsapiDataManagerTest extends TestCase
{
    use DatabaseTransactions;

    protected function getResponseSuccessData()
    {
        return [
            "status" => "ok",
            "totalResults" => 2,
            "articles" => [
                [
                    "source" => [
                        "id" => "cnn",
                        "name" => "CNN"
                    ],
                    "author" => "Rob",
                    "title" => "news1",
                    "description" => "description1",
                    "url" => "https://www.cnn.com/news1",
                    "urlToImage" => "https://cdn.cnn.com/news1.jpg",
                    "publishedAt" => "2022-02-24T12:34:00Z",
                    "content" => "content1"
                ],
                [
                    "source" => [
                        "id" => "rbc",
                        "name" => "RBC"
                    ],
                    "author" => "Alex",
                    "title" => "news2",
                    "description" => "description2",
                    "url" => "https://www.rbc.com/news2",
                    "urlToImage" => "https://cdn.rbc.com/news2.jpg",
                    "publishedAt" => "2022-02-24T12:34:00Z",
                    "content" => null
                ]
            ]
        ];
    }

    /**
     * @return void
     * @throws ServiceException
     */
	public function testGetLatestPopularNews()
	{
        $data = $this->getResponseSuccessData();
        $this->getResponseClientWithStub($data);

        $execResult = (new NewsapiDataManager())->getLatestPopularNews();

        foreach ($data['articles'] as $key => $articleItem) {
            $dto = $execResult->get($key);

            $this->assertEquals($dto->title, $articleItem['title']);
            $this->assertEquals($dto->excerpt, $articleItem['description']);
            $this->assertEquals($dto->content, ($articleItem['content'] ?? $articleItem['description']));
            $this->assertEquals($dto->source_name, ($articleItem['source']['name'] ?? null));
            $this->assertEquals($dto->source_link, $articleItem['url']);
            $this->assertEquals($dto->source_image, $articleItem['urlToImage']);
            $this->assertTrue($dto->partner_news);
        }
	}

    public function failExceptionDataProvider()
    {
        return [
            [
                [
                    "status" => "fail",
                    "totalResults" => 0,
                    "articles" => [],
                ]
            ],
            [
                [
                    "status" => "ok",
                    "totalResults" => 0,
                ]
            ]
        ];
    }

    /**
     * @dataProvider failExceptionDataProvider
     * @return void
     * @throws ServiceException
     */
    public function testResponseFail($data)
    {
        $this->getResponseClientWithStub($data);

        $this->expectException(ServiceException::class);
        $execResult = (new NewsapiDataManager())->getLatestPopularNews();
    }

    private function getResponseClientWithStub($data)
    {
        app()->bind(NewsapiClient::class, function () use ($data) {
            $stubClient = $this->createMock(NewsapiClient::class);
            $stubClient->method('exec')
                ->willReturn($data);

            $stubClient->method('queryWithParams')
                ->willReturn($stubClient);

            return $stubClient;
        });
    }
}
