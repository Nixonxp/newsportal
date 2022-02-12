<?php

namespace App\View\Components;

use App\Repositories\Interfaces\NewsPostRepositoryInterface;
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
    public function __construct(NewsPostRepositoryInterface $newsPostRepository, CurrencyService $currService)
    {
        $this->posts = $newsPostRepository->getLastPublishedNews(null, 12);
        $this->stocks = $currService->getActualInfo()->chunk(3);
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
