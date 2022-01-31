<?php

namespace App\Providers;

use App\Repositories\Interfaces\NewsPostRepositoryInterface;
use App\Repositories\NewsPostRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            NewsPostRepositoryInterface::class,
            NewsPostRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
