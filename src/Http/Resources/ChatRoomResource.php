<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatRoomResource extends JsonResource
{
    public function toArray($request)
    {
        $model_id = strtolower(class_basename(get_class($this->chattable))).'_id';
        return [
            $model_id=> $this->chattable->id,
            'chat_creator_id' => $this->sender_id,
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            "__typename"=> "ChatRoom"
        ];
    }
}