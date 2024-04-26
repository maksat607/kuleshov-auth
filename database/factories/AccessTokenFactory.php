<?php

namespace Maksatsaparbekov\KuleshovAuth\Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Maksatsaparbekov\KuleshovAuth\Models\AccessToken;
use Maksatsaparbekov\KuleshovAuth\Models\FakeUser;

class AccessTokenFactory extends Factory
{
    protected $model = AccessToken::class;

    public function definition()
    {
        return [
            'user_id' => fake()->optional()->randomNumber(8), // Random user ID
            'created_at' => fake()->dateTimeThisMonth,
            'expired_at' => fake()->dateTimeInFuture(month: 1), // Expires in 1 month
            'token' => Str::random(60), // Generate a random token
        ];
    }
}