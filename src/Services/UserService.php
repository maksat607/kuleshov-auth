<?php

namespace Maksatsaparbekov\KuleshovAuth\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Maksatsaparbekov\KuleshovAuth\Exceptions\LogoutSuccessfulException;

class UserService
{
    public function delete(){
        $accessToken = request()->header('Authorization');

        if (!$accessToken) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        if (strpos($accessToken, 'Bearer ') !== 0) {
            $accessToken = 'Bearer ' . $accessToken;
        }

        $accessToken = trim(str_replace("Bearer ", "", $accessToken));

        if ($accessToken) {
            Cache::forget($accessToken);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json', 'Content' => 'application/json',
            'Project-Security-Key' => config('kuleshov-auth.security_key')
        ])->post(config('kuleshov-auth.url') . '/api/auth/logout');


        if ($response->successful()) {
            throw new LogoutSuccessfulException();
        }else{
            return response()->json(['error' => 'invalid_access_token'], 401);
        }
    }

}