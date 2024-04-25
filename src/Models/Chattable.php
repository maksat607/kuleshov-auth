<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Maksatsaparbekov\KuleshovAuth\Database\Factories\ChattableFactory;

class Chattable extends Model
{
    use \Maksatsaparbekov\KuleshovAuth\Traits\Chattable;
    protected $table = 'application';
    use HasFactory;

    protected static function newFactory()
    {
        return ChattableFactory::new();
    }

}