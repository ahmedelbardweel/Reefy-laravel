<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $products = [
            ['name' => 'طماطم بلدي', 'cat' => 'harvest', 'price' => 50, 'img' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400'],
            ['name' => 'سماد عضوي', 'cat' => 'fertilizers', 'price' => 120, 'img' => 'https://images.unsplash.com/photo-1628352081506-83c43123ed6d?w=400'],
            ['name' => 'محبس ري', 'cat' => 'equipment', 'price' => 15, 'img' => 'https://plus.unsplash.com/premium_photo-1664302148512-d5cb8d2b7405?w=400'],
            ['name' => 'بذور قمح', 'cat' => 'seeds', 'price' => 200, 'img' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=400'],
        ];

        $p = $this->faker->randomElement($products);

        return [
            'user_id' => null, // null = system, or override with User ID
            'name' => $p['name'],
            'category' => $p['cat'],
            'price' => $p['price'],
            'stock_quantity' => $this->faker->numberBetween(10, 500),
            'description' => $this->faker->paragraph(),
            'image_url' => $p['img'],
            'is_market_listed' => false,
        ];
    }
}
