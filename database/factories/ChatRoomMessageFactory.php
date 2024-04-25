<?php

namespace Maksatsaparbekov\KuleshovAuth\factories;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoomMessage;
use Maksatsaparbekov\KuleshovAuth\Models\FakeUser;

class ChatRoomMessageFactory extends BaseFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChatRoomMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'chat_room_id' => ChatRoom::factory()->create()->id,
            'user_id' => FakeUser::factory()->create()->id,
            'content' => fake()->sentence,
            'type' => 'text',
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}