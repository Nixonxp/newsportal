<?php

namespace App\View\Components;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdminNewUserWidget extends Component
{
    public string $bgClass = 'warning';
    public int $countInfo = 0;
    public string $title;
    public string $link;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->title = __('admin.new_users_today');
        $this->link = route('admin.users.index', ['sort' => 'id_desc']);
        $this->countInfo = User::select('id')->where('created_at', '>=', Carbon::now()->today())->count();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string|null
     */
    public function render(): View|Closure|string|null
    {
        if (\Auth::user()?->hasAnyRole(['admin'])) {
            return view('components.admin-widget-card');
        }

        return null;
    }
}
