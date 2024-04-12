<?php

namespace Maksatsaparbekov\KuleshovAuth\Observers;

use App\Models\Firebase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Maksatsaparbekov\KuleshovAuth\Jobs\MessageReadJob;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomMessage;
use Maksatsaparbekov\KuleshovAuth\Notifications\FirebasePush;
use Maksatsaparbekov\KuleshovAuth\Services\FirebasePushService;

class ChatRoomMessageObserver
{

    public function retrieved(ChatRoomMessage $message)
    {
        if (request()->user() && Route::currentRouteName() === 'viewChatMessagesForGivenChatRoom' && !$message->messageReadStatus) {
            $userId = request()->user()->id;
            MessageReadJob::dispatch($userId, $message)->delay(now()->addSeconds(5));
        }
    }

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

//
//        foreach ($participants as $participant) {
//            if (auth()->id() != $participant) {
//                $firebase->getReference("users/$participant/messages")->set(['test' => $messageData]);
//            }
//        }
//
//        $tokens = Firebase::whereIn('user_id', $participants)->pluck('firebase')->toArray();
//        (new FirebasePushService())->send($push, $tokens);

    }
}