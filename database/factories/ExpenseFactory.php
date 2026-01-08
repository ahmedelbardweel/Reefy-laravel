<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    public function definition()
    {
        $categories = ['seeds', 'fertilizers', 'pesticides', 'labor', 'equipment', 'other'];
        $titles = [
            'seeds' => ['بذور طماطم هجين', 'بذور خيار سوبر', 'بذور فلفل الوان'],
            'fertilizers' => ['سماد يوريا', 'سماد NPK مركب', 'سماد عضوي'],
            'labor' => ['يومية عمال حصاد', 'أجر فني ري', 'مصاريف نقل'],
            'equipment' => ['صيانة جرار', 'شراء خراطيم ري', 'إصلاح طلمبة'],
        ];

        $category = $this->faker->randomElement($categories);
        $title = isset($titles[$category]) ? $this->faker->randomElement($titles[$category]) : 'مصاريف زراعية متنوعة';

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'category' => $category,
            'date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'notes' => $this->faker->sentence(),
        ];
    }
}
