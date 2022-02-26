<?php

namespace App\Dto\NewsPost;

class NewsPostDto
{
    public string $title;
    public ?string $slug;
    public int $category_id;
    public string $excerpt;
    public string $content;
    public ?string $source_name = null;
    public ?string $source_link = null;
    public ?string $source_image = null;
    public ?bool $partner_news = null;
    public ?bool $is_published = null;
    public ?bool $main_slider = false;
    public ?bool $popular = false;
    public ?string $image;

    public function toArray():array
    {
        return get_object_vars($this);
    }
}
