<?php

namespace Maksatsaparbekov\KuleshovAuth\factories;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomMessage;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomMessageReadStatus;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomParticipant;

class ChatRoomMessageReadStatusFactory extends BaseFactory
{
    protected $model = ChatRoomMessageReadStatus::class;
    public function definition()
    {
        return [
            'chat_room_message_id' => ChatRoomMessage::factory()->create()->id,
            'chat_room_participant_id' => ChatRoomParticipant::create()->id,
            'read_status' => $this->faker->boolean ? 1 : 0,
            'read_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}