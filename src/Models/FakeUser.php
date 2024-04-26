<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Maksatsaparbekov\KuleshovAuth\Database\Factories\FakeUserFactory;
use Maksatsaparbekov\KuleshovAuth\Traits\AuthService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FakeUser extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use AuthService;
    use HasFactory;
    protected $table = 'fake_users';
    protected static function newFactory(){
        return FakeUserFactory::new();
    }

}