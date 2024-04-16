<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maksatsaparbekov\KuleshovAuth\Jobs\MessageReadJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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
 * )
 */
class ChatRoomMessage extends Model
{
    use \Awobaz\Compoships\Compoships;
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['name', 'seen', 'phone', 'role', 'time_diff', 'sender_user_id', 'part_id'];
    protected $visible = ['id', 'seen', 'chat_room_id', 'content', 'sender_user_id', 'name', 'phone', 'role', 'time_diff', 'created_at', 'part_id', 'messageReadStatuses'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->with = ['user'];
    }

    protected static function boot()
    {
        parent::boot();
        static::retrieved(function ($message) {
            Log::info(1414141414);
            if (request()->user() && !$message->messageReadStatus) {
                Log::info('request()->user() && !$message->messageReadStatus');
                Log::info(131313131313);
                if (Route::currentRouteName() === 'viewChatMessagesForGivenChatRoom'
                    || Route::currentRouteName() === 'viewChatMessagesOfAuthUserForGiventModel'
                    || Route::currentRouteName() === 'viewChatsMessagesOfAllUsersForGivenModel'
                    || Route::currentRouteName() === 'viewChatMessagesOfAuthUser'
                ) {
                    Log::info(12121212121212);
                    $userId = request()->user()->id;
                    MessageReadJob::dispatch($userId, $message)->delay(now()->addSeconds(5));
                }
            }

        });
    }

    public function getSenderUserIdAttribute()
    {
        return $this->user_id;
    }

    public function scopeForAuthUser($query)
    {
        return $query->where('user_id', '!=', $this->auth_id);
    }

    public function getAuthIdAttribute()
    {

        if (request()->user()) {
            return request()->user()->id;
        }

        return null;
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d.m.Y H:i');
//            ->format('H:i:s d.m.Y');
    }

    public function getParticipantIdAttribute()
    {
        if ($this->participant) {
            return $this->participant->id;
        }
        return null;
    }

    public function getPartIdAttribute()
    {
        if ($this->participant) {
            $partId = $this->participant->id;
            return $partId;
        }
        return null;
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


    public function messageReadStatuses()
    {
        return $this->hasMany(ChatRoomMessageReadStatus::class, 'chat_room_message_id', 'id');
    }

    public function participant()
    {
        return $this->hasOne(ChatRoomParticipant::class, ['chat_room_id', 'user_id'], ['chat_room_id', 'auth_id']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messageReadStatus()
    {
        return $this->hasOne(ChatRoomMessageReadStatus::class, ['chat_room_message_id', 'chat_room_participant_id'], ['id', 'part_id']);
    }

    public function getSeenAttribute()
    {
        return !(ChatRoomMessage::where('id', $this->id)
                ->forAuthUser()
                ->whereDoesntHave('messageReadStatuses', function ($query) {
                    $query->where('chat_room_participant_id', $this->participant->id);
                })
                ->count() > 0);
    }

    public function getSeenMessagesAttribute()
    {
        return ChatRoomMessage::where('id', $this->id)
                ->forAuthUser()
                ->whereDoesntHave('messageReadStatuses')
                ->count() > 0;
    }
}
