<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;

class IndexController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $posts = Post::inRandomOrder()->with('category')->limit(5)->get();
        $lastPosts = Post::orderBy('published_at', 'desc')->limit(10)->get();
        $mainPost = $lastPosts->take(1)->first();
        return view('index', compact('categories', 'posts', 'lastPosts','mainPost'));
    }
}
