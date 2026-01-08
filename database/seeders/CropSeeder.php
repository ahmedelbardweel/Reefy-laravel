<?php

namespace Database\Seeders;

use App\Models\Crop;
use Illuminate\Database\Seeder;

class CropSeeder extends Seeder
{
    public function run()
    {
        $crops = [
            [
                'name' => 'قمح ذهبي',
                'type' => 'حبوب',
                'planting_date' => now()->subDays(60),
                'harvest_date' => now()->addDays(30),
                'status' => 'excellent',
                'water_level' => 92,
                'field_name' => 'الحقل ٣',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBguVm4UsfSgqyTEeh5s4b1UhP8yozltZT52GquKg7V-MCtzXIP7imvaQJY25oeqpg1mdpmHncBmNpu0rH4xNseJUAiy1NPmwaP1ecNJ1e8n6h2Te0QL3cwdmC4m1CvPhimSk7eVUF25LqhNMhs6GHTCsJy9w5CWujUwzYcDoWd6fVfzcn78fg73tlxPClIXh6pbDr1LT1kkQhLjn-icFSPznoJeBrk-EwZjRnw38oQgZPZV6TZ_QcvRJvW0x6y51nPsXGh1x5GFNQ'
            ],
            [
                'name' => 'طماطم روما',
                'type' => 'خضروات',
                'planting_date' => now()->subDays(45),
                'harvest_date' => now()->addDays(15),
                'status' => 'warning',
                'water_level' => 65,
                'field_name' => 'الحقل ١',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAuqs0HaHwP98NGfrFd0X5onDegD4YHQt3nbCin6UPw7FGR-VlWDCYgNFlhUUwfLbY0VWthOFItq5Mj_HZHleRTWUXHOZE9zVC3GpNVUiDcsdhcgh4pWiLBiQZxpGR4h8rgF9AF96iuN65R5mHGN-57NFHSxOeALuMbTJ1XMNWRads1EFpdy-TCa5BAKXKRIIoUYUqaALARQU6Qo-Zdfx1alSXblZYmXPhPYvUCfdpxR1yVsNs_-dbtW0QNkFeadGcs8CUX-4vuPuw'
            ],
            [
                'name' => 'نخيل تمر',
                'type' => 'فواكه',
                'planting_date' => now()->subMonths(6),
                'harvest_date' => now()->addMonths(2),
                'status' => 'good',
                'water_level' => 88,
                'field_name' => 'الحقل ٥',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDzfro53qYvHO3hX7XQstXOv5Om1tKPecjcqc54p19Xa62bY5h_FC3ZQl1T-DceVxKUXMr6VryVYJaPgifU9jBQCwMmvjT8N5YvHfeTwzpgGss_1Zu3LCVPg-w9oymk10YUnTWbF1AjSsT8ZDg703rShS0EyfJt-8T61R0IiihoXjCBmGLrzyP0NS62PNB_vLsgstOlIF4eKk71xxN-sqCw36F-cgiJAQsTL1LzqkRsEgCzEn0QX_n4nwInvMVmVrMbeNf5oBk5Vfs'
            ],
            [
                'name' => 'ذرة صفراء',
                'type' => 'حبوب',
                'planting_date' => now()->subDays(75),
                'harvest_date' => now()->addDays(5),
                'status' => 'excellent',
                'water_level' => 95,
                'field_name' => 'الحقل ٢',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDq9SGFi2uuwgi2ssW4mUxjKQeEcsBc_0AeV-3BPlsc3C4veQi8eQ0D9uMLJPn76vWNtTCQaQ0fNkt5tlSqpeDT2Du2_41r6ucXVUB44zVC9GJ5g7wQdG8Km-XXHBgiZYZxCj6kCEl0la5klCYjEELSHaJzQK1Iq-ISv8rmYaZkygYd06H3oCGnOtVBvc5xgMuEV9ZctiEl4QZkirUXE24tg8x_pFUoq8isvixlDOK2WjOL-sFTBlUD8VPxLlp4glf398y-Sr6uegw'
            ],
            [
                'name' => 'خيار طازج',
                'type' => 'خضروات',
                'planting_date' => now()->subDays(30),
                'harvest_date' => now()->addDays(25),
                'status' => 'good',
                'water_level' => 78,
                'field_name' => 'البيت المحمي أ',
                'image_url' => null
            ]
        ];

        foreach ($crops as $crop) {
            Crop::create($crop);
        }
    }
}
