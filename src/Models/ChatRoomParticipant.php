<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maksatsaparbekov\KuleshovAuth\Traits\AuthService;

class ChatRoomParticipant extends Model
{
    use \Awobaz\Compoships\Compoships;
    use HasFactory;
    protected $guarded = [];
    public function chatRoom(){
        return $this->belongsTo(ChatRoom::class,'chat_room_id','id');
    }

}
