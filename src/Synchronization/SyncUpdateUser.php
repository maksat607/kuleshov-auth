<?php

namespace Maksatsaparbekov\KuleshovAuth\Synchronization;

class SyncUpdateUser extends Synchronizator
{
    public function __construct($data = [])
    {
        parent::__construct([
            'phone' => request()->get('phone'),
            'password' => request()->get('password')
        ]);
        $this->endpoint = 'auth/update';
    }
}