<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
    Inertia::share([
        'auth' => [
            'user' => function () {
                return [
                    'id' => auth()->id(),
                    'name' => auth()->user()?->name,
                    'email' => auth()->user()?->email,
                    'role' => auth()->user()?->role, // <-- share role
                ];
            },
        ],
    ]);
}
}
