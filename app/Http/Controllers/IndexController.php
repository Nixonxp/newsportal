<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function category(Category $category)
    {
        return view('category', compact('category'));
    }

    public function newsPost(Category $category, Post $post)
    {
        return view('newspost', compact('post'));
    }

    public function search(PostRepositoryInterface $newsRepository)
    {
        $posts = !empty(request('q'))
            ? $newsRepository->search(request('q'))
            : collect([]);

        return view('search', compact('posts'));
    }
}
