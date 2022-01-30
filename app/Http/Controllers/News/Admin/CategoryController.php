<?php

namespace App\Http\Controllers\News\Admin;

use App\Http\Requests\NewsCategoryCreateRequest;
use App\Http\Requests\NewsCategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'asc')->get(['id', 'name', 'created_at', 'updated_at']);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.categories.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NewsCategoryCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NewsCategoryCreateRequest $request)
    {
        $data = $request->safe()->all();
        $item = (new Category())->create($data);

        if ($item) {
            return redirect()
                ->route('admin.categories.edit', $item->id)
                ->with(['success' => __('admin.save_success')]);
        }

        return back()
            ->withErrors(['msg' => __('admin.save_error')])
            ->withInput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NewsCategoryUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(NewsCategoryUpdateRequest $request, int $id)
    {
        $item = Category::find($id);

        if (empty($item)) {
            return back()
                ->withErrors(['msg' => __('admin.record_id_not', ['id' => $id])])
                ->withInput();
        }

        $data = $request->safe()->all();

        $result = $item
            ->update($data);

        if ($result) {
            return redirect()
                ->route('admin.categories.edit', $item->id)
                ->with(['success' => __('admin.save_success')]);
        }

        return back()
            ->withErrors(['msg' => __('admin.save_error')])
            ->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Category $category)
    {
        $category->delete();
        session()->flash('success', __('admin.delete_success'));
        return redirect(route('admin.categories.index'));
    }
}
