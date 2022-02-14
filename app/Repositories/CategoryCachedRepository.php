<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryCachedRepository implements CategoryRepositoryInterface
{
    private CategoryRepository $categoryRepository;
    private int $defaultCacheTime = 36000;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->defaultCacheTime = config('cache.category_repository_cache_time') ?? $this->defaultCacheTime;
    }

    public function getEdit($id)
    {
        return $this->categoryRepository->getEdit($id);
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
                    return $this->categoryRepository->getAllWithPaginate($perPage);
                }
            );

        return $value;
    }

    public function getCategoriesMenu(): Collection
    {
        $value = Cache::tags(self::class . 'getCategoriesMenu')
            ->remember(serialize([__METHOD__, self::class
            ]),
                $this->defaultCacheTime,
                function () {
                    return $this->categoryRepository->getCategoriesMenu();
                }
            );

        return $value;
    }
}
