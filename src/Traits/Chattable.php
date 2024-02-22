<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;

trait Chattable
{
    use \Awobaz\Compoships\Compoships;

    public function getAuthUserIdAttribute()
    {
        return request()->user()->id;
    }

    public function getTypeAttribute()
    {
        return get_class($this);
    }


    public function senderChatRoom()
    {
        return $this->hasOne(ChatRoom::class, ['sender_id', 'chattable_id', 'chattable_type'], ['auth_user_id', 'id', 'type']);
    }


    public function chatRooms()
    {
        return $this->morphMany(ChatRoom::class, 'chattable');
    }
}

