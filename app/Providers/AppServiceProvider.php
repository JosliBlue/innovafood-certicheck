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
        Paginator::useTailwind();

        foreach ([storage_path('fonts'), storage_path('app/dompdf-tmp')] as $directory) {
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
        }

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
