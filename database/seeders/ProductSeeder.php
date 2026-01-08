<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'بذور طماطم هجينة F1',
                'category' => 'seeds',
                'price' => 150.00,
                'stock_quantity' => 50,
                'description' => 'إنتاج عالي، مقاوم للأمراض، مناسب للبيوت المحمية والمكشوفة',
                'image_url' => 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&q=80&w=400', // Placeholder
            ],
            [
                'name' => 'سماد يوريا 46%',
                'category' => 'fertilizers',
                'price' => 85.00,
                'stock_quantity' => 100,
                'description' => 'سماد نيتروجيني عالي التركيز لتعزيز النمو الخضري',
                'image_url' => 'https://images.unsplash.com/photo-1628352081506-83c43123ed6d?auto=format&fit=crop&q=80&w=400',
            ],
            [
                'name' => 'طقم أدوات زراعية يدوية',
                'category' => 'tools',
                'price' => 120.00,
                'stock_quantity' => 30,
                'description' => 'مجموعة متكاملة من 5 قطع من الستيل المقاوم للصدأ',
                'image_url' => 'https://images.unsplash.com/photo-1617576683096-00fc8eecb375?auto=format&fit=crop&q=80&w=400',
            ],
            [
                'name' => 'شبكة ري بالتنقيط 50 متر',
                'category' => 'equipment',
                'price' => 300.00,
                'stock_quantity' => 10,
                'description' => 'تكفي 3 خطوط فقط في المستودع',
                'image_url' => 'https://images.unsplash.com/photo-1563514227149-5634d4afeaa0?auto=format&fit=crop&q=80&w=400',
            ],
             [
                'name' => 'بذور خيار',
                'category' => 'seeds',
                'price' => 45.00,
                'stock_quantity' => 120,
                'description' => 'بذور خيار بلدي سريعة النمو',
                'image_url' => 'https://images.unsplash.com/photo-1449300079323-02e209d9d3a6?auto=format&fit=crop&q=80&w=400',
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
