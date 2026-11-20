<?php

namespace App\Providers;

use App\Translation\DatabaseLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend('translation.loader', function ($loader, $app) {
            return new DatabaseLoader($app['files'], $loader->paths());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
