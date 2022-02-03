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
}
