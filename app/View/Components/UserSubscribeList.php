<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class UserSubscribeList extends Component
{
    public Collection $listAuthors;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->listAuthors = DB::table('subscribers')
            ->join('users','subscribers.author_id', 'users.id')
            ->where('user_id', \Auth::user()->id)
            ->select(
                'subscribers.id',
                'subscribers.author_id',
                'subscribers.created_at',
                'users.name',
                'users.image'
            )
            ->orderByDesc('subscribers.created_at')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user-subscribe-list');
    }
}
