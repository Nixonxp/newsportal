<?php

namespace Tests\Feature\Services\Subscribe;

use App\Exceptions\ServiceException;
use App\Models\Post;
use App\Models\Role;
use App\Models\Subscriber;
use App\Models\User;
use App\Services\Subscribe\SubscribeService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp():void
    {
        parent::setUp();

        $this->service = new SubscribeService();

        $this->userId = User::where('role_id', Role::where('name', 'User')->first()->id)
            ->first()->id;

        $this->authorId = User::where('role_id', Role::where('name', 'Editor')->first()->id)
            ->first()->id;

        $this->newPostIdWithAuthor = Post::factory(1)->create([
            'user_id' => $this->authorId
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

    private function subscribeManually(int $userId, int $authorId): void
    {
        $subscribeAction = Subscriber::create([
            'user_id' => $userId,
            'author_id' => $authorId,
        ]);
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
}
