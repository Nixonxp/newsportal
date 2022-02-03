<?php

namespace App\View\Components;

use App\Models\Post;
use App\Repositories\Interfaces\NewsPostRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class NewsWidget extends Component
{
    private NewsPostRepositoryInterface $newsPostRepository;
    public Collection $lastPostsWeek;
    public Collection $lastPostsMonth;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(NewsPostRepositoryInterface $newsPostRepository)
    {
        $this->newsPostRepository = $newsPostRepository;
        $this->setValues();
    }

    private function setValues():void
    {
        $this->lastPostsWeek = $this->newsPostRepository->getPublishedNewsOverPeriod(Carbon::now()->subWeek(), 2);

        $this->lastPostsMonth = $this->newsPostRepository
            ->getPublishedNewsOverPeriod(Carbon::now()->subMonth(), 3, null, true)
            ->whereNotIn('id', $this->getExcludeIds($this->lastPostsWeek))
            ->get();
    }

    private function getExcludeIds(Collection $posts): array
    {
        return $posts->map(function (Post $post, $key) {
            return $post->id;
        })->toArray();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.news-widget');
    }
}
