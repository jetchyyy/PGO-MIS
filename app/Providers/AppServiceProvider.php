<?php

namespace App\Providers;

use App\Models\User;
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
        Gate::before(fn (User $user, string $ability) => $user->hasRole(User::ROLE_SYSTEM_ADMIN) ? true : null);

        Gate::define('issuance.manage', fn (User $user) => $user->hasRole(User::ROLE_PROPERTY_STAFF, User::ROLE_APPROVING_OFFICIAL));
        Gate::define('transfer.manage', fn (User $user) => $user->hasRole(User::ROLE_PROPERTY_STAFF, User::ROLE_APPROVING_OFFICIAL));
        Gate::define('disposal.manage', fn (User $user) => $user->hasRole(User::ROLE_PROPERTY_STAFF, User::ROLE_APPROVING_OFFICIAL));
        Gate::define('approvals.manage', fn (User $user) => $user->hasRole(User::ROLE_APPROVING_OFFICIAL));
        Gate::define('reports.view', fn (User $user) => $user->hasRole(User::ROLE_PROPERTY_STAFF, User::ROLE_APPROVING_OFFICIAL, User::ROLE_AUDIT_VIEWER));
        Gate::define('logs.view', fn (User $user) => $user->hasRole(User::ROLE_APPROVING_OFFICIAL, User::ROLE_AUDIT_VIEWER));
    }
}
