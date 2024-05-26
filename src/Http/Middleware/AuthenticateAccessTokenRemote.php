<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AuthenticateAccessTokenRemote
{
    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->header('Authorization');

        if (!$accessToken) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        // Ensure the token has the "Bearer " prefix
        if (strpos($accessToken, 'Bearer ') !== 0) {
            $accessToken = 'Bearer ' . $accessToken;
        }

        // Trim the "Bearer " prefix for validation
        $accessToken = trim(str_replace("Bearer ", "", $accessToken));

        // Check if the token is already cached
        if ($cachedUser = Cache::get($accessToken)) {
            Auth::login($cachedUser);
            return $next($request);
        }

        // Create a Guzzle HTTP client
        $client = new Client();

        try {
            // Send a request to the third-party server
            $response = $client->request('POST', config('kuleshov-auth.url').'/api/user', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ]
            ]);

            // Check if the response status code is 200 (OK)
            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody(), true);

                // Assume the response contains user data
                $user = User::find($responseData['user_id']); // Adjust based on your response structure

                // Cache the user for future requests
                Cache::put($accessToken, $user, now()->addWeek()); // Adjust the cache duration as needed

                // Log in the user
                Auth::login($user);

                return $next($request);
            } else {
                return response()->json(['error' => 'invalid_access_token'], 401);
            }
        } catch (RequestException $e) {
            // Handle request exception
            if ($e->hasResponse()) {
                return response()->json(['error' => 'invalid_access_token'], 401);
            } else {
                return response()->json(['error' => 'server_error', 'message' => $e->getMessage()], 500);
            }
        }
    }
}
