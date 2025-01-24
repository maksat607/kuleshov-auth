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
        if ($this->type == 'ChatRoomMessage') {
            DB::transaction(function () {
                $participant = (new AddParticipantAction())->execute($this->model->chatRoom, $this->userId);
                (new MessageReadAction())->execute($this->model, $participant->id);
            });
        }
        if ($this->type == 'ChatRoom') {
            DB::transaction(function () {
                $participant = (new AddParticipantAction())->execute($this->model, $this->userId);
                foreach ($this->model->messages()->where('user_id', $this->userId)->get() as $chatRoomMessage) {
                    (new MessageReadAction())->execute($chatRoomMessage, $participant->id);
                }
            });
        }
    }
}
