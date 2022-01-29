<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert(
            [
                [
                    'name' => 'Admin',
                    'email' => 'admin@example.com',
                    'password' => bcrypt('adminpass'),
                    'role_id' => Role::where('name', 'Admin')->first()->id,
                ],
                [
                    'name' => 'Editor',
                    'email' => 'editor@example.com',
                    'password' => bcrypt('cmanager123'),
                    'role_id' => Role::where('name', 'Editor')->first()->id,
                ],
                [
                    'name' => 'Chief Editor',
                    'email' => 'chiefr@example.com',
                    'password' => bcrypt('chiefrpass'),
                    'role_id' => Role::where('name', 'Chief-editor')->first()->id,
                ],
                [
                    'name' => 'User',
                    'email' => 'User@example.com',
                    'password' => bcrypt('userpass'),
                    'role_id' => Role::where('name', 'User')->first()->id,
                ],
            ]
        );
    }
}
