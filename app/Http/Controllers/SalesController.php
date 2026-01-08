<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index()
    {
        // Find orders that contain products belonging to the authenticated farmer
        // Since we split orders by seller now, an order should belong wholly to one seller (mostly).
        // But to be safe and robust, we query orders where *at least one* item product belongs to auth user.
        $orders = Order::whereHas('items.product', function($q) {
            $q->where('user_id', Auth::id());
        })
        ->with(['items.product', 'user']) // Load items and the Buyer (user)
        ->latest()
        ->paginate(10);

        return view('sales.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Verify ownership/permission: The user must be the seller of the products in this order.
        $isSeller = $order->items()->whereHas('product', function($q) {
            $q->where('user_id', Auth::id());
        })->exists();

        if (!$isSeller) {
            abort(403, 'Unauthorized access to this order');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}
