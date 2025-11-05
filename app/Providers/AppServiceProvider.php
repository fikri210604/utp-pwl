<?php

namespace App\Providers;

// 1. TAMBAHKAN 'use' INI
use Illuminate\Pagination\Paginator;
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
        // 2. TAMBAHKAN BARIS INI
        Paginator::useBootstrapFive(); // atau useBootstrapFour() jika Anda pakai Bootstrap 4
    }
}