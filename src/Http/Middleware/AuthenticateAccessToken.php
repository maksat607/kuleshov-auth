<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maksatsaparbekov\KuleshovAuth\Models\AccessToken;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class AuthenticateAccessToken
{

    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->header('authorization');

        if (!$accessToken) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }
        $accessToken = trim(str_replace("Bearer", "", $accessToken));
        $accessTokenModel = AccessToken::where([
            ['token', $accessToken],
            ['expired_at', '>', now()],
        ])->first();

        if (!$accessTokenModel) {
            return response()->json(['error' => 'invalid_access_token'], 401);
        }

        $user = $accessTokenModel->user;

        Auth::login($user);

        return $next($request);
    }


}
