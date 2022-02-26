<?php

namespace App\Services\NewsPartner;

use App\Exceptions\ServiceException;
use App\Models\Category;
use App\Services\NewsPartner\Interfaces\NewsApiDataManagerInterface;
use App\Services\NewsPost\NewsPostService;
use Illuminate\Support\Collection;

class SyncNewsService
{
    private NewsApiDataManagerInterface $apiManager;
    private NewsPostService $postService;
    private Collection $newsCollections;

    public function __construct(NewsApiDataManagerInterface $apiManager, NewsPostService $postService)
    {
        $this->apiManager = $apiManager;
        $this->postService = $postService;
        $this->newsCollections = collect([]);
    }

    /**
     * @param int $limit
     * @return $this
     * @throws ServiceException
     */
    public function getNewsByAllCategories(int $limit): self
    {
        $arCategories = $this->getCategoryList();

        if ($arCategories->isEmpty()) {
            throw new ServiceException(__('error.config_categories_error'));
        }

        $arCategories->map(
            fn(Category $category) => $this->apiManager
                ->getLatestPopularNews($category->slug, $limit)
                ->map(function($dto) use ($category) {
                    $dto->category_id = $category->id;
                    $this->newsCollections->push($dto);
                })
        );

        return $this;
    }

    /**
     * @return array
     */
    public function sync(): array
    {
        $result = [];

        $this->newsCollections->map(function($dto) use (&$result) {
            $resultAction = $this->postService->findFromTitleOrCreate($dto);
            $result[$resultAction->status]['id'][] = $resultAction->id;
        });

        return $result;
    }

    /**
     * @return Collection
     */
    private function getCategoryList(): Collection
    {
        $arCategories = config('news.category_list');

        return Category::select('id', 'slug')
            ->whereIn('slug', $arCategories)
            ->get();
    }
}
