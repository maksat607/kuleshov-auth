<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'phone' => $this->phone,
            'firebaseTokens' => $this->whenLoaded('firebaseTokens'),
        ];
    }
}