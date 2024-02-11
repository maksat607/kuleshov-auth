<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomMessage extends Model
{
    use HasFactory;
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class,'chat_room_id','id');
    }

    public function messageReadStatus()
    {
        return $this->hasOne(ChatRoomMessageReadStatus::class,'id','chat_room_message_id');
    }
}
