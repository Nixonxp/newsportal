<?php

namespace Tests\Feature\Observers;

use App\Models\Category;
use App\Repositories\CategoryCachedRepository;
use App\Repositories\PostCachedRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CategoryObserverCacheTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function testCreateCacheReset()
    {
        $valueDB = $this->getExistRecordAndPutCache();

        $valueCacheBefore = $this->getValueWithTag();
        $this->assertEquals($valueDB, $valueCacheBefore);
        $category = Category::create([
            'name' => 'test',
            'slug' => 'test'
        ]);

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
                'name' => 'test'
            ]
        );

        $valueCacheAfter = $this->getValueWithTag();

        $this->assertNull($valueCacheAfter);
    }

    public function testDeleteCacheReset()
    {
        $valueDB = Cache::tags(CategoryCachedRepository::class . 'getAllWithPaginate')
            ->remember('test',
                3600,
                function () {
                    return Category::create([
                        'name' => 'test',
                        'slug' => 'test'
                    ]);
                });

        $valueCacheBefore = $this->getValueWithTag();
        $this->assertEquals($valueDB, $valueCacheBefore);

        $valueDB->delete();

        $valueCacheAfter = $this->getValueWithTag();

        $this->assertNull($valueCacheAfter);
    }

    private function getExistRecordAndPutCache()
    {
        return Cache::tags(CategoryCachedRepository::class . 'getAllWithPaginate')
            ->remember('test',
            3600,
            function () {
                return Category::latest()->first();
            });
    }

    private function getValueWithTag()
    {
        return Cache::tags(CategoryCachedRepository::class . 'getAllWithPaginate')->get('test');
    }
}
