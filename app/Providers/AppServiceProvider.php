<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;// Pastikan ini ada
use Illuminate\Support\Facades\Auth;             // Pastikan ini ada

class AppServiceProvider extends ServiceProvider
{
    // ...

    public function boot(): void
    {
        RedirectIfAuthenticated::redirectUsing(function ($request) {
            if (Auth::check()) {
                $role = Auth::user()->role;
                switch ($role) {
                    case 'admin':
                        return route('admin.dashboard');
                    case 'panitia':
                        return route('panitia.dashboard');
                    case 'juri':
                        return route('juri.dashboard');
                }
            }
            return '/';
        });
    }
}