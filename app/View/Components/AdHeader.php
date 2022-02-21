<?php

namespace App\View\Components;

use App\Models\Ad;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class AdHeader extends Component
{
    public ?Ad $adHeader = null;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->adHeader = Cache::tags('headerad')
            ->remember(serialize([__METHOD__, self::class]),
                config('cache.ad_cache_time') ?? 30,
                function () {
                    return Ad::select('id', 'name', 'link', 'image')
                        ->active()
                        ->type('header')
                        ->inRandomOrder()
                        ->first();
                });
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View|string|\Closure
    {
        return view('components.ad-header');
    }
}
