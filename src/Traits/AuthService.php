<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Maksatsaparbekov\KuleshovAuth\Models\AccessToken;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;
use Maksatsaparbekov\KuleshovAuth\Synchronization\RequestEndpoints;

trait AuthService
{
    public $plainTextToken;

    public function createToken($str = '')
    {
        if (str_contains(request()->url(), 'login')) {
            request()->merge(['phone' => request()->input('phone', $this->phone)]);
            $token = RequestEndpoints::from('login')->send($this);
        }
        if (str_contains(request()->url(), 'register')) {
            $token = RequestEndpoints::from('register')->send($this);
        }
        $this->plainTextToken = $token['token'];
        if (strlen($token['token']) > 0) {
            $this->plainTextToken = $token['token'];
        } else {
            $this->setToken();
        }
        return $this;
    }

    public function setToken()
    {
        $this->accesstoken()->create([
            'expired_at' => Carbon::now()->addYears(2),
            'token' => $this->plainTextToken = sprintf('%s%s', $entropy = Str::random(40), hash('crc32b', $entropy))
        ]);
    }

    public function accesstoken()
    {
        return new UserService();
    }

    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'sender_id', 'id');
    }
}
