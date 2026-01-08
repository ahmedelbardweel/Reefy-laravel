<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Assuming you have a Product model
use App\Models\Order;   // Assuming you have an Order model

class ClientController extends Controller
{
    public function index()
    {
        // Mock data or actual logic for the client dashboard
        $stats = [
            'saved_items' => 12, // Example value
            'active_orders' => Order::where('user_id', auth()->id())->where('status', 'pending')->count(), 
        ];

        // Fetch recent products for the feed
        $recentProducts = Product::with('user')->latest()->take(10)->get();

        return view('client.dashboard', compact('stats', 'recentProducts'));
    }
}
