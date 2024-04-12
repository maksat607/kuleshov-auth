<?php

namespace Maksatsaparbekov\KuleshovAuth\Actions;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomMessageReadStatus;

class MessageReadAction
{
    /**
     * Обрабатывает действие для сообщения в чате, основываясь на ID сообщения и ID участника чата.
     *
     * @param  $chat_room_message сообщения в чате.
     * @param int $chat_room_participant_id ID участника чата.
     * @return mixed Возвращает результат выполнения метода.
     */
    public function execute( $chat_room_message, int $chat_room_participant_id)
    {
        if (!$chat_room_message->messageReadStatus()->exists()) {
            $chat_room_message->messageReadStatus()->create([
                'chat_room_participant_id'=>$chat_room_participant_id
            ]);
        }
    }
}