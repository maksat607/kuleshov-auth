<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\ChattableFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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