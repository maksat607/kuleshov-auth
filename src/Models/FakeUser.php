<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Database\Factories\FakeUserFactory;
use Maksatsaparbekov\KuleshovAuth\Traits\AuthService;

class FakeUser
{
    use AuthService;

    protected static function newFactory(){
        return FakeUserFactory::class;
    }

}