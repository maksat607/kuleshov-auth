<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Maksatsaparbekov\KuleshovAuth\Database\Factories\ChattableFactory;

class Chattable extends Model
{
//    use \Maksatsaparbekov\KuleshovAuth\Traits\Chattable;
    protected $table = 'chattable';
    use HasFactory;
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
        return $this->morphMany(ChatRoom::class, 'chattable')->orderByDesc(function ($query) {
            $query->select('created_at')
                ->from('chat_room_messages')
                ->whereColumn('chat_room_id', 'chat_rooms.id')
                ->orderByDesc('updated_at')
                ->limit(1);
        });
    }
    protected static function newFactory()
    {
        return ChattableFactory::new();
    }

}