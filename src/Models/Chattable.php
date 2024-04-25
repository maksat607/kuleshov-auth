<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Database\Factories\ChattableFactory;

class Chattable
{
    use \Maksatsaparbekov\KuleshovAuth\Traits\Chattable;
    protected $table = 'application';


    protected static function newFactory()
    {
        return ChattableFactory::new();
    }

}