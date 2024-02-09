<?php

namespace Maksatsaparbekov\KuleshovAuth\Synchronization;
use App\Models\User;
enum RequestEndpoints: string
{
    case Login = 'login';
    case Register = 'register';
    case Reset = 'reset';

    public function send(User $user) {
        return match ($this){
            self::Login => (new SyncLogin())->setUser($user)->sync()->handleResponse(),
            self::Register => (new SyncRegister())->setUser($user)->sync()->handleResponse(),
            self::Reset => (new SyncResetPassword())->sync(),
        };
    }
}