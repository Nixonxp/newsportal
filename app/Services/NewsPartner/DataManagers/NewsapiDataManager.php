<?php

namespace App\Services\NewsPartner\DataManagers;

use App;
use App\Dto\NewsPost\NewsPostDto;
use App\Exceptions\ServiceException;
use App\Services\ApiClient\Interfaces\ApiClientInterface;
use App\Services\NewsPartner\Clients\NewsapiClient;
use App\Services\NewsPartner\Interfaces\NewsApiDataManagerInterface;
use Illuminate\Support\Collection;
use Str;

class NewsapiDataManager implements NewsApiDataManagerInterface
{
    private ApiClientInterface $client;
    private int $maxCount;
    private string $newsLang;

    public function __construct()
    {
        $this->client = App::make(NewsapiClient::class);
        $this->maxCount = config('news.max_sync_count') ?? 50;
        $this->newsLang = config('news.news_lang') ?? config('app.locale') ?? 'ru';
    }

    /**
     * @param string|null $category
     * @param int|null $count
     * @return Collection
     * @throws ServiceException
     */
    public function getLatestPopularNews(?string $category = null, ?int $count = null): Collection
    {
        $type = 'get';

        $pathParams = [
            'top-headlines'
        ];

        $queryParams['country'] = $this->newsLang;
        $queryParams['pageSize'] = $count ?? $this->maxCount;

        if (!empty($category)) {
            $queryParams['category'] = $category;
        }

        $response = $this->client->queryWithParams($pathParams, $queryParams, $type)->exec();

        if ($response['status'] !== 'ok' || !isset($response['articles'])) {
            throw new ServiceException(
                __('errors.response_api_error',
                    ['info' => self::class . json_encode($response)]
                )
            );
        }

        return self::getNewsDto($response['articles']);
    }

    /**
     * @param array $arArticlesResponse
     * @return Collection
     */
    protected static function getNewsDto(array $arArticlesResponse): Collection
    {
        $result = collect($arArticlesResponse)->map(function ($post) {
            $currDTO = new NewsPostDto();
            $currDTO->title = Str::limit($post['title'], 250);
            $currDTO->excerpt = $post['description'] ?? '';
            $currDTO->content = $post['content'] ?? $currDTO->excerpt;
            $currDTO->source_name = $post['source']['name'] ?? null;
            $currDTO->source_link = $post['url'];
            $currDTO->source_image = $post['urlToImage'];
            $currDTO->partner_news = true;
            $currDTO->is_published = false;

            return $currDTO;
        });

        return $result;
    }
}
