<?php

namespace Maksatsaparbekov\KuleshovAuth;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Maksatsaparbekov\KuleshovAuth\Observers\UserObserver;

class KuleshovAuthServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        User::observe(UserObserver::class);
        $router = $this->app['router'];
        $router->aliasMiddleware('auth.access_token', \Maksatsaparbekov\KuleshovAuth\Http\Middleware\AuthenticateAccessToken::class);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}

