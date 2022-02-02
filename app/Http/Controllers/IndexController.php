<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Repositories\Interfaces\NewsPostRepositoryInterface;

class IndexController extends Controller
{
    /**
     * @var NewsPostRepositoryInterface
     */
    private NewsPostRepositoryInterface $newsRepository;

    public function __construct(NewsPostRepositoryInterface $newsRepository,)
    {
        $this->newsRepository = $newsRepository;
    }

    public function index()
    {
        $posts = Post::inRandomOrder()->with('category')->limit(5)->get();
        $lastPosts = Post::orderBy('published_at', 'desc')->limit(10)->get();
        $mainPost = $lastPosts->take(1)->first();
        return view('index', compact('posts', 'lastPosts','mainPost'));
    }

    public function category(Category $category)
    {
        $categoryNews = $this->newsRepository->getLastNewsByCategoryId($category->id);
        $categoryNewsPaginate = $this->newsRepository->getLastNewsWithPaginateByCategoryId($category->id);
        return view('category', compact('category','categoryNews', 'categoryNewsPaginate'));
    }

    public function newsPost(Category $category, Post $post)
    {
        return view('newspost', compact('post'));
    }
}
