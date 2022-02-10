<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Date\Date;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Date::setlocale(config('app.locale'));

        Blade::directive('routeactive', function($route) {
            return "<?php echo Route::currentRouteNamed($route) ? 'active' : '' ?>";
        });

        // check current user role
        Blade::if('userhasroles', function($roles) {
            return Auth::user()->hasAnyRole($roles);
        });

        Paginator::useBootstrap();

        Collection::macro('recursive', function ($depth = null, $currentLayer = 1) {
            return $this->map(function ($value) use ($depth, $currentLayer) {
                if ((isset($depth) && $depth <= $currentLayer) || !(is_array($value) || is_object($value))) return $value;

                return collect($value)->recursive($depth, ($currentLayer + 1));
            });
        });
    }
}
