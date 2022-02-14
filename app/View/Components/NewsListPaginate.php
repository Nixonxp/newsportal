<?php

namespace App\View\Components;

use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\PostCachedRepository;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NewsListPaginate extends Component
{
    public $posts;
    public ?int $category = null;
    public int $perPage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(PostRepositoryInterface $postRepository, int $perPage, $category = null)
    {
        $this->perPage = $perPage;
        $this->category = $category;

        $this->posts = $postRepository->getPostWithPaginateCategory($this->perPage, $this->category);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string|void
     */
    public function render()
    {
        if ($this->posts->count() > 0) {
            return view('components.news-list-paginate');
        }
    }
}
