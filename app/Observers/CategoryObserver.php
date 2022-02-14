<?php

namespace App\Observers;

use App\Models\Category;
use App\Repositories\CategoryCachedRepository;
use Illuminate\Support\Facades\Cache;
use Str;

class CategoryObserver
{
    public function creating(Category $category)
    {
        $this->setSlug($category);
    }

    private function setSlug(Category $category): void
    {
        if (!isset($category->slug)) {
            $category->slug = Str::slug($category->name);
        }
    }

    public function created(Category $category)
    {
        $this->forgetOldDataPostRepository();
    }

    public function updated(Category $category)
    {
        $this->forgetOldDataPostRepository();
    }

    public function deleted(Category $category)
    {
        $this->forgetOldDataPostRepository();
    }

    public function forceDeleted(Category $category)
    {
        $this->forgetOldDataPostRepository();
    }

    private function forgetOldDataPostRepository(): void
    {
        $repositoryMethods = array_map(function($value) {
            return CategoryCachedRepository::class . $value;
        },
            get_class_methods(CategoryCachedRepository::class));

        Cache::tags($repositoryMethods)->flush();
    }
}
