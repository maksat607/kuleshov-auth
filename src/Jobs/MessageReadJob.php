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
    protected $model;
    protected $type;

    public function __construct($userId, $model)
    {
        $this->userId = $userId;
        $this->model = $model;
        $this->type = class_basename($model);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            match ($this->type) {
                'ChatRoomMessage' => $this->handleChatRoomMessage(),
                'ChatRoom' => $this->handleChatRoom(),
                default => null
            };
        });
    }

    private function handleChatRoomMessage()
    {
        $participant = (new AddParticipantAction())->execute($this->model->chatRoom, $this->userId);
        (new MessageReadAction())->execute($this->model, $participant->id);
    }

    private function handleChatRoom()
    {
        $participant = (new AddParticipantAction())->execute($this->model, $this->userId);
        $this->model->messages()->where('user_id', $this->userId)->get()
            ->each(fn($message) =>
            (new MessageReadAction())->execute($message, $participant->id)
            );
    }
}
