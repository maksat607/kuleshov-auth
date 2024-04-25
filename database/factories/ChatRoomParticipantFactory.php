<?php

namespace Maksatsaparbekov\KuleshovAuth\Database\Factories;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomParticipant;
use Maksatsaparbekov\KuleshovAuth\Models\FakeUser;
use Illuminate\Database\Eloquent\Factories\Factory;
class ChatRoomParticipantFactory extends Factory
{
    protected $model = ChatRoomParticipant::class;

    public function definition()
    {
        return [
            'chat_room_id' => ChatRoom::factory()->create()->id,
            'user_id' =>FakeUser::factory()->create()->id,
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month', 'now')
        ];
    }

}