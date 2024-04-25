<?php

namespace Maksatsaparbekov\KuleshovAuth;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomMessage;
use Maksatsaparbekov\KuleshovAuth\Observers\ChatRoomMessageObserver;
use Maksatsaparbekov\KuleshovAuth\Observers\UserObserver;
use Illuminate\Support\Facades\Gate;
use Maksatsaparbekov\KuleshovAuth\Policies\ChatPolicy;
use Illuminate\Database\Eloquent\Factories\Factory as EloquentFactory;

class KuleshovAuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->make(EloquentFactory::class)->load(__DIR__.'/../src/factories');
    }

    public function boot()
    {
        User::observe(UserObserver::class);
        $router = $this->app['router'];
        $router->aliasMiddleware('auth.access_token', \Maksatsaparbekov\KuleshovAuth\Http\Middleware\AuthenticateAccessToken::class);
        $router->aliasMiddleware('resolveModel', \Maksatsaparbekov\KuleshovAuth\Http\Middleware\ResolveModelMiddleware::class);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');


        $this->publishes([
            __DIR__ . '/../config/kuleshov-auth.php' => config_path('kuleshov-auth.php'),
        ], 'config');
        $this->mergeConfigFrom(__DIR__ . '/../config/kuleshov-auth.php', 'kuleshov-auth');




        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->publishes([
            __DIR__.'/Policies/ChatPolicy.php' => app_path('Policies/ChatPolicy.php')
        ], 'kuleshov-auth-policies');

        ChatRoomMessage::observe(config('kuleshov-auth.observers.chat_room_message', ChatRoomMessageObserver::class));
        Gate::policy(ChatRoom::class, config('kuleshov-auth.policies.chat_room', ChatPolicy::class));// Укажите ваш собственный класс политики

    }

}

