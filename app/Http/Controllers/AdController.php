<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdCreateRequest;
use App\Http\Requests\AdListRequest;
use App\Http\Requests\AdUpdateRequest;
use App\Models\Ad;
use App\Services\Filters\AdFilters;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param AdListRequest $request
     * @return Application|Factory|View
     */
    public function index(AdListRequest $request): View|Factory|Application
    {
        $filters = (new AdFilters($request));
        $ads = Ad::select('id', 'name', 'type', 'image', 'showdate_start', 'showdate_end', 'created_at', 'updated_at')
            ->filter($filters)
            ->paginate(50);

        $types = Ad::TYPES;

        return view('admin.ads.index', compact('ads', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('admin.ads.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdCreateRequest $request
     * @return RedirectResponse
     */
    public function store(AdCreateRequest $request): RedirectResponse
    {
        try {
            $requestArray = $request->validated();
            if ($request->has('image')) {
                $imagePath = $request->file('image')?->store(config('filesystems.local_paths.news_images'));
                $requestArray['image'] = $imagePath;
            }
            $user = Ad::create($requestArray);
        } catch(\Throwable $e) {
            Log::channel('database')->critical($e->getMessage());
            return back()
                ->withErrors(['warning' => __('admin.save_error')])
                ->withInput();
        }

        return redirect()
            ->route('admin.ads.edit', $user)
            ->with(['success' => __('admin.save_success')]);
    }

    /**
     * Display the specified resource.
     *
     * @param Ad $ad
     * @return Application|Factory|View
     */
    public function show(Ad $ad): View|Factory|Application
    {
        return view('admin.ads.show', compact('ad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Ad $ad
     * @return Application|Factory|View
     */
    public function edit(Ad $ad): View|Factory|Application
    {
        return view('admin.ads.form', compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdUpdateRequest $request
     * @param Ad $ad
     * @return RedirectResponse
     */
    public function update(AdUpdateRequest $request, Ad $ad): RedirectResponse
    {
        $error = false;
        $requestArray = $request->validated();

        if ($request->has('image')) {
            $imagePath = $request->file('image')?->store(config('filesystems.local_paths.news_images'));
            $requestArray['image'] = $imagePath;
        }

        try {
            $ad->update($requestArray);
        } catch(\Throwable $e) {
            Log::channel('database')->critical($e->getMessage());
            $error =  __('admin.save_error');
        }

        if ($error) {
            return back()
                ->withErrors(['warning' => $error])
                ->withInput();
        }

        return redirect()
            ->route('admin.ads.edit', $ad)
            ->with(['success' => __('admin.save_success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ad $ad
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy(Ad $ad): Redirector|RedirectResponse|Application
    {
        $ad->delete();
        session()->flash('success', __('admin.delete_success'));
        return redirect(route('admin.ads.index'));
    }
}
