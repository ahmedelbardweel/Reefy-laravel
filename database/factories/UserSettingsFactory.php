<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSettingsFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'language' => $this->faker->randomElement(['ar', 'en']),
            'units' => 'metric',
            'weather_alerts' => true,
            'irrigation_reminders' => true,
            'crop_updates' => true,
        ];
    }
}
