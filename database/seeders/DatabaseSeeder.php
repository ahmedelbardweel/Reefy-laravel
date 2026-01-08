<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Crop;
use App\Models\Task;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Irrigation;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Farmers
        $farmer1 = User::create([
            'name' => 'أحمد المزارع',
            'email' => 'farmer@example.com',
            'password' => Hash::make('password'),
            'role' => 'farmer',
            'phone' => '0599123456',
            'address' => 'نابلس، فلسطين',
            'profile_completed' => true,
        ]);

        $farmer2 = User::create([
            'name' => 'محمد الفلاح',
            'email' => 'farmer2@example.com',
            'password' => Hash::make('password'),
            'role' => 'farmer',
            'phone' => '0598765432',
            'address' => 'جنين، فلسطين',
            'profile_completed' => true,
        ]);

        // Create Clients
        $client1 = User::create([
            'name' => 'سارة العميلة',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '0597654321',
            'address' => 'رام الله، فلسطين',
            'profile_completed' => true,
        ]);

        $client2 = User::create([
            'name' => 'ليلى المشترية',
            'email' => 'client2@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '0596543210',
            'address' => 'الخليل، فلسطين',
            'profile_completed' => true,
        ]);

        // Create Crops for Farmer 1
        $crop1 = Crop::create([
            'user_id' => $farmer1->id,
            'name' => 'طماطم',
            'type' => 'خضروات',
            'planting_date' => now()->subDays(45),
            'harvest_date' => now()->addDays(15),
            'status' => 'good',
            'water_level' => 75,
            'field_name' => 'الحقل الشمالي',
            'image_url' => '/storage/crops/tomato.jpg',
        ]);

        $crop2 = Crop::create([
            'user_id' => $farmer1->id,
            'name' => 'خيار',
            'type' => 'خضروات',
            'planting_date' => now()->subDays(30),
            'harvest_date' => now()->addDays(30),
            'status' => 'excellent',
            'water_level' => 85,
            'field_name' => 'الحقل الجنوبي',
            'image_url' => '/storage/crops/cucumber.jpg',
        ]);

        $crop3 = Crop::create([
            'user_id' => $farmer1->id,
            'name' => 'قمح',
            'type' => 'حبوب',
            'planting_date' => now()->subDays(90),
            'harvest_date' => now()->addDays(60),
            'status' => 'good',
            'water_level' => 60,
            'field_name' => 'الحقل الشرقي',
            'image_url' => '/storage/crops/wheat.jpg',
        ]);

        // Create Crops for Farmer 2
        Crop::create([
            'user_id' => $farmer2->id,
            'name' => 'زيتون',
            'type' => 'أشجار',
            'planting_date' => now()->subYears(3),
            'harvest_date' => now()->addMonths(2),
            'status' => 'excellent',
            'water_level' => 70,
            'field_name' => 'البستان الغربي',
            'image_url' => '/storage/crops/olive.jpg',
        ]);

        // Create Irrigation Records
        Irrigation::create([
            'user_id' => $farmer1->id,
            'crop_id' => $crop1->id,
            'date' => now()->subDays(2),
            'amount_liters' => 150,
            'notes' => 'ري صباحي',
        ]);

        Irrigation::create([
            'user_id' => $farmer1->id,
            'crop_id' => $crop2->id,
            'date' => now()->subDays(1),
            'amount_liters' => 120,
            'notes' => 'ري مسائي',
        ]);

        // Create Tasks for Farmer 1
        Task::create([
            'user_id' => $farmer1->id,
            'crop_id' => $crop1->id,
            'title' => 'تسميد الطماطم',
            'description' => 'إضافة السماد العضوي',
            'due_date' => now()->addDays(1),
            'priority' => 'high',
            'category' => 'fertilization',
            'status' => 'pending',
        ]);

        Task::create([
            'user_id' => $farmer1->id,
            'crop_id' => $crop2->id,
            'title' => 'فحص نظام الري',
            'description' => 'التأكد من عمل الرشاشات',
            'due_date' => now()->addDays(2),
            'priority' => 'medium',
            'category' => 'irrigation',
            'status' => 'pending',
        ]);

        Task::create([
            'user_id' => $farmer1->id,
            'crop_id' => $crop1->id,
            'title' => 'حصاد الطماطم',
            'description' => 'حصاد الثمار الناضجة',
            'due_date' => now()->addDays(15),
            'priority' => 'high',
            'category' => 'harvest',
            'status' => 'pending',
        ]);

        Task::create([
            'user_id' => $farmer1->id,
            'title' => 'شراء بذور جديدة',
            'description' => 'بذور خيار ومسقعة',
            'due_date' => now()->addDays(7),
            'priority' => 'low',
            'category' => 'other',
            'status' => 'pending',
        ]);

        // Completed Task
        Task::create([
            'user_id' => $farmer1->id,
            'crop_id' => $crop3->id,
            'title' => 'ري القمح',
            'description' => 'ري الحقل الشرقي',
            'due_date' => now()->subDays(1),
            'priority' => 'medium',
            'category' => 'irrigation',
            'status' => 'completed',
            'completed_at' => now()->subHours(3),
        ]);

        // Create Inventory Items
        Inventory::create([
            'user_id' => $farmer1->id,
            'name' => 'سماد عضوي',
            'category' => 'fertilizers',
            'quantity_value' => 50,
            'unit' => 'kg',
            'description' => 'سماد طبيعي من المزرعة',
        ]);

        Inventory::create([
            'user_id' => $farmer1->id,
            'name' => 'بذور طماطم',
            'category' => 'seeds',
            'quantity_value' => 5,
            'unit' => 'kg',
            'description' => 'بذور نوعية ممتازة',
        ]);

        Inventory::create([
            'user_id' => $farmer1->id,
            'name' => 'محصول طماطم',
            'category' => 'harvest',
            'quantity_value' => 150,
            'unit' => 'kg',
            'description' => 'حصاد من الحقل الشمالي',
            'image_url' => '/storage/inventory/tomato-harvest.jpg',
        ]);

        // Create Products for Market
        Product::create([
            'user_id' => $farmer1->id,
            'name' => 'طماطم طازجة',
            'category' => 'خضروات',
            'price' => 3.5,
            'stock_quantity' => 100,
            'description' => 'طماطم عضوية طازجة من المزرعة',
            'image_url' => '/storage/products/tomato.jpg',
            'is_market_listed' => true,
        ]);

        Product::create([
            'user_id' => $farmer1->id,
            'name' => 'خيار',
            'category' => 'خضروات',
            'price' => 2.5,
            'stock_quantity' => 80,
            'description' => 'خيار طازج من المزرعة',
            'image_url' => '/storage/products/cucumber.jpg',
            'is_market_listed' => true,
        ]);

        Product::create([
            'user_id' => $farmer2->id,
            'name' => 'زيت زيتون بكر',
            'category' => 'منتجات مصنعة',
            'price' => 45,
            'stock_quantity' => 30,
            'description' => 'زيت زيتون بكر ممتاز من أشجارنا',
            'image_url' => '/storage/products/olive-oil.jpg',
            'is_market_listed' => true,
        ]);

        Product::create([
            'user_id' => $farmer2->id,
            'name' => 'زيتون مخلل',
            'category' => 'منتجات مصنعة',
            'price' => 12,
            'stock_quantity' => 50,
            'description' => 'زيتون مخلل بطريقة تقليدية',
            'image_url' => '/storage/products/pickled-olives.jpg',
            'is_market_listed' => true,
        ]);

        $this->command->info('✅ تم إنشاء البيانات بنجاح!');
        $this->command->info('📧 البريد الإلكتروني للمزارع: farmer@example.com');
        $this->command->info('📧 البريد الإلكتروني للعميل: client@example.com');
        $this->command->info('🔑 كلمة المرور لجميع الحسابات: password');
    }
}
