<?php

namespace App\View\Components;

use App\Repositories\Interfaces\NewsPostRepositoryInterface;
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
    public function __construct(NewsPostRepositoryInterface $newsPostRepository, int $categoryId = null)
    {
        $this->categoryId = $categoryId;
        $this->posts = $newsPostRepository->getPopularRandomNewsOverPeriod(Carbon::now()->subMonths(2), 6, $this->categoryId);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View|void
     */
    public function render()
    {
        if ($this->posts->count() > 1) {
            return view('components.popular-news-carousel');
        }

        return;
    }
}
