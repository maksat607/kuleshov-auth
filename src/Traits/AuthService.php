<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;


use Carbon\Carbon;
use Maksatsaparbekov\KuleshovAuth\Models\AccessToken;
use Maksatsaparbekov\KuleshovAuth\Synchronization\RequestEndpoints;
use Illuminate\Support\Facades\Log;
trait AuthService
{
    public $plainTextToken;

    public function accesstoken()
    {
        return $this->hasOne(AccessToken::class);
    }
    public function createToken($str = '')
    {
        if (str_contains(request()->url(), 'login')) {
            RequestEndpoints::from('login')->send($this);
        }
        if (str_contains(request()->url(), 'register')) {
            RequestEndpoints::from('register')->send($this);
        }
        $this->setToken();
        return $this;
    }


    public function setToken()
    {
        $token = sprintf('%d%s', $this->id, uniqid() . bin2hex(openssl_random_pseudo_bytes(16)));
        $this->accesstoken()->delete();
        $this->accesstoken()->create([
            'expired_at' => Carbon::now()->addYear(),
            'token' => $token
        ]);
        $this->plainTextToken = $token;
    }

}
