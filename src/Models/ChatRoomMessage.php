<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomMessage extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC');

    }

    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC');
    }
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class,'chat_room_id','id');
    }

    public function messageReadStatus()
    {
        return $this->hasOne(ChatRoomMessageReadStatus::class,'id','chat_room_message_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }


}
