<?php

namespace Tests\Feature\Listeners;

use App\Events\PostPublicatedEvent;
use App\Jobs\ProccessSendingSubscribeEmail;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Services\Subscribe\SubscribeService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\Subscribes;
use Tests\TestCase;

class SubscribeMailListenerTest extends TestCase
{
    use DatabaseTransactions, Subscribes;

    protected function setUp():void
    {
        parent::setUp();

        $this->service = new SubscribeService();

        $this->userId = User::where('role_id', Role::where('name', 'User')->first()->id)
            ->first()->id;

        $this->authorId = User::where('role_id', Role::where('name', 'Editor')->first()->id)
            ->first()->id;

        $this->newPostWithAuthor = Post::factory(1)->create([
            'user_id' => $this->authorId,
            'is_published' => false,
            'published_at' => null,
        ])->first();

        $this->subscribeManually($this->userId, $this->authorId);
    }

    public function testCreatePost()
    {
        Event::fake([PostPublicatedEvent::class]);
        $this->createNewPublishedPost();
        Event::assertDispatched(PostPublicatedEvent::class);
    }

    public function testMailListenerCreateQueue()
    {
        Queue::fake();
        $this->createNewPublishedPost();
        Queue::assertPushed(ProccessSendingSubscribeEmail::class);
    }

    public function testUpdatePost()
    {
        Event::fake([PostPublicatedEvent::class]);
        $this->updatePostForPublish();
        Event::assertDispatched(PostPublicatedEvent::class);
    }

    public function testMailListenerQueue()
    {
        Queue::fake();
        $this->updatePostForPublish();
        Queue::assertPushed(ProccessSendingSubscribeEmail::class);
    }

    protected function createNewPublishedPost(): void
    {
        $this->newPost = Post::factory(1)->make([
            'user_id' => $this->authorId,
            'is_published' => true,
            'published_at' => Carbon::now()->toDateTimeString(),
        ])->first()->save();
    }

    protected function updatePostForPublish(): void
    {
        $this->newPostWithAuthor->update(
            [
                'is_published' => true,
                'published_at' => Carbon::now(),
            ]
        );
    }
}
