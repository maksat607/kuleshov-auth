<?php

namespace Maksatsaparbekov\KuleshovAuth\Database\Factories;


use Maksatsaparbekov\KuleshovAuth\Models\Chattable;

use Illuminate\Database\Eloquent\Factories\Factory;
class ChattableFactory extends Factory
{
    protected $model = Chattable::class;

    public function definition()
    {
        return [
            'title'=>fake()->title
        ];
    }

}