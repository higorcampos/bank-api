<?php

namespace App\Providers;

use App\Services\Accounts\AccountsService;
use App\Services\Accounts\AccountsServiceContract;
use App\Services\User\UserService;
use App\Services\User\UserServiceContract;
use Illuminate\Support\ServiceProvider;

class ServiceLayerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(UserServiceContract::class, UserService::class);
        $this->app->singleton(AccountsServiceContract::class, AccountsService::class);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            UserServiceContract::class,
            AccountsServiceContract::class,
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