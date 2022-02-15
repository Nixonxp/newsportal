<?php

namespace App\View\Components;

use App\Models\Post;
use App\Services\Subscribe\SubscribeService;
use Illuminate\View\Component;

class SubscribeButton extends Component
{
    public Post $post;
    public bool $isSubscribed;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(SubscribeService $subscribeService, Post $post)
    {
        $this->post = $post;
        $this->isSubscribed = $subscribeService->isCurrentAuthUserSubscribePostAuthor($this->post->id);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.subscribe-button');
    }
}
