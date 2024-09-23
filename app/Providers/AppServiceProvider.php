<?php

namespace App\Providers;

use App\Models\Site;
use App\Models\Sitemap;
use App\Models\User;
use App\Policies\SitemapPolicy;
use App\Policies\SitePolicy;
use App\UserRole;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Google\Provider;
use SocialiteProviders\Manager\SocialiteWasCalled;

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
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('google', Provider::class);
        });

        Gate::policy(Site::class, SitePolicy::class);
        Gate::policy(Sitemap::class, SitemapPolicy::class);

        Gate::define('admin', fn(User $user) => $user->role->value === UserRole::ADMIN->value);
    }
}
