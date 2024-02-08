<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;


use Maksatsaparbekov\KuleshovAuth\Models\AccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

trait UserTrait
{
    public $plainTextToken;
    private $url;
    private $header;

    public function __construct()
    {
        $this->url = env('AUTH_SERVICE_URL');
        $this->header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Project-Security-Key' => env('PROJECT_SECURITY_KEY')
        ];
    }

    public function accesstoken()
    {
        return $this->hasOne(AccessToken::class);
    }


    public function createToken($str = '')
    {
        if (strpos(request()->url(), 'login') !== false) {
            return $this->sync('login');
        }
        if (strpos(request()->url(), 'register') !== false) {
            return $this->sync('register');
        }
        return $this;
    }

    public function sync($type)
    {
        $password = request()->get('password');
        $response = Http::withHeaders($this->header)->post($this->url . '/' . $type, [
            'phone' => $this->phone,
            'password' => $password
        ]);

        if ($response->successful()) {
            $this->uuid = $response->json()['uuid'];
            $this->uuid->save();

            $token = sprintf('%d%s', $this->id, uniqid() . bin2hex(openssl_random_pseudo_bytes(16)));
            $this->accessTokens()->delete();
            $this->accessTokens()->create([
                'expired_at' => Carbon::now()->addYear(),
                'token' => $token
            ]);
            $this->plainTextToken = $token;
        } else {
            abort(401, 'Unauthorized');
        }
    }

}
