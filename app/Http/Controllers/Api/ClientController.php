<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Get client dashboard data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get client statistics
        $stats = [
            'saved_items' => 12, // Example value - you can implement saved items feature later
            'active_orders' => Order::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->count(), 
        ];

        // Fetch recent products for the feed
        $recentProducts = Product::with('user')
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'recent_products' => $recentProducts
        ]);
    }
}
