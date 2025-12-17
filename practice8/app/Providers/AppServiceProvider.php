<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ChartGeneratorService;
use App\Services\DrawerService;
use App\Services\SortService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Регистрация сервисов из Practice 7
        $this->app->singleton(ChartGeneratorService::class, function ($app) {
            return new ChartGeneratorService();
        });

        $this->app->singleton(DrawerService::class, function ($app) {
            return new DrawerService();
        });

        $this->app->singleton(SortService::class, function ($app) {
            return new SortService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}