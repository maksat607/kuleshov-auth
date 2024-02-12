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

        if (auth()->id() !== $chatRoom->chattable?->user_id) {
            (new MessageReadAction())->execute($message->id, $userId);
        }
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