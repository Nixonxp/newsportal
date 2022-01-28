<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * Check new user role_id
     *
     * @return void
     */
    public function testCheckRegisterRole()
    {
        $userRoleId = Role::where('name', 'User')->first()->id;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $response = $this->post('register',
            [
                'name' => $this->faker->name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
            ]);

        $response->assertStatus(302);

        $user = User::where('email', $email)->first();

        $this->assertEquals($userRoleId, $user->role_id);
    }
}
