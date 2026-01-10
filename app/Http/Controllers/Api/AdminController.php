<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'farmers_count' => User::where('role', 'farmer')->count(), // assuming 'farmer' is default or explicit
            'total_posts' => Post::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
        ];

        return response()->json($stats);
    }

    /**
     * Post official advice to the community.
     * This uses the Post model but marks it as 'tip' (or we could add is_official).
     * For now, we rely on the User's role (admin) to style it differently on client side.
    */
    public function storeAdvice(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'user_id' => $request->user()->id,
            'content' => $request->content,
            'type' => 'tip', // 'tip' is an existing enum value
            'likes_count' => 0,
            'comments_count' => 0
        ]);

        return response()->json(['message' => 'Official advice posted', 'post' => $post]);
    }

    /**
     * Moderate Community: Delete any post.
     */
    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted by admin']);
    }
}
