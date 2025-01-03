<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; 

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
    public function boot()
    {
        Blade::directive('formatDateTime', function ($expression) {
            return "<?php
            if ({$expression}) {
                \$dateTime = explode(' ', {$expression});
                \$date = \$dateTime[0] ?? '';
                \$time = \$dateTime[1] ?? '';
                echo '<div style=\"margin-top: 0;\">
                    <div>Date: ' . \$date . '</div>
                    <div>' . \$time . '</div>
                </div>';
            }
        ?>";
        });
    }
}
