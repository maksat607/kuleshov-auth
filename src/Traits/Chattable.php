<?php

namespace Maksatsaparbekov\KuleshovAuth\Traits;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;
use Maksatsaparbekov\KuleshovAuth\Models\CheckableStatus;

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

    public function getHasUnreadAttribute()
    {
        if ($this->checkableStatuses()->count()>0){
            return true;
        }
        return false;
    }


    public function senderChatRoom()
    {
        return $this->hasOne(ChatRoom::class, ['sender_id', 'chattable_id', 'chattable_type'], ['auth_user_id', 'id', 'type']);
    }

    public function chatRooms()
    {
        return $this->morphMany(ChatRoom::class, 'chattable')->orderByDesc(function ($query) {
            $query->select('created_at')
                ->from('chat_room_messages')
                ->whereColumn('chat_room_id', 'chat_rooms.id')
                ->orderByDesc('updated_at')
                ->limit(1);
        });
    }

    public function checkableStatuses()
    {
        return $this->morphMany(CheckableStatus::class, 'checkable');
    }

    public function scopeOrderByUnread($query)
    {
        return $query->withCount('checkableStatuses')->orderBy('checkable_statuses_count', 'desc');
    }







//    public function chatRooms()
//    {
//        return $this->morphMany(ChatRoom::class, 'chattable');
//    }
}

