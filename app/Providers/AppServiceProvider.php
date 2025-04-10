<?php

namespace App\Providers;

use App\Models\User;
use App\Tenant\Observers\TenantObserver;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        Carbon::setLocale('pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
        
        User::observe(TenantObserver::class);
    }
}
