<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Str;

class PostFactory extends Factory
{
    protected $model = Post::class;
    protected $storage = 'app/public/images';

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = Str::limit($this->faker->realText(), 50);
        $isPublished = (random_int(1,10) > 1);

        $storagePath = storage_path($this->storage);
        if(!File::exists($storagePath)){
            File::makeDirectory($storagePath, null, true);
        }

        return [
            'title' => $title,
            'slug' => Str::slug($title) . random_int(1,1000),
            'image' => 'images/' . $this->faker->image(storage_path($this->storage),640,480, 'abstract', false, true),
            'category_id' => function () {
                return Category::inRandomOrder()->first()->id;
            },
            'user_id' => function () {
                User::inRandomOrder()->first()->id;
            },
            'excerpt' => $this->faker->realText(120),
            'content' => $this->faker->realText(2000, 5),
            'main_slider' => (random_int(1,10) > 8),
            'popular' => (random_int(1,10) > 8),
            'is_published' => $isPublished,
            'published_at'=> $isPublished ? $this->faker->dateTimeBetween('-2 months', 'now') : null,
        ];
    }

    public function withCleanStorageFolder()
    {
        $storagePath = storage_path($this->storage);
        if(File::exists($storagePath)){
            File::cleanDirectory($storagePath);
        }

        return $this->state(function (array $attributes, $userId) {
            return [];
        });
    }
}
