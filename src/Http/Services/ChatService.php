<?php
namespace Maksatsaparbekov\KuleshovAuth\Http\Services;
use Illuminate\Support\Facades\DB;
use Maksatsaparbekov\KuleshovAuth\Actions\AddParticipantAction;
use Maksatsaparbekov\KuleshovAuth\Actions\CreateChatRoomAction;
use Maksatsaparbekov\KuleshovAuth\Actions\SendMessageAction;

class ChatService
{
    public function create($model, $userId, $content,$type)
    {
        DB::beginTransaction();
        try {
            $chatRoom = (new CreateChatRoomAction())->execute($model,$userId);
            $participant = (new AddParticipantAction())->execute($chatRoom,$userId);
            $message = (new SendMessageAction())->execute($chatRoom, $userId, $content,$type='text');
//            (new MessageReadAction())->execute($message->id, $userId);

            DB::commit();
            return $message;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}