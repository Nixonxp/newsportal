<?php

namespace App\Repositories\Interfaces;

interface NewsPostRepositoryInterface
{
    public function getAllWithPaginate();

    public function getEdit($id);
}
