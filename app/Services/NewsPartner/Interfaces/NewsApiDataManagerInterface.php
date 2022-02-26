<?php

namespace App\Services\NewsPartner\Interfaces;

use Illuminate\Support\Collection;

interface NewsApiDataManagerInterface
{
    public function getLatestPopularNews(?string $category, int $count): Collection;
}
