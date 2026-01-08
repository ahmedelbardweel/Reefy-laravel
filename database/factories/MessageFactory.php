<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'conversation_id' => \App\Models\Conversation::factory(),
            'user_id' => function (array $attributes) {
                return \App\Models\Conversation::find($attributes['conversation_id'])->sender_id;
            },
            'content' => $this->faker->sentence(),
            'is_read' => $this->faker->boolean,
        ];
    }
}
