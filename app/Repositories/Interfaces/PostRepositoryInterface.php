<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

interface PostRepositoryInterface
{
    public function getAllWithPaginate(): Collection|LengthAwarePaginator;

    public function getEdit($id);

    public function getPostWithPaginateCategory(int $perPage = 100, int $category = null): Collection|LengthAwarePaginator;

    public function getNewsPostsWithFilterPaginate($request, ?int $perPage = null): Collection|LengthAwarePaginator;

    public function getLastNewsByCategoryId(int $id, int $limit = 100): Collection;

    public function getLastNewsWithPaginateByCategoryId(int $id, int $perPage = 100): Collection|LengthAwarePaginator;

    public function getLastMainSliderPosts(int $category = null, int $limit = 10): Collection;

    public function getLastPublishedNews(int $category = null, int $limit = 20): Collection;

    public function getLastPopularPublishedNews(int $category = null, int $limit = 20): Collection;

    public function getPublishedNewsOverPeriod(Carbon $period, int $limit = 20, int $category = null, $getWithBuilder = false): Collection|Builder;

    public function getPopularRandomNewsOverPeriod(Carbon $period, int $limit = 20, int $category = null): Collection;

    public function search(string $query = ''): \Illuminate\Support\Collection;
}
