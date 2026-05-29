<?php

namespace App\Providers;

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
        if ($this->app->environment('local')) {
            $hotFile = public_path('hot');
            if (file_exists($hotFile)) {
                // Perform a quick socket check to see if the Vite dev server is running on 127.0.0.1:5173
                $connection = @fsockopen('127.0.0.1', 5173, $errno, $errstr, 0.05);
                if (!$connection) {
                    @unlink($hotFile);
                } else {
                    fclose($connection);
                }
            }
        }
    }
}
