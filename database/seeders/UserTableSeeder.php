<?php

namespace Database\Seeders;

use App\Models\Role;
use File;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Faker\Generator as Faker;

class UserTableSeeder extends Seeder
{
    protected $storage = 'app/public/userimages';

    use WithFaker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $storagePath = storage_path($this->storage);

        if(!File::exists($storagePath)){
            File::makeDirectory($storagePath, null, true);
        }

        \DB::table('users')->insert(
            [
                [
                    'name' => 'Admin',
                    'email' => 'admin@example.com',
                    'password' => bcrypt('adminpass'),
                    'role_id' => Role::where('name', 'Admin')->first()->id,
                    'image' => 'userimages/' . $faker->image(storage_path($this->storage),640,480, 'abstract', false, true),
                ],
                [
                    'name' => 'Editor',
                    'email' => 'editor@example.com',
                    'password' => bcrypt('cmanager123'),
                    'role_id' => Role::where('name', 'Editor')->first()->id,
                    'image' => 'userimages/' . $faker->image(storage_path($this->storage),640,480, 'abstract', false, true),
                ],
                [
                    'name' => 'Chief Editor',
                    'email' => 'chiefr@example.com',
                    'password' => bcrypt('chiefrpass'),
                    'role_id' => Role::where('name', 'Chief-editor')->first()->id,
                    'image' => 'userimages/' . $faker->image(storage_path($this->storage),640,480, 'abstract', false, true),
                ],
                [
                    'name' => 'User',
                    'email' => 'User@example.com',
                    'password' => bcrypt('userpass'),
                    'role_id' => Role::where('name', 'User')->first()->id,
                    'image' => 'userimages/' . $faker->image(storage_path($this->storage),640,480, 'abstract', false, true),
                ],
                [
                    'name' => 'Editor 2',
                    'email' => 'editor2@example.com',
                    'password' => bcrypt('cmanager123'),
                    'role_id' => Role::where('name', 'Editor')->first()->id,
                    'image' => 'userimages/' . $faker->image(storage_path($this->storage),640,480, 'abstract', false, true),
                ],
                [
                    'name' => 'Editor 3',
                    'email' => 'editor3@example.com',
                    'password' => bcrypt('cmanager123'),
                    'role_id' => Role::where('name', 'Editor')->first()->id,
                    'image' => 'userimages/' . $faker->image(storage_path($this->storage),640,480, 'abstract', false, true),
                ],
            ]
        );
    }
}
