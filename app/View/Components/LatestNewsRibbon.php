<?php

namespace App\View\Components;

use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class LatestNewsRibbon extends Component
{
    public Collection $lastPosts;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->lastPosts = $postRepository->getLastPublishedNews(null, 10);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View|void
     */
    public function render()
    {
        if ($this->lastPosts->count() > 3) {
            return view('components.latest-news-ribbon');
        }

        return;
    }
}
