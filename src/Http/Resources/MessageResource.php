<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'chat_room_id' => $this->chat_room_id,
            'user_id' => $this->user_id,
            'users' => new UserResource($this->whenLoaded('user')),
            'content' => $this->content,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            "__typename"=> "Message"
        ];
    }
}