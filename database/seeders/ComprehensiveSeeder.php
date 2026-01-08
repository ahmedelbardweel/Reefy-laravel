<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Crop;
use App\Models\Task;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Post;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Expense;
use App\Models\Irrigation;
use App\Models\Negotiation;
use App\Models\ProductReview;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\UserSettings;
use App\Models\Comment;

class ComprehensiveSeeder extends Seeder
{
    public function run()
    {
        // --- 1. Create Core Users ---
        
        $farmer = User::factory()->create([
            'name' => 'المزارع أحمد',
            'email' => 'farmer@example.com',
            'password' => bcrypt('password'),
            'role' => 'farmer',
            'phone' => '01012345678',
            'address' => 'المنصورة، الدقهلية',
            'bio' => 'مزارع خبير في زراعة الخضروات العضوية بخبرة تزيد عن 20 عاماً.',
            'profile_completed' => true,
        ]);

        $client = User::factory()->create([
            'name' => 'العميل محمد',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
            'role' => 'client',
            'phone' => '01234567890',
            'address' => 'القاهرة، مصر',
            'profile_completed' => true,
        ]);

        // --- 2. Farmer's Farm Management ---

        // Crops
        $crops = [
            ['name' => 'طماطم بلدي', 'type' => 'خضروات', 'image' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400'],
            ['name' => 'خيار صويا', 'type' => 'خضروات', 'image' => 'https://images.unsplash.com/photo-1449300079323-02e209d9d3a6?w=400'],
            ['name' => 'فلفل ألوان', 'type' => 'خضروات', 'image' => 'https://images.unsplash.com/photo-1592841604418-45ec1ce5d9a4?w=400'],
        ];

        foreach ($crops as $cropData) {
            $crop = Crop::factory()->create([
                'user_id' => $farmer->id,
                'name' => $cropData['name'],
                'type' => $cropData['type'],
                'image_url' => $cropData['image'],
                'status' => 'excellent',
            ]);

            // Tasks for each crop
            Task::factory()->count(3)->create([
                'user_id' => $farmer->id,
                'crop_id' => $crop->id,
            ]);

            // Irrigation logs
            Irrigation::factory()->count(5)->create([
                'user_id' => $farmer->id,
                'crop_id' => $crop->id,
            ]);
        }

        // Inventory
        Inventory::factory()->count(5)->create([
            'user_id' => $farmer->id,
        ]);

        // Expenses
        Expense::factory()->count(10)->create([
            'user_id' => $farmer->id,
        ]);

        // --- 3. Market and Social ---

        // Products for sale
        $products = [
            ['name' => 'قفص طماطم أورجانيك', 'price' => 150, 'img' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400'],
            ['name' => 'كرتونة خيار طازج', 'price' => 80, 'img' => 'https://images.unsplash.com/photo-1449300079323-02e209d9d3a6?w=400'],
        ];

        foreach ($products as $prodData) {
            Product::factory()->create([
                'user_id' => $farmer->id,
                'name' => $prodData['name'],
                'price' => $prodData['price'],
                'image_url' => $prodData['img'],
                'category' => 'harvest',
                'is_market_listed' => true,
            ]);
        }

        // System Products (seeds, tools) - listed in market to test fallback
        Product::factory()->count(5)->create([
            'user_id' => null, // System products
            'is_market_listed' => true,
        ]);

        // Posts
        $posts = Post::factory()->count(3)->create([
            'user_id' => $farmer->id,
            'content' => 'بداية موسم حصاد الطماطم اليوم، الجودة ممتازة والحمد لله.',
        ]);

        // --- 4. Client Interactions ---

        // Social interaction
        foreach ($posts as $post) {
            Comment::create([
                'user_id' => $client->id,
                'post_id' => $post->id,
                'content' => 'بالتوفيق يا حج أحمد، محتاجين كمية للمطعم عندنا.',
            ]);
        }

        // Market interaction
        $cart = Cart::create(['user_id' => $client->id]);
        $marketProducts = Product::where('user_id', $farmer->id)->get();
        
        foreach ($marketProducts as $product) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => $product->price,
            ]);

            ProductReview::create([
                'product_id' => $product->id,
                'user_id' => $client->id,
                'rating' => 5,
                'comment' => 'منتج ممتاز وتوصيل سريع، أنصح بالتعامل معه.',
            ]);
        }

        // Chat & Negotiation
        $firstProduct = $marketProducts->first();
        $conversation = Conversation::create([
            'sender_id' => $client->id,
            'receiver_id' => $farmer->id,
            'product_id' => $firstProduct->id
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $client->id,
            'content' => 'السلام عليكم يا حاج أحمد، هل السعر نهائي للكميات الكبيرة؟',
            'created_at' => now()->subHours(2)
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $farmer->id,
            'content' => 'وعليكم السلام، ممكن نتفاوض لو هتاخد أكثر من 10 أقفاص.',
            'created_at' => now()->subHour()
        ]);

        Negotiation::create([
            'conversation_id' => $conversation->id,
            'product_id' => $firstProduct->id,
            'price' => 135,
            'quantity' => 15,
            'status' => 'pending'
        ]);

        // settings
        UserSettings::create([
            'user_id' => $farmer->id,
            'language' => 'ar'
        ]);
        UserSettings::create([
            'user_id' => $client->id,
            'language' => 'ar'
        ]);

        // --- 5. Orders (For new Sales/Orders features) ---
        
        // Active Order (Processing)
        $activeOrder = \App\Models\Order::create([
            'user_id' => $client->id,
            'total_amount' => 500, // Hardcoded for demo
            'status' => 'processing',
            'payment_status' => 'paid',
            'created_at' => now()->subDay(),
        ]);

        $p1 = $marketProducts->first();
        \App\Models\OrderItem::create([
            'order_id' => $activeOrder->id,
            'product_id' => $p1->id,
            'quantity' => 2,
            'price' => $p1->price,
            'total' => $p1->price * 2
        ]);

        // Past Order (Delivered)
        $pastOrder = \App\Models\Order::create([
            'user_id' => $client->id,
            'total_amount' => 120, 
            'status' => 'delivered',
            'payment_status' => 'paid',
            'created_at' => now()->subMonth(),
        ]);
        
        // Assuming there is a second product or reuse first
        $p2 = $marketProducts->count() > 1 ? $marketProducts->get(1) : $p1;
        \App\Models\OrderItem::create([
            'order_id' => $pastOrder->id,
            'product_id' => $p2->id,
            'quantity' => 1,
            'price' => $p2->price,
            'total' => $p2->price
        ]);
    }
}
