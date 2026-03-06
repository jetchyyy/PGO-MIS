<?php

namespace App\Providers;

use App\Models\BrandingSetting;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        Gate::before(fn (User $user, string $ability) => $user->hasRole(User::ROLE_SUPER_ADMIN, User::ROLE_SYSTEM_ADMIN) ? true : null);

        Gate::define('issuance.manage', fn (User $user) => $user->hasRole(User::ROLE_PROPERTY_STAFF, User::ROLE_APPROVING_OFFICIAL));
        Gate::define('transfer.manage', fn (User $user) => $user->hasRole(User::ROLE_PROPERTY_STAFF, User::ROLE_APPROVING_OFFICIAL));
        Gate::define('disposal.manage', fn (User $user) => $user->hasRole(User::ROLE_PROPERTY_STAFF, User::ROLE_APPROVING_OFFICIAL));
        Gate::define('inventory.manage', fn (User $user) => $user->hasRole(User::ROLE_PROPERTY_STAFF, User::ROLE_APPROVING_OFFICIAL, User::ROLE_ACCOUNTABLE_OFFICER));
        Gate::define('approvals.manage', fn (User $user) => $user->hasRole(User::ROLE_APPROVING_OFFICIAL));
        Gate::define('reports.view', fn (User $user) => $user->hasRole(User::ROLE_PROPERTY_STAFF, User::ROLE_APPROVING_OFFICIAL, User::ROLE_AUDIT_VIEWER));
        Gate::define('logs.view', fn (User $user) => $user->hasRole(User::ROLE_APPROVING_OFFICIAL, User::ROLE_AUDIT_VIEWER));

        View::share('whiteLabel', $this->resolveWhiteLabel());
    }

    private function resolveWhiteLabel(): array
    {
        $defaults = [
            'app_name' => config('app.name', 'PGSO-SDN PMIS'),
            'meta_title' => 'PGSO Property MIS - Provincial Government of Surigao del Norte',
            'meta_description' => 'Property Management and Inventory System of the Provincial General Services Office.',
            'nav_title' => 'PGSO-PMIS',
            'nav_subtitle' => 'Surigao Del Norte',
            'welcome_badge' => 'Official Government Portal',
            'welcome_title' => 'Provincial General Services Office',
            'welcome_subtitle' => 'Property Management System',
            'welcome_description' => 'A centralized digital platform for managing, tracking, and auditing government properties and equipment.',
            'login_heading' => 'System Access',
            'login_subheading' => 'Sign in with your authorized account',
            'footer_text' => 'Provincial Government of Surigao Del Norte - Property Management System',
            'footer_subtext' => 'This system is built by New Zenith Datacom OPC',
            'logo_url' => asset('images/surigaodelnorte.png'),
            'welcome_bg_url' => asset('images/sdncapitollongshot.jpg'),
            'login_bg_url' => asset('images/sdncapitollongshot.jpg'),
            'og_image_url' => asset('images/og-image.png'),
            'favicon_url' => asset('favicon.ico'),
        ];

        if (!Schema::hasTable('branding_settings')) {
            return $defaults;
        }

        $branding = BrandingSetting::query()->first();

        if ($branding === null) {
            return $defaults;
        }

        return [
            'app_name' => $branding->app_name ?: $defaults['app_name'],
            'meta_title' => $branding->meta_title ?: $defaults['meta_title'],
            'meta_description' => $branding->meta_description ?: $defaults['meta_description'],
            'nav_title' => $branding->nav_title ?: $defaults['nav_title'],
            'nav_subtitle' => $branding->nav_subtitle ?: $defaults['nav_subtitle'],
            'welcome_badge' => $branding->welcome_badge ?: $defaults['welcome_badge'],
            'welcome_title' => $branding->welcome_title ?: $defaults['welcome_title'],
            'welcome_subtitle' => $branding->welcome_subtitle ?: $defaults['welcome_subtitle'],
            'welcome_description' => $branding->welcome_description ?: $defaults['welcome_description'],
            'login_heading' => $branding->login_heading ?: $defaults['login_heading'],
            'login_subheading' => $branding->login_subheading ?: $defaults['login_subheading'],
            'footer_text' => $branding->footer_text ?: $defaults['footer_text'],
            'footer_subtext' => $branding->footer_subtext ?: $defaults['footer_subtext'],
            'logo_url' => $branding->logo_path ? asset('storage/'.$branding->logo_path) : $defaults['logo_url'],
            'welcome_bg_url' => $branding->welcome_bg_path ? asset('storage/'.$branding->welcome_bg_path) : $defaults['welcome_bg_url'],
            'login_bg_url' => $branding->login_bg_path ? asset('storage/'.$branding->login_bg_path) : $defaults['login_bg_url'],
            'og_image_url' => $branding->og_image_path ? asset('storage/'.$branding->og_image_path) : $defaults['og_image_url'],
            'favicon_url' => $branding->favicon_path ? asset('storage/'.$branding->favicon_path) : $defaults['favicon_url'],
        ];
    }
}
