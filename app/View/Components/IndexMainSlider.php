<?php

namespace App\View\Components;

use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class IndexMainSlider extends Component
{
    public Collection $posts;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->posts = $postRepository->getLastMainSliderPosts();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View|void
     */
    public function render()
    {
        if ($this->posts->count() > 3) {
            return view('components.index-main-slider');
        }

        return;
    }
}
