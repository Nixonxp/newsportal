<?php

namespace App\Repositories;

use App\Models\Post as Model;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\NewsPostRepositoryInterface;
use App\Services\Filters\NewsPostFilters;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsPostRepository extends CoreRepository implements NewsPostRepositoryInterface
{
    private $defaultColumns = [
        'id',
        'title',
        'slug',
        'image',
        'excerpt',
        'published_at',
        'category_id'
    ];

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getAllWithPaginate(int $perPage = 100): Collection|LengthAwarePaginator
    {
        $columns = [
            'id',
            'title',
            'slug',
            'image',
            'excerpt',
            'is_published',
            'created_at',
            'updated_at',
            'published_at',
            'user_id',
            'category_id'
        ];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->orderBy('id', 'asc')
            ->with('category:name')
            ->paginate($perPage);

        return $result;
    }

    public function getNewsPostsWithFilterPaginate($request, $perPage = null)
    {
        $filters = (new NewsPostFilters($request));
        return $this
            ->startConditions()
            ->filter($filters)
            ->paginate($perPage);
    }

    public function getLastNewsByCategoryId(int $id, $limit = 100)
    {
        return $this
            ->startConditions()
            ->select($this->defaultColumns)
            ->orderBy('id', 'desc')
            ->with('category')
            ->category($id)
            ->limit($limit)
            ->get();
    }

    public function getLastNewsWithPaginateByCategoryId(int $id, $perPage = 100)
    {
        return $this
            ->startConditions()
            ->select($this->defaultColumns)
            ->orderBy('id', 'desc')
            ->with('category')
            ->category($id)
            ->paginate($perPage);
    }
}
