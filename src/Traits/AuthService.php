<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;


use Carbon\Carbon;
use Illuminate\Support\Str;
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

        $this->accesstoken()->delete();
        $this->accesstoken()->create([
            'expired_at' => Carbon::now()->addYear(),
            'token' => $this->plainTextToken = sprintf('%s%s',$entropy = Str::random(40),hash('crc32b', $entropy))
        ]);

    }

}
