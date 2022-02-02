<?php

namespace App\Http\ViewComposers;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\View\View;

class CategoriesComposer
{
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function compose(View $view): void
    {
        $view->withCategories($this->categoryRepository->getCategoriesMenu());
    }
}
