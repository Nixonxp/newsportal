<?php

namespace App\Providers;

use App\Services\Currency\Interfaces\CurrencyClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (config('currency.default') !== null) {
            $apiClientClass = 'App\\Services\\Currency\\Clients\\' . ucwords(config('currency.default')) . "Client";
            $this->app->bind(CurrencyClientInterface::class, function() use ($apiClientClass){
                return new $apiClientClass();
            });

            $this->app->bind(ClientInterface::class, Client::class);
        }
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
