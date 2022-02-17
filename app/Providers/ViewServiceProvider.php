<?php

namespace App\Providers;

use App\Http\ViewComposers\CategoriesComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts.master'], CategoriesComposer::class);

        View::composer(['layouts.master'], function($view) {
            $personalRoute = 'login';

            if(\Auth::user()?->hasAnyRole(['Admin','Chief-editor', 'Editor'])) {
                $personalRoute = 'admin.dashboard';
            } elseif(\Auth::check()) {
                $personalRoute = 'personal.index';
            }

            $view->with(['personalRoute' => $personalRoute]);
        });
    }
}
