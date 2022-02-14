<?php

namespace App\View\Components;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class NewsWidget extends Component
{
    private PostRepositoryInterface $postRepository;
    public Collection $lastPostsWeek;
    public Collection $lastPostsMonth;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->setValues();
    }

    private function setValues():void
    {
        $this->lastPostsWeek = $this->postRepository->getPublishedNewsOverPeriod(Carbon::now()->subWeek(), 2);

        $this->lastPostsMonth = Cache::tags('lastPostsMonth')
            ->remember(serialize([__METHOD__, self::class,
                'arguments' => [$this->lastPostsWeek],
            ]),
            config('cache.post_repository_cache_time') ?? 3600,
            function () {
                return $this->postRepository
                            ->getPublishedNewsOverPeriod(Carbon::now()->subMonth(), 3, null, true)
                            ->whereNotIn('id', $this->getExcludeIds($this->lastPostsWeek))
                            ->get();
            }
        );
    }

    private function getExcludeIds(Collection $posts): array
    {
        return $posts->map(function (Post $post) {
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
