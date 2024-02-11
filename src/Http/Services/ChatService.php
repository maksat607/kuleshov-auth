<?php
namespace Maksatsaparbekov\KuleshovAuth\Http\Services;
use Maksatsaparbekov\KuleshovAuth\Actions\AddParticipantAction;
use Maksatsaparbekov\KuleshovAuth\Actions\CreateChatRoomAction;
use Maksatsaparbekov\KuleshovAuth\Actions\SendMessageAction;

class ChatService
{
    public function create($model, $userId, $content,$type)
    {
        DB::beginTransaction();
        try {
            $chatRoom = (new CreateChatRoomAction())->execute($model);
            $participant = (new AddParticipantAction())->execute($chatRoom,$userId);
            $message = (new SendMessageAction())->execute($chatRoom, $userId, $content,$type);
            DB::commit();
            return $message;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}