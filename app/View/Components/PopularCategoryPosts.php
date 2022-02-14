<?php

namespace App\View\Components;

use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class PopularCategoryPosts extends Component
{
    public Collection $posts;
    public ?int $categoryId;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(PostRepositoryInterface $postRepository, int $categoryId = null)
    {
        $this->categoryId = $categoryId;
        $this->posts = $postRepository->getLastPublishedNews($this->categoryId, 5);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.popular-category-posts');
    }
}
