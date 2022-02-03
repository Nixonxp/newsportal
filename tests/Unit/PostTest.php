<?php

namespace Tests\Unit;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;;

class PostTest extends TestCase
{
    use DatabaseTransactions;

    public function getTestData()
    {
        return [
            [0, false],
            [1, true]
        ];
    }

    /**
     * Check entity attributes
     *
     * @dataProvider getTestData
     * @param $value
     * @param $expectResult
     * @return void
     */
    public function testIsPopularShowMainPublishedFlags($value, $expectResult)
    {
        $post = Post::factory()->create([
            'is_published' => $value,
            'popular' => $value,
            'main_slider' => $value,
        ]);

        $this->assertEquals($expectResult, $post->isPublished());
        $this->assertEquals($expectResult, $post->isPopular());
        $this->assertEquals($expectResult, $post->isShowInMainSlider());
    }
}
