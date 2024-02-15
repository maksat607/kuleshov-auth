<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ChatRoomMessage",
 *     type="object",
 *     title="Chat Room Message",
 *     description="Represents a message within a chat room.",
 *     @OA\Property(property="content", type="string", description="The content of the message"),
 *     @OA\Property(property="sender_user_id", type="integer", description="The ID of the user who sent the message"),
 *     @OA\Property(property="name", type="string", description="The name of the user who sent the message"),
 *     @OA\Property(property="phone", type="string", description="The phone number of the user who sent the message"),
 *     @OA\Property(property="role", type="string", description="The role of the user who sent the message"),
 *     @OA\Property(property="time_diff", type="string", description="The time difference since the message was created"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="The creation date and time of the message"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="The last update date and time of the message"),
 * )
 */


class ChatRoomMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['name', 'phone', 'role', 'time_diff', 'sender_user_id'];
    protected $visible = ['content', 'sender_user_id', 'name', 'phone', 'role', 'time_diff','created_at'];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->with = ['user'];
    }

    public function getSenderUserIdAttribute()
    {
        return $this->user->id;
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i:s d.m.Y');
    }

    public function getTimeDiffAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at)->locale('ru')->diffForHumans();
    }

    public function getNameAttribute()
    {
        return $this->user->name;
    }

    public function getPhoneAttribute()
    {
        return $this->user->phone;
    }

    public function getRoleAttribute()
    {
        return $this->user?->getRoleNames()[0] ?? '';
    }


    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i:s d.m.Y');
    }

    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id', 'id');
    }

    public function messageReadStatus()
    {
        return $this->hasOne(ChatRoomMessageReadStatus::class, 'id', 'chat_room_message_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
