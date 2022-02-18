<?php

namespace Tests\Feature\Services\Subscribe;

use App\Exceptions\ServiceException;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Services\Subscribe\SubscribeService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\Subscribes;
use Tests\TestCase;

class SubscribeTest extends TestCase
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

        $this->newPostIdWithAuthor = Post::factory(1)->create([
            'user_id' => $this->authorId,
        ])->first()->id;
    }

    public function testNotFoundNewsFail()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage(__('validation.subscribe_exists'));
        $this->service->subscribeFromNewsId($this->userId, 0);
    }

    public function testNotFoundNotSetAuthorFail()
    {
        $newPostId = Post::whereNull('user_id')->first()->id;

        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage(__('validation.author_not_set'));
        $this->service->subscribeFromNewsId($this->userId, $newPostId);
    }

    public function testUserAlreadySubscribedFail()
    {
        $this->subscribeManually($this->userId, $this->authorId);

        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage(__('validation.already_subscribed'));
        $this->service->subscribeFromNewsId($this->userId, $this->newPostIdWithAuthor);
    }

    public function testSubscribe()
    {
        $subscribeAction = $this->service->subscribeFromNewsId($this->userId, $this->newPostIdWithAuthor);
        $this->assertModelExists($subscribeAction);
    }

    public function testIsCurrentAuthUserSubscribePostAuthor()
    {
        $resultWithoutAuth = $this->service->isCurrentAuthUserSubscribePostAuthor($this->newPostIdWithAuthor);
        $this->assertFalse($resultWithoutAuth);

        \Auth::loginUsingId($this->userId);
        $resultNotExistPost = $this->service->isCurrentAuthUserSubscribePostAuthor(0);
        $this->assertFalse($resultNotExistPost);

        $this->subscribeManually($this->userId, $this->authorId);
        $resultTrueSubAuthor = $this->service->isCurrentAuthUserSubscribePostAuthor($this->newPostIdWithAuthor);
        $this->assertTrue($resultTrueSubAuthor);
    }

    public function testSubscribeRecordNotFoundFail()
    {
        $this->expectExceptionMessage(__('validation.subscribe_not_exists'));
        $this->expectException(ServiceException::class);
        $this->service->unsubscribe($this->userId, $this->authorId);
    }

    public function testUnsubscribe()
    {
        $this->subscribeManually($this->userId, $this->authorId);
        $result = $this->service->unsubscribe($this->userId, $this->authorId);
        $this->assertTrue($result);
    }

    public function testGetSubscribersByPostId()
    {
        $this->createPosts();
        $postId = Post::select('id')->where('title', '1')->first()->id;
        $this->subscribeManually($this->userId, $this->authorId);

        $resultSubscribers = $this->service->getSubscribersByPostId($postId)->first();
        $user = User::find($this->userId);

        $this->assertEquals(
            [
                'user_id' => $resultSubscribers->user_id,
                'user_email' => $resultSubscribers->user_email,
                'user_name' => $resultSubscribers->user_name,
            ],
            [
                'user_id' => $this->userId,
                'user_email' => $user->email,
                'user_name' => $user->name,
            ]
        );
    }

    protected function createPosts(): void
    {
        $postData = [
            [
                'title' => '1',
                'user_id' => $this->authorId,
                'is_published' => true,
                'published_at' => Carbon::createFromDate(2022, 01, 24)->toDateString(),
                'image' => null,
                "slug" => "1",
                "category_id" => 3,
                "excerpt" => 'excerpt',
                "content" => 'content',
            ],
            [
                'title' => '2',
                'user_id' => $this->authorId,
                'is_published' => false,
                'published_at' => Carbon::createFromDate(2022, 01, 23)->toDateString(),
                'image' => null,
                "slug" => "2",
                "category_id" => 3,
                "excerpt" => 'excerpt',
                "content" => 'content',
            ],
        ];
        array_map(function($value) {
            $post = Post::create($value);
        }, $postData);

        return;
    }
}
