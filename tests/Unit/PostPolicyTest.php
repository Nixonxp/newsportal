<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function dataProviderCreateData()
    {
        return [
            ['Admin', true],
            ['Chief-editor', true],
            ['Editor', true],
            ['User', false]
        ];
    }

    /**
     * @dataProvider dataProviderCreateData
     * @param $role
     * @param $expectResult
     * @return void
     */
    public function testCanCreatePost($role, $expectResult)
    {
        $user = $this->getUserWithRole($role);
        Auth::loginUsingId($user->id);

        $this->assertEquals($expectResult, Auth::user()->can('create', Post::class));
    }

    public function dataProviderViewData()
    {
        return [
            [
                'Admin',
                [
                    true,
                    true,
                    true,
                    true,
                ]
            ],
            [
                'Chief-editor',
                [
                    true,
                    true,
                    true,
                    true,
                ]
            ],
            [
                'Editor',
                [
                    true,
                    true,
                    true,
                    false,
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderViewData
     * @param $role
     * @param $expectResults
     * @return void
     */
    public function testCanViewPost($role, $expectResults)
    {
        $user = $this->getUserWithRole($role);
        Auth::loginUsingId($user->id);

        global $posts;
        $posts[$user->id] = $posts[$user->id] ?? $this->getPosts($user);

        foreach ($expectResults as $key => $result) {
            $this->assertEquals($result, Auth::user()->can('view', $posts[$user->id]->get($key)));
        }
    }

    public function dataProviderUpdateData()
    {
        return [
            [
                'Admin',
                [
                    true,
                    true,
                    true,
                    true,
                ]
            ],
            [
                'Chief-editor',
                [
                    true,
                    true,
                    true,
                    true,
                ]
            ],
            [
                'Editor',
                [
                    true,
                    true,
                    false,
                    false,
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderUpdateData
     * @param $role
     * @param $expectResults
     * @return void
     */
    public function testCanUpdatePost($role, $expectResults)
    {
        $user = $this->getUserWithRole($role);
        Auth::loginUsingId($user->id);

        global $posts;
        $posts[$user->id] = $posts[$user->id] ?? $this->getPosts($user);

        foreach ($expectResults as $key => $result) {
            $this->assertEquals($result, Auth::user()->can('update', $posts[$user->id]->get($key)));
        }
    }

    public function dataProviderDeleteData()
    {
        return [
            [
                'Admin',
                [
                    true,
                    true,
                    true,
                    true,
                ]
            ],
            [
                'Chief-editor',
                [
                    true,
                    true,
                    true,
                    true,
                ]
            ],
            [
                'Editor',
                [
                    false,
                    false,
                    false,
                    false,
                ]
            ],
            [
                'User',
                [
                    false,
                    false,
                    false,
                    false,
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderDeleteData
     * @param $role
     * @param $expectResults
     * @return void
     */
    public function testCanDeleteAndRestorePost($role, $expectResults)
    {
        $user = $this->getUserWithRole($role);
        Auth::loginUsingId($user->id);

        global $posts;
        $posts[$user->id] = $posts[$user->id] ?? $this->getPosts($user);

        foreach ($expectResults as $key => $result) {
            $this->assertEquals($result, Auth::user()->can('forceDelete', $posts[$user->id]->get($key)));
            $this->assertEquals($result, Auth::user()->can('delete', $posts[$user->id]->get($key)));
            $this->assertEquals($result, Auth::user()->can('restore', $posts[$user->id]->get($key)));
        }
    }

    /**
     * create posts with data types:
     * #1 current editor 2 post by type of publication
     * #2 another editor 2 post by type of publication
     *
     * @param User $user
     * @return Collection
     */
    protected function getPosts(User $user): Collection
    {
        $anotherEditor = $this->getNewUserWithRole('Editor');
        $posts = collect([]);

        $postData = [
            [
                'user_id' => $user->id,
                'is_published' => true,
                'image' => null,
            ],
            [
                'user_id' => $user->id,
                'is_published' => false,
                'published_at' => null,
                'image' => null
            ],
            [
                'user_id' => $anotherEditor->id,
                'is_published' => true,
                'image' => null
            ],
            [
                'user_id' => $anotherEditor->id,
                'is_published' => false,
                'published_at' => null,
                'image' => null
            ]
        ];
        array_map(function($value) use ($posts) {
            $posts->push(Post::factory()->make($value));
        }, $postData);

        return $posts;
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
}
