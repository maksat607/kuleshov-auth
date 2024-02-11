<?php

namespace Maksatsaparbekov\KuleshovAuth\Actions;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomMessageReadStatus;

class MessageReadAction
{
    /**
     * Обрабатывает действие для сообщения в чате, основываясь на ID сообщения и ID участника чата.
     *
     * @param int $chat_room_message_id ID сообщения в чате.
     * @param int $chat_room_participant_id ID участника чата.
     * @return mixed Возвращает результат выполнения метода.
     */
    public function execute(int $chat_room_message_id, int $chat_room_participant_id)
    {
        return ChatRoomMessageReadStatus::create([
            'chat_room_message_id' => $chat_room_message_id,
            'chat_room_participant_id' => $chat_room_participant_id,
            'read_status' => false,
            'read_at' => now(),
        ]);
    }
}