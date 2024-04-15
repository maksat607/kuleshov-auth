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
        if (ChatRoomMessageReadStatus::where('chat_room_message_id',$chat_room_message->id)->where('chat_room_participant_id',$chat_room_participant_id)->count()==0) {
            ChatRoomMessageReadStatus::create([
                'chat_room_participant_id'=>$chat_room_participant_id,
                'chat_room_message_id'=>$chat_room_message->id
            ]);
        }
    }
}