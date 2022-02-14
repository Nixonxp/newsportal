<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class PostCachedRepository implements PostRepositoryInterface
{
    private PostRepository $postRepository;
    private int $defaultCacheTime = 3600;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;

        $this->defaultCacheTime = config('cache.post_repository_cache_time') ?? $this->defaultCacheTime;
    }

    public function getAllWithPaginate(int $perPage = 100): Collection|LengthAwarePaginator
    {
        $value = Cache::tags(self::class . 'getAllWithPaginate')
            ->remember(serialize([__METHOD__, self::class,
                'arguments' => [$perPage],
                'page' => [request()?->query('page')]
            ]),
        $this->defaultCacheTime,
            function () use ($perPage) {
                return $this->postRepository->getAllWithPaginate($perPage);
            }
        );

        return $value;
    }

    public function getEdit($id)
    {
        return $this->postRepository->getEdit($id);
    }

    public function getPostWithPaginateCategory(int $perPage = 100, int $category = null): Collection|LengthAwarePaginator
    {
        $value = Cache::tags(self::class . 'getPostWithPaginateCategory')
            ->remember(serialize([__METHOD__, self::class,
                    'arguments' => [$perPage, $category],
                    'page' => [request()?->query('page')]
                ]),
                $this->defaultCacheTime,
                function () use ($perPage, $category) {
                    return $this->postRepository->getPostWithPaginateCategory($perPage, $category);
                });

        return $value;
    }

    public function getNewsPostsWithFilterPaginate($request, ?int $perPage = null): Collection|LengthAwarePaginator
    {
        return $this->postRepository->getNewsPostsWithFilterPaginate($request, $perPage);
    }

    public function getLastNewsByCategoryId(int $id, int $limit = 100): Collection
    {
        $value = Cache::tags(self::class . 'getLastNewsByCategoryId')
            ->remember(serialize([__METHOD__, self::class,
                'arguments' => [$id, $limit]
            ]),
            $this->defaultCacheTime,
            function () use ($id, $limit) {
                return $this->postRepository->getLastNewsByCategoryId($id, $limit);
            });

        return $value;
    }

    public function getLastNewsWithPaginateByCategoryId(int $id, int $perPage = 100): Collection|LengthAwarePaginator
    {
        $value = Cache::tags(self::class . 'getLastNewsWithPaginateByCategoryId')
            ->remember(serialize([__METHOD__, self::class,
                'arguments' => [$id, $perPage],
                'page' => [request()?->query('page')]
            ]),
            $this->defaultCacheTime,
            function () use ($id, $perPage) {
                return $this->postRepository->getLastNewsWithPaginateByCategoryId($id, $perPage);
            });

        return $value;
    }

    public function getLastMainSliderPosts(int $category = null, int $limit = 10): Collection
    {
        $value = Cache::tags(self::class . 'getLastMainSliderPosts')
            ->remember(serialize([__METHOD__, self::class,
                'arguments' => [$category, $limit]
            ]),
            $this->defaultCacheTime,
            function () use ($category, $limit) {
                return $this->postRepository->getLastMainSliderPosts($category, $limit);
            });

        return $value;
    }

    public function getLastPublishedNews(int $category = null, int $limit = 20): Collection
    {
        $value = Cache::tags(self::class . 'getLastPublishedNews')
            ->remember(serialize([__METHOD__, self::class,
                'arguments' => [$category, $limit]
            ]),
            $this->defaultCacheTime,
            function () use ($category, $limit) {
                return $this->postRepository->getLastPublishedNews($category, $limit);
            });

        return $value;
    }

    public function getLastPopularPublishedNews(int $category = null, int $limit = 20): Collection
    {
        $value = Cache::tags(self::class . 'getLastPopularPublishedNews')
            ->remember(serialize([__METHOD__, self::class,
                'arguments' => [$category, $limit]
            ]),
            $this->defaultCacheTime,
            function () use ($category, $limit) {
                return $this->postRepository->getLastPopularPublishedNews($category, $limit);
            });

        return $value;
    }

    public function getPublishedNewsOverPeriod(Carbon $period, int $limit = 20, int $category = null, $getWithBuilder = false): Collection|Builder
    {
        if (!$getWithBuilder) {
            $value = Cache::tags(self::class . 'getPublishedNewsOverPeriod')
                ->remember(serialize([__METHOD__, self::class,
                    'arguments' => [$period->toDateString(), $limit, $category, $getWithBuilder]
                ]),
                    $this->defaultCacheTime,
                    function () use ($period, $limit, $category, $getWithBuilder) {
                        return $this->postRepository->getPublishedNewsOverPeriod($period, $limit, $category, $getWithBuilder);
                    });

            return $value;
        }

        return $this->postRepository->getPublishedNewsOverPeriod($period, $limit, $category, $getWithBuilder);
    }

    public function getPopularRandomNewsOverPeriod(Carbon $period, int $limit = 20, int $category = null): Collection
    {
        $value = Cache::tags(self::class . 'getPopularRandomNewsOverPeriod')
            ->remember(serialize([__METHOD__, self::class,
                'arguments' => [$period->toDateString(), $limit, $category]
            ]),
            $this->defaultCacheTime,
            function () use ($period, $limit, $category) {
                return $this->postRepository->getPopularRandomNewsOverPeriod($period, $limit, $category);
            });

        return $value;
    }
}
