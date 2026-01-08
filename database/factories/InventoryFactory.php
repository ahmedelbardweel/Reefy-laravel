<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $items = [
            ['name' => 'بذور طماطم', 'cat' => 'seeds', 'unit' => 'كيس'],
            ['name' => 'بذور خيار', 'cat' => 'seeds', 'unit' => 'كيس'],
            ['name' => 'سماد NPK', 'cat' => 'fertilizers', 'unit' => 'شكارة'],
            ['name' => 'مبيد حشري', 'cat' => 'pesticides', 'unit' => 'لتر'],
            ['name' => 'أنبوب ري', 'cat' => 'equipment', 'unit' => 'متر'],
            ['name' => 'صندوق طماطم', 'cat' => 'harvest', 'unit' => 'صندوق'],
        ];

        $item = $this->faker->randomElement($items);

        return [
            'user_id' => User::factory(),
            'name' => $item['name'],
            'category' => $item['cat'],
            'quantity_value' => $this->faker->numberBetween(5, 100),
            'unit' => $item['unit'],
            'description' => $this->faker->sentence(),
        ];
    }
}
