<?php

namespace App\Providers;

use App\Repositories\Contracts\{GlucoseDayRepositoryInterface, GlucoseRepositoryInterface, UserRepositoryInterface};
use App\Repositories\{GlucoseDayRepository, GlucoseRepository, UserRepository};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
         );

         $this->app->bind(
            GlucoseRepositoryInterface::class,
            GlucoseRepository::class
         );

         $this->app->bind(
            GlucoseDayRepositoryInterface::class,
            GlucoseDayRepository::class
         );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
