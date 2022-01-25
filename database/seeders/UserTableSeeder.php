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
                ],
                [
                    'name' => 'Content manager',
                    'email' => 'cm@example.com',
                    'password' => bcrypt('cmanager123'),
                ],
                [
                    'name' => 'Chief Editor',
                    'email' => 'chiefr@example.com',
                    'password' => bcrypt('chiefrpass'),
                ],
            ]
        );
    }
}
