<?php

namespace Maksatsaparbekov\KuleshovAuth\Synchronization;

use App\Models\User;
class SyncLogin extends Synchronizator
{
    public function __construct($data = [])
    {
        parent::__construct([
            'phone' => request()->get('phone'),
            'password' => request()->get('password'),
            'firebase_token' => request()->get('firebase_token')
        ]);
        $this->endpoint = 'auth/login';
    }

}