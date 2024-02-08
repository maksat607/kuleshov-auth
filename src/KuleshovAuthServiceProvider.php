<?php
namespace Maksatsaparbekov\KuleshovAuth;

use Illuminate\Support\ServiceProvider;

class KuleshovAuthServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('auth.access_token', \Maksatsaparbekov\KuleshovAuth\Http\Middleware\AuthenticateAccessToken::class);
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}

