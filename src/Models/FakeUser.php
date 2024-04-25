<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;


use Maksatsaparbekov\KuleshovAuth\Database\Factories\FakeUserFactory;
use Maksatsaparbekov\KuleshovAuth\Traits\AuthService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FakeUser extends Model
{
    use AuthService;
    use HasFactory;
    protected $table = 'fake_users';
    protected static function newFactory(){
        return FakeUserFactory::new();
    }

}