<?php
// tests/ApiAuthTest.php

use GuzzleHttp\Client;
use function Pest;

it('can login using the API', function () {

    $endpoint = 'http://127.0.0.1:8000/api/auth/login';
    $projectUuid = '5c0dba16-ca6f-11ee-ba5d-4f87b6fed42b'; // замените на реальный UUID вашего проекта
    $data = [
        'phone' => '+7 (909) 919-34-13', // замените на реальные данные
        'password' => '$2y$12$LGonP13ztSXCVHX.FPkzIuvUlMbzS3EPoQBRcBjQLepyPVgNuOY6u'    // замените на реальные данные
    ];


    $client = new Client([
        'headers' => [
            'Project-Security-Key' => $projectUuid,
            'Accept' => 'application/json',
        ]
    ]);

    // Send a POST request to the API
    $response = $client->post($endpoint, [
        'json' => $data,
    ]);

    // Assert the response status
    var_dump($response->getStatusCode());

    // Parse the response body
    $responseData = json_decode($response->getBody(), true);

    // Assert the response structure and content
    expect($responseData)->toHaveKeys(['token']);

    // Parse the response body
    $responseData = json_decode($response->getBody(), true);

    // Debugging: Output the token and other response data
    var_dump($responseData);


});
