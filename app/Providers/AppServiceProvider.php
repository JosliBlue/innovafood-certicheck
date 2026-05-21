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

        $dompdfFontDir = storage_path('fonts');

        if (! is_dir($dompdfFontDir)) {
            mkdir($dompdfFontDir, 0755, true);
        }

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
