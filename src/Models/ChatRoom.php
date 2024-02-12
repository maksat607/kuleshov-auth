<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;
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

}
