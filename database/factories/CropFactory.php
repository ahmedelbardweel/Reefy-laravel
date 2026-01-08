<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Crop>
 */
class CropFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $crops = [
            ['name' => 'طماطم', 'type' => 'خضروات', 'image' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400'],
            ['name' => 'خيار', 'type' => 'خضروات', 'image' => 'https://images.unsplash.com/photo-1449300079323-02e209d9d3a6?w=400'],
            ['name' => 'قمح', 'type' => 'حبوب', 'image' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=400'],
            ['name' => 'فراولة', 'type' => 'فواكه', 'image' => 'https://images.unsplash.com/photo-1464965911861-746a04b4b0a0?w=400'],
            ['name' => 'بطاطس', 'type' => 'خضروات', 'image' => 'https://images.unsplash.com/photo-1518977676601-b53f82a6b69d?w=400'],
        ];

        $selectedCrop = $this->faker->randomElement($crops);
        $plantingDate = $this->faker->dateTimeBetween('-3 months', 'now');

        return [
            'user_id' => User::factory(), // Will be overridden
            'name' => $selectedCrop['name'],
            'type' => $selectedCrop['type'],
            'planting_date' => $plantingDate,
            'harvest_date' => (clone $plantingDate)->modify('+3 months'),
            'status' => $this->faker->randomElement(['excellent', 'good', 'warning', 'infected']),
            'water_level' => $this->faker->numberBetween(20, 90),
            'image_url' => $selectedCrop['image'],
            'field_name' => 'حقل ' . $this->faker->numberBetween(1, 4),
        ];
    }
}
