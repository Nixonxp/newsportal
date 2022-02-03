<?php

namespace App\Dto\NewsPost;

class NewsPostDto
{
    public string $title;
    public ?string $slug;
    public int $category_id;
    public string $excerpt;
    public string $content;
    public ?int $user_id = null;
    public ?bool $is_published = null;
    public ?bool $main_slider = false;
    public ?bool $popular = false;
    public ?string $image;

    public function toArray():array
    {
        return get_object_vars($this);
    }
}
