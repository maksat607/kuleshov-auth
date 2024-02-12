<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomParticipant extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function chatRoom(){
        return $this->belongsTo(ChatRoom::class,'chat_room_id','id');
    }
}
