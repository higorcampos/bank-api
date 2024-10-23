<?php

namespace App\Providers;

use App\Repositories\Accounts\AccountsRepositoryContract;
use App\Repositories\Accounts\AccountsRepositoryEloquent;
use App\Repositories\User\UserRepositoryContract;
use App\Repositories\User\UserRepositoryEloquent;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepositoryContract::class, UserRepositoryEloquent::class);
        $this->app->singleton(AccountsRepositoryContract::class, AccountsRepositoryEloquent::class);
    }


    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            UserRepositoryContract::class,
            AccountsRepositoryContract::class,
        ];
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}