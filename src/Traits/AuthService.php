<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;


use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maksatsaparbekov\KuleshovAuth\Models\AccessToken;

trait AuthService
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

    public function createToken($str = '')
    {
        if (strpos(request()->url(), 'login') !== false) {
            $this->sync('login');
        }
        if (strpos(request()->url(), 'register') !== false) {
            $this->sync('register');
        }
        $this->setToken();
        return $this;
    }

    public function sync($type)
    {

        $password = request()->get('password');
        $firebase_token = request()->get('firebase_token');
        $response = Http::withHeaders($this->header)->post($this->url . '/api/auth/' . $type, [
            'phone' => $this->phone,
            'password' => $password,
            'id' => $this->id,
            'firebase_token' => $firebase_token
        ]);

        if ($response->successful()) {
            $result = $response->json();
            if (isset($result['uuid'])) {
                $this->auth_identifier = $result['uuid'];
                $this->save();
            }


        } else {
            // Log failed response for debugging
            Log::error(' request: ' . json_encode([
                    'phone' => $this->phone,
                ]));
            Log::error('HTTP request failed with status code: ' . $response->status());
            Log::error('Response body: ' . $response->body());

            // Throw HTTP exception with error message
            abort(401, 'Unauthorized');
        }
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

    public function accesstoken()
    {
        return $this->hasOne(AccessToken::class);
    }

}
