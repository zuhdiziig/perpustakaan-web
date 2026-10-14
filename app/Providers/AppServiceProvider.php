<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (
            request()->isSecure()
            || request()->header('X-Forwarded-Proto') === 'https'
            || str_contains(request()->getHost(), 'github.dev')
            || str_contains(request()->getHost(), 'app.github.dev')
            || str_contains(request()->getHost(), 'devtunnels.ms')
            || str_contains(request()->getHost(), 'ngrok')
            || ! in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1'])
        ) {
            URL::forceScheme('https');
        }

        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');
    }
}
