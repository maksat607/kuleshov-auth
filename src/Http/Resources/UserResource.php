<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            "__typename"=> "User"
        ];
    }
}