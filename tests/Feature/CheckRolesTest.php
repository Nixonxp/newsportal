<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckRolesTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function dataProviderRoles()
    {
        return [
            ['Admin', 200],
            ['Chief-editor', 200],
            ['Editor', 200],
            ['User', 403],
        ];
    }

    /**
     * @dataProvider dataProviderRoles
     * @param $roleName
     * @param $expectStatus
     * @return void
     */
    public function testCheckRoutesWithRoles($roleName, $expectStatus)
    {
        $roleId = Role::where('name', $roleName)->first()->id;
        $password = $this->faker->password;

        $user = User::factory()->create([
            'role_id' => $roleId,
            'status' => User::STATUS_ACTIVE,
            'password' => bcrypt($password)
        ]);

        $this->post('login',
        [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response = $this->get('admin');

        $response->assertStatus($expectStatus);
    }
}
