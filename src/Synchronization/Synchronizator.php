<?php

namespace Maksatsaparbekov\KuleshovAuth\Synchronization;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Synchronizator implements Synchronization
{
    public $url;
    public array $header;
    public $body;
    public $response;
    public $endpoint;
    public $user;


    public function __construct(array $data = [])
    {
        $this->url = config('kuleshov-auth.url');
        $this->header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Project-Security-Key' => config('kuleshov-auth.security_key')
        ];
        $this->body = $data;
    }

    public function sync()
    {
        if (!request()->get('password')){
            return $this;
        }
        $response = Http::withHeaders($this->header)->post($this->url . '/api/' . $this->endpoint, $this->body);
        if ($response->successful()) {
            $this->response = $response->json();
        } else {
            $this->response = $response;
            $this->handleError();
        }
        return $this;
    }

    public function handleError()
    {
        Log::error('HTTP request failed with status code: ' . $this->response->status());
        Log::error('Response body: ' . $this->response->body());
        abort(401, 'Unauthorized error from kuleshov auth service: '.$this->response->body());
    }

    public function handleResponse()
    {
        if (!request()->get('password')){
            return $this;
        }
        $this->user->auth_identifier = $this->response['uuid'];
        $this->user->save();
        return $this->response;
    }

    public function setUser($user)
    {
        $this->user = $user;
        $this->body = array_merge(['id'=>$this->user->id],$this->body);
        return $this;
    }
}