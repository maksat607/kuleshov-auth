<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomMessageReadStatus extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function chatRoomMessage()
    {
        return $this->belongsTo(ChatRoomMessage::class,'chat_room_message_id','id');
    }
}
