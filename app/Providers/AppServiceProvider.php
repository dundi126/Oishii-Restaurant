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
        // Define Gate for admin authorization
        \Illuminate\Support\Facades\Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });
    }
}
