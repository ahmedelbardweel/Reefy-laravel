<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Get or Create user cart
    private function getCart()
    {
        return Cart::firstOrCreate(
            ['user_id' => Auth::id()]
        );
    }

    public function index()
    {
        $cart = $this->getCart();
        $cart->load('items.product.user'); // user = seller
        
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $cart = $this->getCart();
        $product = Product::findOrFail($request->product_id);
        
        // Prevent adding your own product
        if ($product->user_id == Auth::id()) {
            return back()->with('error', 'لا يمكنك شراء منتجاتك الخاصة');
        }

        // Check if item exists in cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        
        $requestedQuantity = $request->input('quantity', 1);
        $currentQuantity = $cartItem ? $cartItem->quantity : 0;
        $totalQuantity = $currentQuantity + $requestedQuantity;

        if ($totalQuantity > $product->stock_quantity) {
             return back()->with('error', 'الكمية المطلوبة أكبر من المتوفر (' . $product->stock_quantity . ' كيلو)');
        }

        if ($cartItem) {
            $cartItem->quantity = $totalQuantity;
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $requestedQuantity
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'تمت الإضافة للسلة');
    }

    public function remove(CartItem $item)
    {
        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }
        
        $item->delete();
        return back()->with('success', 'تم حذف العنصر');
    }

    public function checkout()
    {
        $cart = $this->getCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
             return back()->with('error', 'السلة فارغة');
        }

        // DB Transaction to ensure consistency
        \Illuminate\Support\Facades\DB::transaction(function () use ($cart) {
            // Group items by Seller (Product Owner)
            $itemsBySeller = $cart->items->groupBy(function($item) {
                return $item->product->user_id;
            });

            foreach ($itemsBySeller as $sellerId => $items) {
                // Calculate total for this seller's order
                $orderTotal = $items->sum(function($item) { 
                    return $item->quantity * $item->product->price; 
                });

                // Create Order for this Seller
                $order = \App\Models\Order::create([
                    'user_id' => Auth::id(), // Buyer
                    // We could store seller_id here if we added a column, but for now we infer it from items
                    'total_amount' => $orderTotal,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'notes' => 'Generated from Cart Checkout'
                ]);

                foreach ($items as $item) {
                    $product = $item->product;
                    
                    if ($product->stock_quantity >= $item->quantity) {
                        $product->decrement('stock_quantity', $item->quantity);
                        
                        \App\Models\OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $item->quantity,
                            'price' => $product->price,
                            'total' => $item->quantity * $product->price
                        ]);
                    }
                }
            }

            // Clear Cart
            $cart->items()->delete();
        });

        return redirect()->route('orders.index')->with('success', 'تم الشراء بنجاح! تم إنشاء الطلب.');

        return redirect()->route('market.index')->with('success', 'تم الشراء بنجاح! تم خصم الكمية من المخزون.');
    }
}
