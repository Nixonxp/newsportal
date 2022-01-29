<?php

namespace App\Observers;

use App\Models\Category;
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
}
