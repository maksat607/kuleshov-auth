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

//    public function scopeOrderByUnread($query)
//    {
//        return $query->withCount('checkableStatuses')->orderBy('checkable_statuses_count', 'desc');
//    }
    public function scopeOrderByUnread($query)
    {
        return $query->withCount([
            'checkableStatuses as unread_count' => function ($q) {
                $q->where('checked', 0); // Count unread statuses
            },
            'checkableStatuses as read_count' => function ($q) {
                $q->where('checked', 1); // Count read statuses
            }
        ])
            ->orderBy('unread_count', 'desc') // Primary sorting by unread count
            ->orderBy('read_count', 'desc')  // Secondary sorting by read count
            ->orderByRaw(
                "CASE 
            WHEN unread_count = 0 AND read_count = 0 THEN (
                SELECT MAX(chat_room_messages.updated_at) 
                FROM chat_rooms
                JOIN chat_room_messages ON chat_rooms.id = chat_room_messages.chat_room_id
                WHERE chat_rooms.chattable_id = models.id
                AND chat_rooms.chattable_type = ?
            )
            ELSE NULL
        END DESC",
                [addslashes(get_class($query->getModel()))] // Dynamic morph class
            );
    }



    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }







//    public function chatRooms()
//    {
//        return $this->morphMany(ChatRoom::class, 'chattable');
//    }
}

