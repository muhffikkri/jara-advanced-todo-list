<?php

namespace App\Providers;

use App\Models\TodoList;
use App\Policies\ListMemberPolicy;
use App\Policies\ListPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(TodoList::class, ListPolicy::class);
        Gate::define('manageMembers', [ListMemberPolicy::class, 'manageMembers']);
    }
}
