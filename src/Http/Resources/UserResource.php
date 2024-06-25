<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'phone' => $this->phone,
            'role' => $this->getRoleNames(),
            'firebaseTokens' => $this->whenLoaded('firebaseTokens'),
        ];
    }
}