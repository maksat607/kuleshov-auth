<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ChatRoom extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC');

    }

    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC');
    }

    protected $guarded = [];

    public function chattable()
    {
        return $this->morphTo();
    }
    public function messages()
    {
        return $this->hasMany(ChatRoomMessage::class);
    }

    public function participants()
    {
        return $this->hasMany(ChatRoomParticipant::class);
    }

    public function user(){
        return $this->belongsTo(User::class,'sender_id','id');
    }

    public function users()
    {
//        return $this->hasManyThrough(
//            User::class,
//            Message::class,
//            'chat_room_id', // Foreign key on messages table...
//            'id', // Foreign key on users table...
//            'id', // Local key on chat_rooms table...
//            'user_id' // Local key on messages table...
//        );
    }

}
