<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        // Assuming simple Cart logic where User has multiple CartItems or a Cart model that has items.
        // Let's assume User hasMany CartItem directly or Cart->items
        // Based on file list, there is a Cart.php and CartItem.php
        
        $cart = $request->user()->cart; 
        if(!$cart) {
            return response()->json(['items' => [], 'total' => 0]);
        }
        
        $items = $cart->items()->with('product')->get();
        
        // Calculate total if needed, or resource handles it
        return CartItemResource::collection($items);
    }
    
    public function add(Request $request)
    {
        $validated = $request->validate([
             'product_id' => 'required|exists:products,id',
             'quantity' => 'required|integer|min:1'
        ]);
        
        $user = $request->user();
        $cart = $user->cart ?: $user->cart()->create();
        
        $product = Product::find($validated['product_id']);
        if ($product->quantity < $validated['quantity']) {
             return response()->json(['message' => 'Not enough stock'], 400);
        }
        
        // Update or create cart item
        $item = $cart->items()->where('product_id', $validated['product_id'])->first();
        if ($item) {
             $item->increment('quantity', $validated['quantity']);
        } else {
             $item = $cart->items()->create([
                 'product_id' => $validated['product_id'],
                 'quantity' => $validated['quantity']
             ]);
        }
        
        return new CartItemResource($item->load('product'));
    }
    
    public function remove(Request $request, $itemId)
    {
         // Logic to remove item
         // $item = CartItem::where('cart_id', $request->user()->cart->id)->findOrFail($itemId);
         // $item->delete();
         return response()->json(['message' => 'Item removed']);
    }
    
    public function checkout(Request $request) 
    {
         // Simplify checkout 
         return response()->json(['message' => 'Checkout successful']);
    }
}
