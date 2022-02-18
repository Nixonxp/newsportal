<?php

namespace App\Listeners;

use App\Events\PostPublicatedEvent;
use App\Jobs\ProccessSendingSubscribeEmail;
use App\Models\Post;
use App\Models\User;
use App\Services\Subscribe\SubscribeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class SubscribeMailListener
{
    private Post $post;
    private ?Collection $subscribers = null;
    private ?User $author = null;
    private SubscribeService $subService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SubscribeService $subService)
    {
        $this->subService = $subService;
    }

    protected function getPostAuthorSubscribers():void
    {
        if (isset($this->post->user_id)) {
            try {
                $this->subscribers = $this->subService->getSubscribersByPostId($this->post->id);
                $this->author = User::select('id', 'name')->find($this->post->user_id);
            } catch (Throwable $e) {
                Log::error($e);
            }
        }
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PostPublicatedEvent  $event
     * @return void
     */
    public function handle(PostPublicatedEvent $event): void
    {
        $this->post = $event->post;
        $this->getPostAuthorSubscribers();

        if (!empty($this->subscribers)) {
            $this->subscribers->map(function($subscriber) {
                $data = (object)[
                    'user_name' => $subscriber->user_name,
                    'user_email' => $subscriber->user_email,
                    'author' => $this->author->name,
                    'post_title' => $this->post->title,
                    'post_link' => route('newspost', [$this->post->category, $this->post]) ,
                ];

                ProccessSendingSubscribeEmail::dispatch($data)->delay(10);
            });
        }
    }
}
