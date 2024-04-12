<?php

namespace Maksatsaparbekov\KuleshovAuth\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Maksatsaparbekov\KuleshovAuth\Actions\AddParticipantAction;
use Maksatsaparbekov\KuleshovAuth\Actions\MessageReadAction;

class MessageReadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $chatRoomMessage;

    public function __construct($userId, $chatRoomMessage)
    {
        $this->userId = $userId;
        $this->chatRoomMessage = $chatRoomMessage;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $participant = (new AddParticipantAction())->execute($this->chatRoomMessage->chatRoom, $this->userId);
            (new MessageReadAction())->execute($this->chatRoomMessage, $participant->id);
        });
    }
}
