<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberListRequest;
use App\Models\Subscriber;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.index');
    }

    public function subscribes(SubscriberListRequest $request)
    {
        $subscribers = Subscriber::select('id', 'user_id', 'author_id', 'created_at')
            ->when($request->author, function ($query) use ($request) {
                return $query->where('author_id', $request->author);
            })
            ->orderByDesc('id')
            ->paginate(100);

        $authors = User::select('id', 'name')
            ->withAuthorRoles()
            ->orderBy('id')
            ->get();
        return view('admin.subscribes.index', compact('subscribers', 'authors'));
    }
}
