<?php

namespace Maksatsaparbekov\KuleshovAuth\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Maksatsaparbekov\KuleshovAuth\Database\Factories\ChatRoomFactory;


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

    protected $appends = ['chat_room_id', 'title', 'thumbnail', 'model_id', 'model_type', 'chat_creator_id','chat_creator_name', 'route_name', 'unread_count', 'total_count'];
    protected $visible = ['chat_room_id', 'title', 'thumbnail', 'model_id', 'model_type', 'chat_creator_id','chat_creator_name' ,'messages', 'messages.user', 'route_name', 'unread_count', 'unread_count', 'total_count'];
    protected $guarded = [];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->with = ['chattable', 'messages', 'messages.user'];
    }

    protected static function newFactory()
    {
        return ChatRoomFactory::new();
    }

    public function getChatRoomIdAttribute()
    {
        return $this->id;
    }

    public function getChatCreatorIdAttribute()
    {
        return $this->sender_id;
    }

    public function getChatCreatorNameAttribute()
    {
        return $this->user->name;
    }

    public function getModelIdAttribute()
    {
        return $this->chattable->id;
    }

    public function getModelTypeAttribute()
    {
        return class_basename($this->chattable);
    }

    public function scopeForAuthUser($query)
    {
        return $query->whereHas('participants', function ($query) {
            $query->where('user_id', '!=', $this->auth_id);
        });
    }

    public function getUnreadCountAttribute()
    {
        return $this->messages()
            ->forAuthUser()
            ->whereDoesntHave('messageReadStatuses', function ($query) {
                $query->where('chat_room_participant_id', $this?->participant?->id);
            })
            ->count();
    }

    public function messages()
    {
        $sortOrder = request()->input('messageSort', 'desc');
        return $this->hasMany(ChatRoomMessage::class)->orderBy('updated_at', $sortOrder);
    }

    public function getUnreadMessagesAttribute()
    {
        return $this->messages()
            ->forAuthUser()
            ->whereDoesntHave('messageReadStatuses', function ($query) {
                $query->where('chat_room_participant_id', $this->participant->id);
            })
            ->get();
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i:s d.m.Y');
    }

    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i:s d.m.Y');
    }

    public function chattable()
    {
        return $this->morphTo();
    }

    public function getTitleAttribute()
    {
        if ($this->chattable->car_title) {
            return $this->chattable->car_title;
        }
        return $this->chattable?->car_title;
    }

    public function getThumbnailAttribute()
    {
        if ($this->chattable->thumbnail_url) {
            return $this->chattable->thumbnail_url;
        }
        return $this->chattable?->attachments?->first()?->thumbnail_url;
    }


    public function participants()
    {
        return $this->hasMany(ChatRoomParticipant::class, 'chat_room_id');
    }

    public function participant()
    {
        return $this->hasOne(ChatRoomParticipant::class, ['chat_room_id', 'user_id'], ['id', 'auth_id']);
    }

    public function user()
    {
        if (App::runningUnitTests()) {
            return $this->belongsTo(FakeUser::class, 'sender_id', 'id');
        }
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }


    public function getRouteNameAttribute()
    {
        if (request()->route()) {
            return request()->route()->getName();
        }

        return null;
    }

    public function getAuthIdAttribute()
    {

        if (request()->user()) {
            return request()->user()->id;
        }

        return null;
    }

    public function getTotalCountAttribute()
    {
        return $this->total_unread_count;
    }

    public function scopeOrderByUnreadCount($query)
    {
        return $query->orderBy('unread_count', 'desc');
    }
//    public function users()
//    {
//        // Assuming you need to use an additional column in the relationship
//        return $this->hasManyThrough(
//            User::class,
//            ChatRoomParticipant::class,
//            'chat_room_id', // Foreign key on ChatRoomParticipant table
//            'id', // Foreign key on User table
//            'id', // Local key on ChatRoom table
//            'user_id' // Local key on ChatRoomParticipant table
//        );
//    }

}
