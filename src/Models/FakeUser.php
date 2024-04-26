<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;


use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Maksatsaparbekov\KuleshovAuth\Database\Factories\FakeUserFactory;
use Maksatsaparbekov\KuleshovAuth\Traits\AuthService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FakeUser extends Authenticatable
{
    use HasFactory, HasRoles;
    use AuthService;
    protected $table = 'fake_users';
    protected static function newFactory(){
        return FakeUserFactory::new();
    }

    public function getRoleNames(){
        return '';
    }

}