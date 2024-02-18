<?php

namespace Maksatsaparbekov\KuleshovAuth\Observers;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomMessage;
use Maksatsaparbekov\KuleshovAuth\Notifications\FirebasePush;
use Maksatsaparbekov\KuleshovAuth\Services\FirebasePushService;

class ChatRoomMessageObserver
{
    public function created(ChatRoomMessage $message)
    {
        $push = (new FirebasePush())->setTitle('Message created')->setBody('some One wrote message to you')->setData(["user_id" => 0]);
        (new FirebasePushService())->send($push,['c2T6LMRnRR6xtQmjerd6cX:APA91bE82aNc_9iCu0Vprfj--1Z97J_1ps3CoN1M48gUqw83MAaZdAJJ99oHnysZm-nLlisDOMIy4XNLNSf2XsVBSCfB0R8A79Wc6pjf08rDtzbRjH_ECyku0Kekq4mH2qzS0RhlMlvd']);
    }
}