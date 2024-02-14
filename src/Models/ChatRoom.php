<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ChatRoom extends Model
{
    use \Awobaz\Compoships\Compoships;
    use HasFactory;
    protected $appends = ['model_id','model_type' ,'chat_creator_id'];
    protected $visible = ['model_id','model_type', 'chat_creator_id','messages','messages.user'];



    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->with = ['chattable','messages','messages.user'];
    }
    public function getChatCreatorIdAttribute()
    {
        return $this->sender_id;
    }

    public function getModelIdAttribute()
    {
        return $this->chattable->id;
    }
    public function getModelTypeAttribute()
    {
        return class_basename($this->chattable);
    }
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i:s d.m.Y');
    }
    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i:s d.m.Y');
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

}
