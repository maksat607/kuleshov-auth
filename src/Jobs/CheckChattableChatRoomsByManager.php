<?php

namespace Maksatsaparbekov\KuleshovAuth\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckChattableChatRoomsByManager implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $chatRoom)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->chatRoom->readables()->delete();
        $count = $this->chatRoom->chattable->chatRooms()->whereHas('readables')->count();
        if ($count>0){
            $this->chatRoom->chattable->checkableStatuses()->firstOrCreate(['checked'=>0]);
        }else{
            $this->chatRoom->chattable->checkableStatuses()->delete();
        }
    }
}
