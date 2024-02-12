<?php

namespace Maksatsaparbekov\KuleshovAuth\Actions;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;
use Illuminate\Database\Eloquent\Model;
class CreateChatRoomAction
{
    public function execute(Model $model,$userId)
    {
        return $model->chatRooms()->create(['sender_id'=>$userId]);
    }
}
