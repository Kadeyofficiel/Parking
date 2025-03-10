<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Number;

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
        Schema::defaultStringLength(191);
        
        // Contournement pour l'extension intl manquante
        if (!extension_loaded('intl')) {
            Number::macro('format', function ($number, $precision = 0, $locale = null) {
                return number_format($number, $precision);
            });
            
            Number::macro('fileSize', function ($bytes, $precision = 2, $locale = null) {
                $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
                for ($i = 0; $bytes > 1024; $i++) {
                    $bytes /= 1024;
                }
                return round($bytes, $precision) . ' ' . $units[$i];
            });
        }
    }
}
