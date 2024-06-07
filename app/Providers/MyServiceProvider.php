<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\MyService;
class MyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        // Binding services into the container
        // $this->app->bind('ServiceName', function ($app) {
        //     return new \App\Service\CreateService();
        // });
        $this->app->bind(MyService::class, function ($app) {
            return new MyService();
        });
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
