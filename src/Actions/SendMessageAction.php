<?php

namespace Maksatsaparbekov\KuleshovAuth\Actions;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;

class SendMessageAction
{
    public function execute(ChatRoom $chatRoom, $userId, $content)
    {
        $data = [
            'user_id' => $userId,
            'type' => 'text',
            'content' => $content,
        ];
        if (request()->hasFile('content')) {
            $data = [
                'user_id' => $userId,
                'type' => 'file',
                'content' => $this->manageFile()
            ];
        }
        $message = $chatRoom->messages()->create($data);

        return $message;
    }

    public function manageFile()
    {
        $file = request()->file('content');
        return $file->store('store');
    }
}