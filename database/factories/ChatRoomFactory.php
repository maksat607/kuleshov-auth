<?php

namespace Maksatsaparbekov\KuleshovAuth\Database\Factories;

use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;
use Maksatsaparbekov\KuleshovAuth\Models\Chattable;
use Maksatsaparbekov\KuleshovAuth\Models\FakeUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatRoomFactory extends Factory
{
    protected $model = ChatRoom::class;

    public function definition()
    {
        return [
            'sender_id' => FakeUser::factory()->create()->id,
            'chattable_id' => Chattable::factory()->create()->id,
            'chattable_type' => 'Maksatsaparbekov\KuleshovAuth\Models\Chattable',
        ];
    }

}