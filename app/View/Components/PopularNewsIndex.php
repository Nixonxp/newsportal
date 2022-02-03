<?php

namespace App\View\Components;

use App\Models\Post;
use App\Repositories\Interfaces\NewsPostRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class PopularNewsIndex extends Component
{
    public Collection $popularPosts;
    public Post $popularPostsMain;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(NewsPostRepositoryInterface $newsPostRepository)
    {

        $this->popularPosts = $newsPostRepository->getLastPopularPublishedNews(null, 3);

        if ($this->popularPosts->count() > 0) {
            $this->popularPostsMain = $this->popularPosts->pull(0);
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string|void
     */
    public function render()
    {
        if ($this->popularPosts->isNotEmpty()) {
            return view('components.popular-news-index');
        }
    }
}
