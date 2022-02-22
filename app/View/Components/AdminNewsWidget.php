<?php

namespace App\View\Components;

use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;

class AdminNewsWidget extends Component
{
    public string $bgClass = 'info';
    public int $countInfo = 0;
    public string $title;
    public string $link;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->title = __('admin.new_posts_today');
        $this->link = route('admin.posts.index', ['sort' => 'new']);
        $this->countInfo = $postRepository
            ->getPublishedNewsOverPeriod(Carbon::today(), 999, null, true)
            ->count();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        return view('components.admin-widget-card');
    }
}
