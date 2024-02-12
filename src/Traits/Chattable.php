<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;

trait Chattable
{
    public function chatRooms()
    {
        return $this->morphMany(ChatRoom::class, 'chattable');
    }
}