<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function getAllWithPaginate(): Collection|LengthAwarePaginator;

    public function getEdit($id);

    public function getCategoriesMenu(): Collection;
}
