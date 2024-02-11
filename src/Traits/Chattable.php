<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;

use App\Models\ChatRoom;

trait Chattable
{
    public function chatRooms()
    {
        return $this->morphMany(ChatRoom::class, 'chattable');
    }
}