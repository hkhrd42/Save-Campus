<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\Meal;
use App\Policies\MealPolicy;
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
        Gate::policy(Meal::class, MealPolicy::class);
    }
}
