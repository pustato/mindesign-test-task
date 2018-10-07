<?php

namespace App\Providers;

use App\Http\Transports\CachedGuzzleTransport;
use App\Http\Transports\GuzzleTransport;
use App\Http\Transports\TransportContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(TransportContract::class, GuzzleTransport::class);

        if ($this->app->environment('local')) {
            $this->app->bind(TransportContract::class, CachedGuzzleTransport::class);
        }
    }
}
