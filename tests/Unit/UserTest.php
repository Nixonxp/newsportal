<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function dataProviderRoles()
    {
        return [
            ['Admin', 'Admin', true],
            ['Admin', ['Admin'], true],
            ['Chief-editor', ['chief-editor', 'admin'], true],
            ['Editor', ['chief-editor', 'admin'], false],
            ['User', ['editor', 'user'], true],
            ['Editor', ['editor', 'admin'], true]
        ];
    }

    /**
     * Check roles of users
     *
     * @dataProvider dataProviderRoles
     * @param $roleName
     * @param $testRole
     * @param $expectResult
     * @var User $user
     * @var Role $role
     * @return void
     */
    public function testUserCheckRoles($roleName, $testRole, $expectResult)
    {
        $role = Role::where('name',$roleName)->first();
        $user = User::factory()->create(
            [
                'role_id' => $role->id,
            ]
        );

        $this->assertEquals($expectResult, $user->hasAnyRole($testRole));
    }

    /**
     * Not set role_id on create user test
     *
     * @test
     * @return void
     */
    public function checkCreateNewUserWithoutRoleFail()
    {
        $newUserData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            //'role_id' => Role::where('name', 'User')->first()->id,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        $this->expectException(QueryException::class);

        $newUser = User::create($newUserData);
    }
}
