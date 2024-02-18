<?php

namespace Maksatsaparbekov\KuleshovAuth\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Maksatsaparbekov\KuleshovAuth\Notifications\FirebasePush;

class FirebasePushService
{
    public function send(FirebasePush $notification, array $fcmTokens)
    {
        $client = new Client();
        $url = env('FIREBASE_URL');
        $serverKey = env('FIREBASE_TOKEN');

        $payload = array_merge(
            ["registration_ids" => $fcmTokens],
            $notification->getPayload()
        );

        $headers = [
            'Authorization' => "key={$serverKey}",
            'Content-Type' => 'application/json',
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $payload,
            ]);

            if ($response->getStatusCode() === 200) {
                // Handle successful response
                return json_decode($response->getBody()->getContents(), true);
            } else {
                // Handle error
                throw new \Exception('Failed to send notification.');
            }
        } catch (RequestException $e) {
            // Handle Guzzle HTTP request exceptions
            throw $e;
        }
    }
}

