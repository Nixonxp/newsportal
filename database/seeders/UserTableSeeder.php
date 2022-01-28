<?php

namespace Database\Seeders;

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
                    'role_id' => 1,
                ],
                [
                    'name' => 'Editor',
                    'email' => 'editor@example.com',
                    'password' => bcrypt('cmanager123'),
                    'role_id' => 2,
                ],
                [
                    'name' => 'Chief Editor',
                    'email' => 'chiefr@example.com',
                    'password' => bcrypt('chiefrpass'),
                    'role_id' => 3,
                ],
                [
                    'name' => 'User',
                    'email' => 'User@example.com',
                    'password' => bcrypt('userpass'),
                    'role_id' => 4,
                ],
            ]
        );
    }
}
