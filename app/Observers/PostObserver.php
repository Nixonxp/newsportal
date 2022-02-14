<?php

namespace App\Observers;

use App\Models\Post;
use App\Repositories\PostCachedRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostObserver
{

    public function creating(Post $post)
    {
        $this->setSlug($post);
        $this->setAuthor($post);
    }

    public function created(Post $post)
    {
        $this->forgetOldDataPostRepository($post);
    }

    private function setSlug(Post $post): void
    {
        if (!isset($post->slug)) {
            $post->slug = Str::slug($post->title);
        }
    }

    private function setAuthor(Post $post): void
    {
        if (\Auth::check()) {
            $post->user_id = \Auth::user()->id;
        }
    }

    public function updated(Post $post)
    {
        $oldImageSource = $post->getOriginal('image');

        if ($oldImageSource !== $post->image) {
            $this->deleteFileFromSource($oldImageSource);
        }

        $this->forgetOldDataPostRepository($post);
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Post $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        $this->deleteImageFromEntity($post);

        $this->forgetOldDataPostRepository($post);
    }

    public function deleted(Post $post)
    {
        $this->forgetOldDataPostRepository($post);
    }

    /**
     * Delete image from entity
     * @param Post $post
     */
    private function deleteImageFromEntity(Post $post)
    {
        if (isset($post->image)) {
            Storage::delete($post->image);
        }
    }

    private function forgetOldDataPostRepository(Post $post): void
    {
        if ($post->isPublished()) {
            $repositoryMethods = array_map(function($value) {
                return PostCachedRepository::class . $value;
            },
            get_class_methods(PostCachedRepository::class));

            $repositoryMethods[] = 'lastPostsMonth';

            Cache::tags($repositoryMethods)->flush();
        }
    }

    /**
     * Delete file from source
     *
     * @param string $source
     * @return void
     */
    private function deleteFileFromSource(string $source):void
    {
        if(Storage::exists($source)){
            Storage::delete($source);
        }
    }
}
