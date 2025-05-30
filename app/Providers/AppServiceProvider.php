<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        // Load routes/test.php untuk keperluan pengujian
        if (file_exists(base_path('routes/test.php'))) {
            Route::middleware('web')
                ->group(base_path('routes/test.php'));
        }
    }
}
