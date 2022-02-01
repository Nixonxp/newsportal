<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);

        Post::factory(30)->withCleanStorageFolder()->create();
    }
}
