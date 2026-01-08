<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class NegotiationFactory extends Factory
{
    public function definition()
    {
        return [
            'conversation_id' => Conversation::factory(),
            'product_id' => Product::factory(),
            'price' => $this->faker->numberBetween(10, 500),
            'quantity' => $this->faker->numberBetween(1, 50),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}
