<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ElasticSearchTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->postRepository = \App::make(PostRepository::class);
    }

    public function testObserverPublishedNews()
    {
        $title = 'test_title_search_4';
        $newPostTest = Post::factory(1)->create([
            'title' => $title,
            'content' => 'test content search 1',
            'is_published' => true,
            'published_at' => now(),
        ])->first();

        usleep(1200000);
        $result1 =  $this->callProtectedMethod($this->postRepository, 'searchOnElasticsearch', [$title]);
        $ids = Arr::pluck($result1['hits']['hits'], '_id');
        $this->assertTrue(in_array($newPostTest->id, $ids));

        $newPostTest->delete();
        usleep(1200000);
        $result2 =  $this->callProtectedMethod($this->postRepository, 'searchOnElasticsearch', [$title]);
        $ids2 = Arr::pluck($result2['hits']['hits'], '_id');
        $this->assertFalse(in_array($newPostTest->id, $ids2));
    }

    public function testObserverNotPublishedNews()
    {
        $title = 'test_title_search_5';
        $newPostTest = Post::factory(1)->create([
            'title' => $title,
            'content' => 'test content search 5',
            'is_published' => false,
            'published_at' => null,
        ])->first();

        usleep(900000);
        $result1 =  $this->callProtectedMethod($this->postRepository, 'searchOnElasticsearch', [$title]);
        $ids = Arr::pluck($result1['hits']['hits'], '_id');
        $this->assertFalse(in_array($newPostTest->id, $ids));
    }
}
