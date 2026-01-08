<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of user orders
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product.user'])
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    /**
     * Display the specified order
     *
     * @param  Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        // Ensure user owns the order
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بالوصول لهذا الطلب'
            ], 403);
        }

        $order->load(['items.product.user']);
        
        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }
}
