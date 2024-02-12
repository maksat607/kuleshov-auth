<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;

trait Chattable
{
    public function getAuthUserIdAttribute()
    {
        return auth()->id();
    }
    public function chatRoom(){
        return $this->chatRooms->where('sender_id', $this->auth_user_id);
    }
    public function chatRooms()
    {
        return $this->morphMany(ChatRoom::class, 'chattable');
    }
}

