<?php

namespace App\Http\Controllers\News\Admin;

use App\Dto\NewsPost\CreateNewsPostDtoFactory;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Repositories\Interfaces\NewsPostRepositoryInterface;
use App\Services\NewsPost\NewsPostService;
use Illuminate\Http\Request;

class NewsController extends BaseController
{
    private $newsRepository;
    private $newsPostService;

    public function __construct(NewsPostRepositoryInterface $newsRepository, NewsPostService $newsPostService)
    {
        parent::__construct();

        $this->newsRepository = $newsRepository;
        $this->newsPostService = $newsPostService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $posts = $this->newsRepository->getNewsPostsWithFilterPaginate($request, 20);
        return view('admin.newsposts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
     * @return \Illuminate\Http\RedirectResponse
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Post $post)
    {
        return view('admin.newsposts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePostRequest $request, $id)
    {
        try {
            $dtoNewsPost = CreateNewsPostDtoFactory::fromRequest($request);
            $result = $this->newsPostService->updateNewsPostWithId($id, $dtoNewsPost);

        } catch(\Throwable $e) {
            return back()
                ->withErrors(['warning' => __('admin.save_error')])
                ->withInput();
        }

        if ($result) {
            return redirect()
                ->route('admin.posts.edit', $id)
                ->with(['success' => __('admin.save_success')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Post $post)
    {
        $post->delete();
        session()->flash('success', __('admin.delete_success'));
        return redirect(route('admin.posts.index'));
    }

    public function publish(Post $post)
    {
        // todo
    }

    public function reject(Post $post)
    {
        // todo
    }
}
