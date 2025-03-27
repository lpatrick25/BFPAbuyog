<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\User;
use App\Observers\ApplicationObserver;
use App\Observers\ApplicationStatusObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        User::observe(UserObserver::class);
        Application::observe(ApplicationObserver::class);
        ApplicationStatus::observe(ApplicationStatusObserver::class);
    }
}
