<?php

namespace App\View\Components;

use App\Exceptions\ServiceException;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\Component;

class SubscribeNewsList extends Component
{
    public const PER_PAGE = 50;
    public ?LengthAwarePaginator $posts;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->posts = $this->getPosts();
    }

    private function getPosts()
    {
        try {
            $subscribes = $this->getUserSubscribes();
        } catch (ServiceException $e) {
            return Redirect::route('login', 302)->withErrors(['msg' => $e]);
        }

        if ($subscribes?->isEmpty()) {
            return null;
        }

        $query = Post::select('id', 'title', 'slug', 'category_id', 'published_at')
            ->nowPublished()
            ->with('category')
            ->where(function ($query) use ($subscribes) {
                $subscribes->map(function ($data) use ($query) {
                    $query->where(function() use ($data, $query) {
                        $query->orWhere('user_id', $data->author_id)->where('published_at', '>', $data->created_at);
                    });
                });
            });
        return $query->orderByDesc('published_at')->paginate(self::PER_PAGE);
    }

    /**
     * @throws ServiceException
     */
    private function getUserSubscribes(): Collection|null
    {
        if (!\Auth::check()) {
            throw new ServiceException(__('admin.login_need'));
        }

        return Subscriber::select('author_id', 'created_at')
            ->where('user_id', \Auth::user()->id)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.subscribe-news-list');
    }
}
