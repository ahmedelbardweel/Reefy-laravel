<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Crop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $types = [
            'irrigation' => ['ري المحاصيل', 'ري الحقل رقم 1 بشكل كامل'],
            'fertilization' => ['تسميد', 'إضافة سماد عضوي للطماطم'],
            'harvest' => ['حصاد', 'حصاد ثمار الخيار الناضجة'],
            'inspection' => ['فحص دوري', 'فحص الأوراق بحثاً عن آفات'],
            'other' => ['صيانة معدات', 'صيانة شبكة الري'],
        ];

        $category = $this->faker->randomElement(array_keys($types));

        return [
            'user_id' => User::factory(),
            'crop_id' => null, // Optional
            'title' => $types[$category][0],
            'description' => $types[$category][1],
            'due_date' => $this->faker->dateTimeBetween('now', '+2 weeks'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'category' => $category,
        ];
    }
}
