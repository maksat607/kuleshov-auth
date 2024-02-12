<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatRoomResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
//            'users' => new UserResource($this->whenLoaded('user')),
            "__typename"=> "ChatRoom"
        ];
    }
}