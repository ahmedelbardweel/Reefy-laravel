<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Crop;
use Illuminate\Database\Eloquent\Factories\Factory;

class IrrigationFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'crop_id' => Crop::factory(),
            'amount_liters' => $this->faker->numberBetween(100, 5000),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
