<?php


namespace App\Dto\NewsPost;


use Auth;
use Illuminate\Http\Request;

class CreateNewsPostDtoFactory
{
    public static function fromRequest(Request $request): NewsPostDto
    {
        $requestArray = $request->validated();
        if ($request->has('image')) {
            $imagePath = $request->file('image')->store(config('filesystems.local_paths.news_images'));
            $requestArray['image'] = $imagePath;
        }

        return self::fromArray($requestArray);
    }

    public static function fromArray(array $data): NewsPostDto
    {
        $dto = new NewsPostDto();
        $dto->title = $data['title'];
        $dto->slug = $data['slug'];
        $dto->category_id = (int)$data['category_id'];
        $dto->excerpt = $data['excerpt'];
        $dto->content = $data['content'];
        $dto->is_published = (int)$data['is_published'] === 1;
        $dto->main_slider = (int)$data['main_slider'] === 1;
        $dto->popular = (int)$data['popular'] === 1;

        if (!empty($data['image'])) {
            $dto->image = $data['image'];
        }

        return $dto;
    }
}
