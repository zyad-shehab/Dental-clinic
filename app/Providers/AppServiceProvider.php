<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


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
    public function boot(): void{
        // استخدام Bootstrap للتنسيق مع الترقيم

        if (class_exists(Paginator::class))
{
    Paginator::useBootstrap(); // لجعل الترقيم متناسق مع Bootstrap
}
    }
}
