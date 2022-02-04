<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckPagesTest extends TestCase
{
    public function testHomePage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testCategoryPage()
    {
        $category = Category::first();
        $response = $this->get(route('category', $category));

        $response->assertStatus(200);
    }

    public function testNewsPostPage()
    {
        $post = Post::first();
        $response = $this->get(route('newspost', [$post->category, $post]));

        $response->assertStatus(200);
    }
}
