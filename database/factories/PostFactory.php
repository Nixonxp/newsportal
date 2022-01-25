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

        $storagePath = storage_path($this->storage);
        if(!File::exists($storagePath)){
            File::makeDirectory($storagePath);
        }

        return [
            'title' => $title,
            'slug' => Str::slug($title) . random_int(1,1000),
            'image' => $this->faker->image('public/storage/images',640,480, 'abstract', true, true),
            'category_id' => function () {
                return Category::inRandomOrder()->first()->id;
            },
            'user_id' => function () {
                User::inRandomOrder()->first()->id;
            },
            'excerpt' => Str::limit($this->faker->realText(), random_int(40,60)),
            'content' => Str::limit($this->faker->realText(), random_int(300,600)),
            'is_published' => 1,
            'published_at'=> Carbon::now(),
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
