<?php

namespace Maksatsaparbekov\KuleshovAuth;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;
use Maksatsaparbekov\KuleshovAuth\Observers\UserObserver;
use Illuminate\Support\Facades\Gate;
use Maksatsaparbekov\KuleshovAuth\Policies\ChatPolicy;

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
        $router->aliasMiddleware('resolveModel', \Maksatsaparbekov\KuleshovAuth\Http\Middleware\ResolveModelMiddleware::class);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');


        $this->mergeConfigFrom(__DIR__ . '/../config/kuleshov-auth.php', 'kuleshov-auth');

        $this->publishes([
            __DIR__ . '/../config/kuleshov-auth.php' => config_path('kuleshov-auth.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->publishes([
            __DIR__.'/Policies/ChatPolicy.php' => app_path('Policies/ChatPolicy.php')
        ], 'kuleshov-auth-policies');

        Gate::policy(ChatRoom::class, ChatPolicy::class);
    }
}

