<?php

namespace Tests\Feature\View\Components;

use App\Models\Post;
use App\Models\Role;
use App\Models\Subscriber;
use App\Models\User;
use App\View\Components\SubscribeNewsList;
use ErrorException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SubscribeNewsListTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->editor1 = $this->getUserWithRole('Editor');
        $this->editor2 = $this->getNewUserWithRole('Editor');
        $this->user = $this->getUserWithRole('User');

        $this->createPosts();
        $this->subscribeManually($this->user->id, $this->editor1->id, Carbon::createFromDate(2022, 01, 23));
        $this->subscribeManually($this->user->id, $this->editor2->id, Carbon::createFromDate(2022, 01, 22));
    }

    public function testSubscribeNewsListShowFail()
    {
        $this->expectException(ErrorException::class);
        $testObj = new SubscribeNewsList();
    }

    public function testSubscribeNewsList()
    {
        \Auth::loginUsingId($this->user->id);
        $testObj = new SubscribeNewsList();

        $resultPosts = $testObj->posts;

        $this->assertEquals(2, $resultPosts->whereIn('title', ['1','3'])->count());
    }

    private function subscribeManually(int $userId, int $authorId, $date): void
    {
        $subscribeAction = Subscriber::create([
            'user_id' => $userId,
            'author_id' => $authorId,
            'created_at' => $date,
        ]);
    }

    protected function getUserWithRole($role): User
    {
        $roleId = Role::where('name', $role)->first()->id;
        return User::where('role_id', $roleId)->first();
    }

    protected function getNewUserWithRole($role)
    {
        $roleId = Role::where('name', $role)->first()->id;

        return User::factory()->create([
            'role_id' => $roleId,
            'password' => bcrypt($this->faker->password)
        ]);
    }

    protected function createPosts(): void
    {
        $postData = [
            [
                'title' => '1',
                'user_id' => $this->editor1->id,
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
                'user_id' => $this->editor2->id,
                'is_published' => false,
                'published_at' => Carbon::createFromDate(2022, 01, 23)->toDateString(),
                'image' => null,
                "slug" => "2",
                "category_id" => 3,
                "excerpt" => 'excerpt',
                "content" => 'content',
            ],
            [
                'title' => '3',
                'user_id' => $this->editor1->id,
                'is_published' => true,
                'published_at' => Carbon::createFromDate(2022, 01, 24)->toDateString(),
                'image' => null,
                "slug" => "3",
                "category_id" => 3,
                "excerpt" => 'excerpt',
                "content" => 'content',
            ],
            [
                'title' => '4',
                'user_id' => $this->editor2->id,
                'is_published' => false,
                'published_at' => Carbon::createFromDate(2022, 01, 22)->toDateString(),
                'image' => null,
                "slug" => "4",
                "category_id" => 3,
                "excerpt" => 'excerpt',
                "content" => 'content',
            ]
        ];
        array_map(function($value) {
            $post = Post::create($value);
        }, $postData);

        return;
    }
}
