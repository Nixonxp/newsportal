<?php

namespace App\View\Components;

use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class PopularNewsCarousel extends Component
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
        $this->posts = $postRepository->getPopularRandomNewsOverPeriod(Carbon::now()->subMonths(2), 6, $this->categoryId);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View|void
     */
    public function render()
    {
        if ($this->posts->isNotEmpty()) {
            return view('components.popular-news-carousel');
        }

        return;
    }
}
