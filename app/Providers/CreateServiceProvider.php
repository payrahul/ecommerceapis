<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CreateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('App\Service\CreateServiceInterface','App\Service\CreateService');
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
