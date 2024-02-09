<?php

namespace Maksatsaparbekov\KuleshovAuth\Synchronization;

class Synchronizator implements Synchronization
{
    public function send(){
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


        }
    }
    public function handleResponse(array $response)
    {

    }
    public function handleError(array $error){

    }
}