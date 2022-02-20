<?php

namespace Tests\Browser;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserListTest extends DuskTestCase
{
    use DatabaseTransactions;

    protected function setUp():void
    {
        parent::setUp();

        $this->admin = User::select('id', 'name', 'email', 'role_id')->withAdminRole()->first();

        $this->newUserData = [
            'name' => 'test_user',
            'email' => 'test_user@test.ru',
            'role_id' => Role::select('id')->where('name', 'User')->first()->id,
            'password' => 'password123@test.ru',
        ];
    }

    public function testUserListIndex()
    {
        $admin = $this->admin;

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
            ->visitRoute('admin.users.index')
            ->assertSee(__('admin.users'))
            ->select('role', Role::select('id')->admin()->first()->id)
            ->type('name', $admin->name)
            ->type('email', $admin->email)
            ->press(__('admin.apply'))
            ->assertRouteIs('admin.users.index')
            ->assertSee($admin->name)
            ->assertSee($admin->email);
        });
    }

    public function testUserShowPage()
    {
        $admin = $this->admin;

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visitRoute('admin.users.show', $admin)
                ->assertSee(__('admin.user_detail'))
                ->assertSee($admin->name)
                ->assertSee($admin->email)
                ->assertSee(__('admin.edit'));
        });
    }

    public function testUserEditPage()
    {
        $admin = $this->admin;

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visitRoute('admin.users.edit', $admin)
                ->assertSee(__('admin.edit_record', ['name' => $admin->name]))
                ->assertValue('input#name', $admin->name)
                ->assertValue('input#email', $admin->email)
                ->assertSelected('role_id', $admin->role_id)
                ->assertAttribute('#img-upload', 'src', $admin->image);
        });
    }

    public function testUserChangeAttr()
    {
        $admin = $this->admin;

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visitRoute('admin.users.edit', $admin)
                ->type('input#name', $admin->name . '1')
                ->press(__('admin.save'))
                ->assertSee(__('admin.save_success'))
                ->assertValue('input#name', $admin->name . '1')
                ->assertValue('input#email', $admin->email)
                ->assertSelected('role_id', $admin->role_id)
                ->assertAttribute('#img-upload', 'src', $admin->image)
                ->type('input#name', $admin->name)
                ->assertValue('input#name', $admin->name)
                ->press(__('admin.save'));
        });
    }

    public function testUserChangeAdminRoleFail()
    {
        $admin = $this->admin;

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visitRoute('admin.users.edit', $admin)
                ->select('role_id', Role::select('id')->where('name', 'Editor')->first()->id)
                ->press(__('admin.save'))
                ->assertSee(__('admin.admin_exist_update_error'));
        });
    }

    public function testDeleteAdminFail()
    {
        $admin = $this->admin;

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visitRoute('admin.users.show', $admin)
                ->click('#delete')
                ->assertSee(__('admin.admin_exist_delete_error'));
        });
    }

    public function testAddNewUser()
    {
        $admin = $this->admin;

        $userData = $this->newUserData;

        $this->browse(function (Browser $browser) use ($admin, $userData) {
            $browser->loginAs($admin)
                ->visitRoute('admin.users.index')
                ->press('#add_record')
                ->select('role_id', $userData['role_id'])
                ->type('name', $userData['name'])
                ->type('email', $userData['email'])
                ->type('password', $userData['password'])
                ->press(__('admin.save'))
                ->assertSee(__('admin.save_success'))
                ->assertValue('input#name', $userData['name'])
                ->assertValue('input#email', $userData['email'])
                ->assertSelected('role_id', $userData['role_id'])
                ->visitRoute('admin.users.index')
                ->assertSee($userData['email']);
        });
    }

    public function testDelete()
    {
        $admin = $this->admin;
        $userData = $this->newUserData;

        $this->browse(function (Browser $browser) use ($admin, $userData) {
            $browser->loginAs($admin)
                ->visitRoute('admin.users.index')
                ->assertSee($userData['name'])
                ->clickLink($userData['name'])
                ->click('#delete')
                ->assertSee(__('admin.delete_success'));
        });
    }
}
