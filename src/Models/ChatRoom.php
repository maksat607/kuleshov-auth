<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;



/**
 * @OA\Schema(
 *     schema="ChatRoom",
 *     type="object",
 *     title="Chat Room",
 *     description="Represents a chat room within the application.",
 *     @OA\Property(
 *         property="chat_room_id",
 *         type="integer",
 *         description="The unique identifier of the chat room."
 *     ),
 *     @OA\Property(
 *         property="model_id",
 *         type="integer",
 *         description="The unique identifier of the associated model."
 *     ),
 *     @OA\Property(
 *         property="model_type",
 *         type="string",
 *         description="The type of the associated model."
 *     ),
 *     @OA\Property(
 *         property="chat_creator_id",
 *         type="integer",
 *         description="The user ID of the chat room creator."
 *     ),
 *     @OA\Property(
 *         property="messages",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ChatRoomMessage")
 *     ),
 *     @OA\Property(
 *         property="route_name",
 *         type="string",
 *         description="The route name for the current request."
 *     )
 * )
 */

class ChatRoom extends Model
{
    use \Awobaz\Compoships\Compoships;
    use HasFactory;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->with = ['chattable','messages','messages.user'];
    }
    protected $appends = ['chat_room_id','model_id','model_type' ,'chat_creator_id','route_name'];
    protected $visible = ['chat_room_id','model_id','model_type', 'chat_creator_id','messages','messages.user','route_name'];




    public function getChatRoomIdAttribute()
    {
        return $this->id;
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


    public function getRouteNameAttribute()
    {
        return request()->route()->getName();
    }

}
