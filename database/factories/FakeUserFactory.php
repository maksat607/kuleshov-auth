<?php

namespace Maksatsaparbekov\KuleshovAuth\Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Maksatsaparbekov\KuleshovAuth\Models\FakeUser;

class FakeUserFactory extends Factory
{
    protected $model = FakeUser::class;

    public function definition()
    {
        return [
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // You may adjust this as per your requirements
            'remember_token' => Str::random(10),
        ];
    }
}