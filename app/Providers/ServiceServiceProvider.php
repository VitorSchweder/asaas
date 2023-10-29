<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AsaasServiceContract;
use App\Services\AsaasService;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            AsaasServiceContract::class,
            AsaasService::class,
        );
    }

    /**
     * Register the application services.
     *
     * @return array
     */
    public function register(): array
    {
        return [
            AsaasServiceContract::class,
            AsaasService::class,
        ];
    }
}