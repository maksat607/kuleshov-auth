<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maksatsaparbekov\KuleshovAuth\Database\Factories\ChatRoomMessageReadStatusFactory;

class ChatRoomMessageReadStatus extends Model
{
    use \Awobaz\Compoships\Compoships;
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return ChatRoomMessageReadStatusFactory::new();
    }

    public function chatRoomMessage()
    {
        return $this->belongsTo(ChatRoomMessage::class, 'chat_room_message_id', 'id');
    }
}
