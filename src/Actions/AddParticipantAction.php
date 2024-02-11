<?php

namespace Maksatsaparbekov\KuleshovAuth\Actions;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomParticipant;

class AddParticipantAction
{
    /**
     * Добавляет участника в чат-комнату или обновляет его, если он уже существует.
     *
     * @param ChatRoom $chatRoom Чат-комната для добавления участника.
     * @param int $user_id ID пользователя, которого нужно добавить как участника.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function execute(ChatRoom $chatRoom,$user_id)
    {
        return $chatRoom->participants()->updateOrCreate([
        'user_id'=>$user_id
        ]);
    }
}