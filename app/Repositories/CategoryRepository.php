<?php

namespace App\Repositories;

use App\Models\Category as Model;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\Filters\NewsPostFilters;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository extends CoreRepository implements CategoryRepositoryInterface
{
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
            'name',
            'slug',
            'description',
        ];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->orderBy('id', 'asc')
            ->paginate($perPage);

        return $result;
    }

    public function getCategoriesMenu()
    {
        $columns = [
            'id',
            'name',
            'slug',
        ];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->orderBy('id', 'asc')
            ->get();

        return $result;
    }
}
