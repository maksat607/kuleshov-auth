<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Database\Factories\ChatRoomParticipantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomParticipant extends Model
{
    use \Awobaz\Compoships\Compoships;
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return ChatRoomParticipantFactory::class;
    }

    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id', 'id');
    }

}
