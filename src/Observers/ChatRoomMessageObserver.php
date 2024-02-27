<?php

namespace Maksatsaparbekov\KuleshovAuth\Observers;

use App\Models\Firebase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomMessage;
use Maksatsaparbekov\KuleshovAuth\Notifications\FirebasePush;
use Maksatsaparbekov\KuleshovAuth\Services\FirebasePushService;

class ChatRoomMessageObserver
{
    public function created(ChatRoomMessage $message)
    {
        $push = (new FirebasePush())
            ->setTitle('Новое сообщение')
            ->setBody(Str::limit($message->content, 20, '...'))
            ->setData([
                'sender_id' => $message->user_id,
                'chat_id' => $message->chatRoom->id
            ]);
        $participants = $message->chatRoom
            ->participants()
            ->where('user_id', '!=', request()->user()->id)
            ->pluck('user_id');

        $tokens = Firebase::whereIn('user_id', $participants)->pluck('firebase')->toArray();
        (new FirebasePushService())->send($push,$tokens);

    }
}