<?php

namespace App\View\Components;

use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\Currency\CurrencyService;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class TopHeader extends Component
{
    public Collection $posts;
    public ?Collection $stocks;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(PostRepositoryInterface $postRepository, CurrencyService $currService)
    {
        $this->posts = $postRepository->getLastPublishedNews(null, 12);
        $this->stocks = $currService->getActualCachedInfo()->chunk(3);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.top-header');
    }
}
