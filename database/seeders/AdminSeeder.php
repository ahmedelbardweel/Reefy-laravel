<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User (No crops or tasks - Admin only manages users)
        User::create([
            'name' => 'أحمد المدير',
            'email' => 'admin@reefy.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '0599123456',
            'profile_completed' => true,
        ]);

        echo "✅ Admin created: admin@reefy.com / admin123\n";
    }
}
