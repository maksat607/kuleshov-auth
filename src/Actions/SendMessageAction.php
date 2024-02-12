<?php

namespace Maksatsaparbekov\KuleshovAuth\Actions;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;

class SendMessageAction
{
    public function execute(ChatRoom $chatRoom, $userId, $content)
    {
        $message = $chatRoom->messages()->create([
            'user_id' => $userId,
            'type' => $this->manageFile() ? 'file' : 'text',
            'content' => $content,
        ]);

        return $message;
    }

    public function manageFile()
    {
        if (request()->hasFile('file')) {
            $file = request()->file('file');
//            $path = $file->store('store');
        }
        return false;
    }
}