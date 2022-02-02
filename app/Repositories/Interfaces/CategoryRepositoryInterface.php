<?php

namespace App\Repositories\Interfaces;

interface CategoryRepositoryInterface
{
    public function getAllWithPaginate();

    public function getEdit($id);
}
