<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdCreateRequest;
use App\Http\Requests\AdListRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserListRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\Filters\UserFilters;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(UserListRequest $request): View
    {
        $filters = (new UserFilters($request));
        $users = User::select('id', 'name', 'email', 'role_id', 'created_at', 'updated_at')
                        ->filter($filters)
                        ->with('role')
                        ->paginate(50);

        $roles = Role::select('id', 'name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $roles = Role::select('id', 'name')->get();
        return view('admin.users.form', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreateRequest $request
     * @return RedirectResponse
     */
    public function store(UserCreateRequest $request): RedirectResponse
    {
        try {
            $requestArray = $request->validated();
            if ($request->has('image')) {
                $imagePath = $request->file('image')?->store(config('filesystems.local_paths.user_images'));
                $requestArray['image'] = $imagePath;
            }
            $requestArray['password'] = Hash::make($requestArray['password']);
            $user = User::create($requestArray);
        } catch(\Throwable $e) {
            Log::channel('database')->critical($e->getMessage());
            return back()
                ->withErrors(['warning' => __('admin.save_error')])
                ->withInput();
        }

        return redirect()
            ->route('admin.users.edit', $user)
            ->with(['success' => __('admin.save_success')]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function show(User $user): Application|Factory|View
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function edit(User $user): Application|Factory|View
    {
        $roles = Role::select('id', 'name')->get();
        return view('admin.users.form', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $error = false;
        $requestArray = $request->validated();

        if ($request->has('image')) {
            $imagePath = $request->file('image')?->store(config('filesystems.local_paths.user_images'));
            $requestArray['image'] = $imagePath;
        }

        if ($request->has('password') && $requestArray['password'] !== null) {
            $requestArray['password'] = Hash::make($requestArray['password']);
        } else {
            unset($requestArray['password']);
        }

        if ($user->hasAnyRole('admin')
            && User::select('id')
                ->withAdminRole()
                ->get()->count() < 2
            && ((int)$requestArray['role_id'] !== Role::select('id')->admin()->first()->id)) {
            $error =  __('admin.admin_exist_update_error');
        }

        if (!$error) {
            try {
                $user->update($requestArray);
            } catch(\Throwable $e) {
                Log::channel('database')->critical($e->getMessage());
                $error =  __('admin.save_error');
            }
        }

        if ($error) {
            return back()
                ->withErrors(['warning' => $error])
                ->withInput();
        }

        return redirect()
            ->route('admin.users.edit', $user)
            ->with(['success' => __('admin.save_success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy(User $user): Redirector|RedirectResponse|Application
    {
        if ($user->hasAnyRole('admin')
            && User::select('id')
                ->withAdminRole()
                ->get()->count() < 2) {
            return back()
                ->withErrors(['warning' => __('admin.admin_exist_delete_error')])
                ->withInput();
        }

        $user->delete();
        session()->flash('success', __('admin.delete_success'));
        return redirect(route('admin.users.index'));
    }
}
