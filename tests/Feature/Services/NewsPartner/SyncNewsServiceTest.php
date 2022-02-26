<?php

namespace Tests\Feature\Services\NewsPartner;

use App;
use App\Exceptions\ServiceException;
use App\Models\Category;
use App\Services\NewsPartner\DataManagers\NewsapiDataManager;
use App\Services\NewsPartner\SyncNewsService;
use App\Services\NewsPost\NewsPostService;
use Config;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SyncNewsServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testEmptyConfigCategoriesFail()
    {
        Config::set('news.category_list', []);
        $this->expectException(ServiceException::class);
        $testObj = (new SyncNewsService(new NewsapiDataManager, App::make(NewsPostService::class)));
        $testObj->getNewsByAllCategories(10)->sync();
    }

	public function testGetNewsByAllCategories()
	{
        Config::set('news.category_list', ['politics', 'economy']);
        $dtoData = $this->getDataForDto();

        $mockDataManager = $this->createMock(NewsapiDataManager::class);
        $mockDataManager->method("getLatestPopularNews")->will(
            $this->onConsecutiveCalls(collect([$dtoData->get(0)]), collect([$dtoData->get(1)]))
        );

        $testObj = (new SyncNewsService($mockDataManager, App::make(NewsPostService::class)));
        $testObj->getNewsByAllCategories(10);

        $this->assertEquals($dtoData, $this->getProtectedProperty($testObj, 'newsCollections'));
	}

    public function testRun()
    {
        Config::set('news.category_list', ['politics']);
        $dtoData = $this->getDataForDto();
        $mockDataManager = $this->createMock(NewsapiDataManager::class);
        $mockDataManager->method("getLatestPopularNews")->willReturn($dtoData);

        $testObj = (new SyncNewsService($mockDataManager, App::make(NewsPostService::class)));
        $result = $testObj->getNewsByAllCategories(10)->sync();

        $this->assertCount(2, $result['created']['id']);
    }

    public function testRunCreateAndFound()
    {
        Config::set('news.category_list', ['politics']);
        $dtoData = $this->getDataForDto();
        $mockDataManager = $this->createMock(NewsapiDataManager::class);
        $mockDataManager->method("getLatestPopularNews")->willReturn($dtoData);

        $newsPostServiceObj = App::make(NewsPostService::class);

        $dtoItem = $dtoData->get(0);
        $dtoItem->category_id = Category::first()->id;
        $newsPostServiceObj->findFromTitleOrCreate($dtoItem);

        $testObj = (new SyncNewsService($mockDataManager, App::make(NewsPostService::class)));
        $result = $testObj->getNewsByAllCategories(10)->sync();

        $this->assertCount(1, $result['created']['id']);
        $this->assertCount(1, $result['found']['id']);
    }

    protected function getDataForDto()
    {
        $arrayData = [
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
        ];

        $objDataManager = new NewsapiDataManager;
        return $this->callProtectedMethod($objDataManager, 'getNewsDto', [$arrayData]);
    }
}
