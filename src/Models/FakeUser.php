<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Database\Factories\FakeUserFactory;
use Maksatsaparbekov\KuleshovAuth\Traits\AuthService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FakeUser extends Model
{
    use AuthService;
    use HasFactory;
    protected static function newFactory(){
        return FakeUserFactory::new();
    }

}