<?php

namespace ActivityLogger;

use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->publishes([
        //     __DIR__.'/stubs/ActivitylogMiddleware.php' => app_path('Http/Middleware/')
        // ],'app');

        copy(__DIR__.'/stubs/ActivitylogMiddleware.php',base_path('app/Http/Middleware/ActivitylogMiddleware.php'));

        $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');
        $kernel->pushMiddleware('\App\Http\Middleware\ActivitylogMiddleware::class');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
