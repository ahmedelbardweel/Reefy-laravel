<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Crop;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class FarmerSeeder extends Seeder
{
    public function run(): void
    {
        // Create Farmer User
        $farmer = User::create([
            'name' => 'أحمد المزارع',
            'email' => 'farmer@reefy.com',
            'password' => Hash::make('farmer123'),
            'role' => 'farmer',
            'phone' => '0599654321',
            'profile_completed' => true,
            'farm_name' => 'مزرعة البركة',
            'farm_size' => 200,
            'farm_location' => 'غزة - خانيونس',
        ]);

        // Create Crops for Farmer
        $tomato = Crop::create([
            'user_id' => $farmer->id,
            'name' => 'طماطم',
            'type' => 'خضروات',
            'planting_date' => now()->subDays(30),
            'harvest_date' => now()->addDays(30),
            'status' => 'good',
            'water_level' => 75,
            'image_url' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400',
        ]);

        $cucumber = Crop::create([
            'user_id' => $farmer->id,
            'name' => 'خيار',
            'type' => 'خضروات',
            'planting_date' => now()->subDays(20),
            'harvest_date' => now()->addDays(40),
            'status' => 'good',
            'water_level' => 80,
            'image_url' => 'https://images.unsplash.com/photo-1604977042946-1eecc30f269e?w=400',
        ]);

        $pepper = Crop::create([
            'user_id' => $farmer->id,
            'name' => 'فلفل حلو',
            'type' => 'خضروات',
            'planting_date' => now()->subDays(25),
            'harvest_date' => now()->addDays(35),
            'status' => 'good',
            'water_level' => 70,
            'image_url' => 'https://images.unsplash.com/photo-1563565375-f3fdfdbefa83?w=400',
        ]);

        // Create Tasks for Farmer
        // Future task (will send notification)
        Task::create([
            'user_id' => $farmer->id,
            'crop_id' => $tomato->id,
            'title' => 'ري الطماطم',
            'description' => 'ري منتظم للطماطم',
            'due_date' => now()->addMinutes(5),
            'reminder_date' => now()->addMinutes(5),
            'priority' => 'high',
            'status' => 'pending',
            'category' => 'irrigation',
        ]);

        Task::create([
            'user_id' => $farmer->id,
            'crop_id' => $cucumber->id,
            'title' => 'تسميد الخيار',
            'description' => 'إضافة سماد عضوي',
            'due_date' => now()->addHours(2),
            'reminder_date' => now()->addHours(2),
            'priority' => 'medium',
            'status' => 'pending',
            'category' => 'fertilization',
        ]);

        Task::create([
            'user_id' => $farmer->id,
            'crop_id' => $pepper->id,
            'title' => 'فحص الفلفل',
            'description' => 'فحص النمو والصحة',
            'due_date' => now()->addDays(1),
            'reminder_date' => now()->addDays(1),
            'priority' => 'low',
            'status' => 'pending',
            'category' => 'inspection',
        ]);

        // Completed task
        Task::create([
            'user_id' => $farmer->id,
            'crop_id' => $tomato->id,
            'title' => 'تنظيف الأعشاب',
            'description' => 'إزالة الأعشاب الضارة',
            'due_date' => now()->subDays(1),
            'reminder_date' => now()->subDays(1),
            'priority' => 'medium',
            'status' => 'completed',
            'category' => 'other',
            'notification_sent' => true,
        ]);

        echo "✅ Farmer created: farmer@reefy.com / farmer123\n";
        echo "✅ 3 Crops created\n";
        echo "✅ 4 Tasks created (1 will notify in 5 minutes!)\n";
    }
}
