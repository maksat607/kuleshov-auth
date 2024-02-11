<?php

namespace Maksatsaparbekov\KuleshovAuth\Actions;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;

class SendMessageAction
{
    public function execute(ChatRoom $chatRoom, $userId, $content)
    {
        $message = $chatRoom->messages()->create([
            'user_id' => $userId,
            'type' => 'text',
            'content' => $content,
        ]);

        if (auth()->id() !== $userId) {
            (new MessageReadAction())->execute($message->id,$userId);
        }

    }
}