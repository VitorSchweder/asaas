<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Customers\CustomerRepositoryContract;
use App\Repositories\Customers\CustomerRepositoryEloquent;
use App\Repositories\Transactions\TransactionRepositoryContract;
use App\Repositories\Transactions\TransactionRepositoryEloquent;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            CustomerRepositoryContract::class,
            CustomerRepositoryEloquent::class,
        );

        $this->app->bind(
            TransactionRepositoryContract::class,
            TransactionRepositoryEloquent::class,
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
            CustomerRepositoryContract::class,
            CustomerRepositoryEloquent::class,
            TransactionRepositoryContract::class,
            TransactionRepositoryEloquent::class,
        ];
    }
}