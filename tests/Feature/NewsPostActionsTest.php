<?php

namespace Tests\Feature;

use App\Dto\NewsPost\NewsPostDto;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use App\Services\NewsPost\NewsPostService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Str;
use Tests\TestCase;
use function app;
use function bcrypt;
use function storage_path;

class NewsPostActionsTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    private $newsPostService;
    protected $storage = 'app/public/images';

    protected function setUp():void
    {
        parent::setUp();

        $this->newsPostService = app(NewsPostService::class);
    }

    /**
     * Test create scenario
     *
     * @return void
     */
    public function testCreatePost(): void
    {
        $authUser = $this->createAuthorizeUserWithRole('Editor');
        $title = $this->faker->title;

        $stubDto = $this->getMockDto([
            'title' => $title
        ]);

        $itemNews = $this->newsPostService->createNewsPost($stubDto);
        $imagePath = $itemNews->image;

        // check substituted values
        $this->assertEquals($itemNews->slug, Str::slug($title));
        $this->assertEquals($itemNews->user_id, $authUser->id);

        // check image
        $this->assertEquals($this->checkImage($imagePath), true);
    }

    /**
     * check deleting old image
     *
     * @return void
     */
    public function testUpdatePost(): void
    {
        $authUser = $this->createAuthorizeUserWithRole('Editor');
        $title = $this->faker->title;

        $stubDto = $this->getMockDto([
            'title' => $title
        ]);
        $itemNews = $this->newsPostService->createNewsPost($stubDto);
        $imagePathOld = $itemNews->image;

        // check image
        $this->assertEquals($this->checkImage($imagePathOld), true);

        $newImagePath = $this->getImagePath();
        $newStubDto = $this->getMockDto([
            'title' => $title,
            'slug' => $itemNews->slug,
            'image' => $newImagePath,
        ]);

        $updateNewsItem = $this->newsPostService->updateNewsPostWithId($itemNews->id, $newStubDto);

        // check deleting old image
        $this->assertEquals($this->checkImage($imagePathOld), false);
        $this->assertEquals($this->checkImage($newImagePath), true);
    }

    /**
     * Throw exception if not set slug
     * in time of update
     *
     * @return void
     */
    public function testUpdatePostFailure(): void
    {
        $this->createAuthorizeUserWithRole('Editor');

        $stubDto = $this->getMockDto();
        $itemNews = $this->newsPostService->createNewsPost($stubDto);


        $newStubDto = $this->getMockDto([
            'slug' => null, // <- null slug
        ]);

        $this->expectException(QueryException::class);

        $this->newsPostService->updateNewsPostWithId($itemNews->id, $newStubDto);
    }

    /**
     * check deleting image after in 2 scenarios
     * 1. softdelete (image will be deleted)
     * 2. forcedelete (image will deleted from disk)
     *
     * @return void
     */
    public function testDeletePost(): void
    {
        $authUser = $this->createAuthorizeUserWithRole('Editor');
        $title = $this->faker->title;

        $stubDto = $this->getMockDto([
            'title' => $title
        ]);
        $itemNews = $this->newsPostService->createNewsPost($stubDto);
        $imagePath = $itemNews->image;

        // check image
        $this->assertEquals($this->checkImage($imagePath), true);

        $itemNews->delete();
        $this->assertEquals($this->checkImage($imagePath), true);

        $itemNews->forceDelete();
        $this->assertEquals($this->checkImage($imagePath), false);
    }

    protected function getImagePath()
    {
        return 'images/' . $this->faker->image(storage_path($this->storage),640,480, 'abstract', false, true);
    }

    protected function getMockDto(array $customData = []): NewsPostDto
    {
        $data = [
            'title' => $customData['title'] ?? $this->faker->title,
            'slug' => $customData['slug'] ?? null,
            'category_id' => $customData['category_id'] ?? Category::inRandomOrder()->first()->id,
            'excerpt' => $customData['excerpt'] ?? Str::limit($this->faker->realText(), random_int(80,120)),
            'content' => $customData['content'] ?? Str::limit($this->faker->realText(), random_int(450,800)),
            'image' => $customData['image'] ?? $this->getImagePath()
        ];

        $stubDto = $this->createMock(NewsPostDto::class);
        $stubDto->method('toArray')
            ->willReturn($data);

        return $stubDto;
    }

    protected function checkImage(string $path): bool
    {
        return File::exists(Storage::path($path));
    }

    protected function createAuthorizeUserWithRole($role): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
    {
        $roleId = Role::where('name', $role)->first()->id;
        $password = $this->faker->password;

        $user = User::factory()->create([
            'role_id' => $roleId,
            'password' => bcrypt($password)
        ]);

        $this->post('login',
            [
                'email' => $user->email,
                'password' => $password,
            ]);

        $response = $this->get('admin');

        $response->assertStatus(200);

        return $user;
    }
}
