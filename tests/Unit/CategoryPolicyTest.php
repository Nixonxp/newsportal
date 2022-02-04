<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryPolicyTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function dataProviderPolicyData()
    {
        return [
            [
                'Admin',
                [
                    'viewAny' => true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                    'restore' => true,
                    'forceDelete' => true
                ]
            ],
            [
                'Chief-editor',
                [
                    'viewAny' => true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                    'restore' => true,
                    'forceDelete' => true
                ]
            ],
            [
                'Editor',
                [
                    'viewAny' => true,
                    'view' => true,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                    'restore' => false,
                    'forceDelete' => false
                ]
            ],
            [
                'User',
                [
                    'viewAny' => false,
                    'view' => false,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                    'restore' => false,
                    'forceDelete' => false
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderPolicyData
     * @param $role
     * @param $expectResults
     * @return void
     */
    public function testCanUserAccessCategory($role, $expectResults)
    {
        $user = $this->getUserWithRole($role);
        Auth::loginUsingId($user->id);

        $categoryItem = Category::first();

        $accessCheckArray = [
            'viewAny' => Auth::user()->can('viewAny', Category::class),
            'view' => Auth::user()->can('view', $categoryItem),
            'create' => Auth::user()->can('create', Category::class),
            'update' => Auth::user()->can('update', $categoryItem),
            'delete' => Auth::user()->can('delete', $categoryItem),
            'restore' => Auth::user()->can('restore', $categoryItem),
            'forceDelete' => Auth::user()->can('forceDelete', $categoryItem)
        ];

        $this->assertEquals($expectResults, $accessCheckArray);
    }

    protected function getUserWithRole($role): User
    {
        $roleId = Role::where('name', $role)->first()->id;
        return User::where('role_id', $roleId)->first();
    }
}
