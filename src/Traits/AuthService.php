<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;


use Maksatsaparbekov\KuleshovAuth\Models\AccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

    public static function boot()
    {

        parent::boot();


        static::retrieved(function ($item) {
        });
        static::saving(function ($item) {

        });
        static::updated(function ($item) {

        });
        static::deleted(function ($item) {
        });
        static::created(function ($item) {

        });
    }

    public function accesstoken()
    {
        return $this->hasOne(AccessToken::class);
    }


    public function createToken($str = '')
    {
        if (strpos(request()->url(), 'login') !== false) {
            $this->sync('login');
        }
        if (strpos(request()->url(), 'register') !== false) {
            $this->sync('register');
        }
        return $this;
    }

    public function sync($type)
    {

        $password = request()->get('password');
        $response = Http::withHeaders($this->header)->post($this->url . '/api/auth/' . $type, [
            'phone' => $this->phone,
            'password' => $password,
            'id' => $this->id
        ]);

        if ($response->successful()) {
            $this->auth_identifier = $response->json()['uuid'];
            $this->save();

            $token = sprintf('%d%s', $this->id, uniqid() . bin2hex(openssl_random_pseudo_bytes(16)));
            $this->accesstoken()->delete();
            $this->accesstoken()->create([
                'expired_at' => Carbon::now()->addYear(),
                'token' => $token
            ]);
            $this->plainTextToken = $token;
        } else {
            // Log failed response for debugging
            Log::error(' request: ' . json_encode( [
                    'phone' => $this->phone,
                    'password' => $password
                ]));
            Log::error('HTTP request failed with status code: ' . $response->status());
            Log::error('Response body: ' . $response->body());

            // Throw HTTP exception with error message
            abort(401, 'Unauthorized');
        }
    }

}