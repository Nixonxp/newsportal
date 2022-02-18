<?php

namespace App\Services\Subscribe;

use App\Exceptions\ServiceException;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Support\Collection;

class SubscribeService
{
    /**
     * @param int $userId
     * @param int $postId
     * @return Subscriber
     * @throws ServiceException
     */
    public function subscribeFromNewsId(int $userId, int $postId): Subscriber
    {
        $post = Post::where('id', $postId)->published()->nowPublished()->first();

        if (empty($post)) {
            throw new ServiceException(__('validation.subscribe_exists'));
        }

        if (empty($authorId = $post->user_id)) {
            throw new ServiceException(__('validation.author_not_set'));
        }

        if ($this->checkSubscribe($userId, $post->user_id)) {
            throw new ServiceException(__('validation.already_subscribed'));
        }

        return Subscriber::create([
            'user_id' => $userId,
            'author_id' => $authorId,
        ]);
    }

    /**
     * @param int $userId
     * @param int $authorId
     * @return bool
     * @throws ServiceException
     */
    public function unsubscribe(int $userId, int $authorId): bool
    {
        if (!$this->checkSubscribe($userId, $authorId)) {
            throw new ServiceException(__('validation.subscribe_not_exists'));
        }

        return (bool)Subscriber::withSubscriber($userId)->withAuthor($authorId)->delete();
    }

    /**
     * @param int $postId
     * @return bool
     */
    public function isCurrentAuthUserSubscribePostAuthor(int $postId): bool
    {
        if(!\Auth::user()) {
            return false;
        }

        $post = Post::where('id', $postId)->published()->nowPublished()->first();

        if (empty($post)) {
            return false;
        }

        return $this->checkSubscribe(\Auth::user()->id, $post->user_id);
    }

    /**
     * @param int $userId
     * @param int $author_id
     * @return bool
     */
    private function checkSubscribe(int $userId, int $author_id): bool
    {
        return Subscriber::withSubscriber($userId)->withAuthor($author_id)->exists();
    }

    /**
     * @param int $postId
     * @return Collection|null
     * @throws ServiceException
     */
    public function getSubscribersByPostId(int $postId): Collection|null
    {
        $post = Post::where('id', $postId)->published()->nowPublished()->first();

        if (empty($post)) {
            throw new ServiceException(__('validation.subscribe_exists'));
        }

        if (empty($authorId = $post->user_id)) {
            throw new ServiceException(__('validation.author_not_set'));
        }

        return Subscriber::select('id', 'user_id', 'author_id')->withAuthor($authorId)->get()->map(
            function($record) {
                return (object)[
                    'user_id' => $record->user_id,
                    'user_email' => $record->user->email,
                    'user_name' => $record->user->name,
                ];
            }
        );
    }
}
