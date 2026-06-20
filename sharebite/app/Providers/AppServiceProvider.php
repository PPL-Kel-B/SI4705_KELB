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
        if (app()->environment('testing') || env('APP_ENV') === 'testing') {
            config(['database.connections.mysql.database' => 'sharebite_dusk']);
            \Illuminate\Support\Facades\DB::purge('mysql');
        }
    }
}
