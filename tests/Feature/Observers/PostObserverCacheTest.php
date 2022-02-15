<?php

namespace Tests\Feature\Observers;

use App\Models\Post;
use App\Repositories\PostCachedRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PostObserverCacheTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    private $defaultTag = PostCachedRepository::class . 'getAllWithPaginate';

    public function testCreateCacheReset()
    {
        $valueDB = $this->getExistRecordAndPutCache();

        $valueCacheBefore = $this->getValueWithTag();
        $this->assertEquals($valueDB, $valueCacheBefore);
        $post = Post::factory(1)->make()->first()->save();

        $valueCacheAfter = $this->getValueWithTag();

        $this->assertNull($valueCacheAfter);
    }

    public function testUpdateCacheReset()
    {
        $valueDB = $this->getExistRecordAndPutCache();

        $valueCacheBefore = $this->getValueWithTag();
        $this->assertEquals($valueDB, $valueCacheBefore);

        $valueDB->update(
            [
                'title' => 'test'
            ]
        );

        $valueCacheAfter = $this->getValueWithTag();

        $this->assertNull($valueCacheAfter);
    }

    public function testDeleteCacheReset()
    {
        $valueDB = $this->getExistRecordAndPutCache();

        $valueCacheBefore = $this->getValueWithTag();
        $this->assertEquals($valueDB, $valueCacheBefore);

        $valueDB->delete();

        $valueCacheAfter = $this->getValueWithTag();

        $this->assertNull($valueCacheAfter);
    }

    private function getExistRecordAndPutCache()
    {
        return Cache::tags(PostCachedRepository::class . 'getAllWithPaginate')
            ->remember('test',
            3600,
            function () {
                return Post::where('is_published', true)->first();
            });
    }

    private function getValueWithTag()
    {
        return Cache::tags($this->defaultTag)->get('test');
    }
}
