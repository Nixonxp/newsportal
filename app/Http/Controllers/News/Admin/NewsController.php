<?php

namespace App\Http\Controllers\News\Admin;

use App\Dto\NewsPost\CreateNewsPostDtoFactory;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\NewsListRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\NewsPost\NewsPostService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;

class NewsController extends BaseController
{
    private PostRepositoryInterface $newsRepository;
    private NewsPostService $newsPostService;

    public function __construct(PostRepositoryInterface $newsRepository, NewsPostService $newsPostService)
    {
        parent::__construct();

        $this->authorizeResource(Post::class, 'post');

        $this->newsRepository = $newsRepository;
        $this->newsPostService = $newsPostService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(NewsListRequest $request)
    {
        $posts = $this->newsRepository->getNewsPostsWithFilterPaginate($request, 20);
        return view('admin.newsposts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $categories = Category::orderBy('id', 'asc')->get(['id', 'name']);
        return view('admin.newsposts.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePostRequest $request
     * @return RedirectResponse
     */
    public function store(CreatePostRequest $request)
    {
        $dtoNewsPost = CreateNewsPostDtoFactory::fromRequest($request);

        $item = $this->newsPostService->createNewsPost($dtoNewsPost);

        if ($item) {
            return redirect()
                ->route('admin.posts.edit', $item->id)
                ->with(['success' => __('admin.save_success')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return Application|Factory|View
     */
    public function show(Post $post)
    {
        return view('admin.newsposts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     * @return Application|Factory|View
     */
    public function edit(Post $post)
    {
        $categories = Category::orderBy('id', 'asc')->get(['id', 'name']);
        return view('admin.newsposts.form', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return RedirectResponse
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            $dtoNewsPost = CreateNewsPostDtoFactory::fromRequest($request);
            $result = $this->newsPostService->updateNewsPostWithId($post->id, $dtoNewsPost);

        } catch(\Throwable $e) {
            Log::channel('database')->critical($e->getMessage());
            return back()
                ->withErrors(['warning' => __('admin.save_error')])
                ->withInput();
        }

        if ($result) {
            return redirect()
                ->route('admin.posts.edit', $post->id)
                ->with(['success' => __('admin.save_success')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy(Post $post)
    {
        $post->delete();
        session()->flash('success', __('admin.delete_success'));
        return redirect(route('admin.posts.index'));
    }
}
