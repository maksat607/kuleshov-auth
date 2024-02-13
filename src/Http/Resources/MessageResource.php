<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'id' => $this->user->id,
            'name' => $this->user->name,
            'phone' => $this->user->phone,
            'role' => $this->user?->getRoleNames()[0] ?? '',
            'created_at' => $this->created_at,
//            'users' => new UserResource($this->whenLoaded('user')),
            "__typename"=> "Message"
        ];
    }
}