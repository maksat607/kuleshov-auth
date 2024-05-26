<?php

namespace Maksatsaparbekov\KuleshovAuth\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LogOutListener
{
    public function handle(Logout $event)
    {
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
            return response()->json([
                'status' => 200,
                'message' => 'Вы успешно вышли',
            ]);
        }

    }
}

