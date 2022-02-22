<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * Check new user role_id
     *
     * @return void
     */
    public function testCheckRegisterData()
    {
        $userRoleId = Role::where('name', 'User')->first()->id;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $response = $this->getRegisterUserResponse($email, $password);

        $response->assertStatus(200);
        $response->assertSee(__('auth.registration_success_check_verify_email'));

        $user = User::where('email', $email)->first();

        $this->assertEquals($userRoleId, $user->role_id);
        $this->assertEquals(User::STATUS_INACTIVE, $user->status);
        $this->assertNotEmpty($user->verify_token);

        $responseLogin = $this->getLoginUserResponse($email,$password);

        $responseLogin->assertStatus(200);
        $responseLogin->assertSee(__('auth.You need to confirm your account. Please check your email.'));
        $this->assertFalse(\Auth::check());
    }

    public function testCheckVerifyUser()
    {
        $email = $this->faker->email;
        $password = $this->faker->password;
        $responseRegister = $this->getRegisterUserResponse($email, $password);
        $user = User::where('email', $email)->first();

        $responseVerify = $this->followingRedirects()->get('/verify/' . $user->verify_token);
        $responseVerify->assertStatus(200);
        $responseVerify->assertSee(__('auth.Your e-mail is verified. You can now login.'));

        $userRefresh = User::where('email', $email)->first();
        $this->assertEquals(User::STATUS_ACTIVE, $userRefresh->status);
        $this->assertEmpty($userRefresh->verify_token);
        $this->assertNotEmpty($userRefresh->email_verified_at);

        $responseLogin = $this->getLoginUserResponse($email,$password);
        $responseLogin->assertStatus(200);
        $responseLogin->assertSee(__('main.my_tape'));
        $this->assertTrue(\Auth::check());
    }

    public function testCheckVerifyUserFailed()
    {
        $email = $this->faker->email;
        $password = $this->faker->password;
        $responseRegister = $this->getRegisterUserResponse($email, $password);
        $user = User::where('email', $email)->first();

        $responseVerify = $this->followingRedirects()->get('/verify/' . $user->verify_token . '1');
        $responseVerify->assertStatus(200);
        $responseVerify->assertSee(__('auth.Sorry your link cannot be identified.'));

        $userRefresh = User::where('email', $email)->first();
        $this->assertEquals(User::STATUS_INACTIVE, $userRefresh->status);
        $this->assertNotEmpty($userRefresh->verify_token);
        $this->assertEmpty($userRefresh->email_verified_at);

        $responseLogin = $this->getLoginUserResponse($email,$password);
        $responseLogin->assertStatus(200);
        $responseLogin->assertSee(__('auth.You need to confirm your account. Please check your email.'));
        $this->assertFalse(\Auth::check());
    }

    private function getRegisterUserResponse(string $email, ?string $password = null): TestResponse
    {
        $password = $password ?? $this->faker->password;

        return $this->followingRedirects()->post('register',
            [
                'name' => $this->faker->name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
            ]);
    }

    private function getLoginUserResponse(string $email, string $password): TestResponse
    {
        return $this->followingRedirects()->post('login',
            [
                'email' => $email,
                'password' => $password,
            ]);
    }
}
