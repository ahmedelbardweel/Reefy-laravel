<?php

namespace App\Http\Controllers;

use App\Models\Negotiation;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NegotiationController extends Controller
{
    public function update(Request $request, Negotiation $negotiation)
    {
        // Ensure user is part of the conversation
        $this->authorizeAccess($negotiation);

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1'
        ]);

        $negotiation->update([
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'status' => 'pending' // Reset status if it was accepted/rejected
        ]);

        return response()->json([
            'success' => true, 
            'negotiation' => $negotiation
        ]);
    }

    public function confirm(Request $request, Negotiation $negotiation)
    {
        $this->authorizeAccess($negotiation);

        // Update status to accepted
        $negotiation->update(['status' => 'accepted']);

        // Determine who is the buyer/client
        // The sender of the conversation is usually the client (User A starts chat with User B about product)
        // Or we check who is NOT the product owner
        $client = $negotiation->conversation->sender_id === $negotiation->product->user_id 
                  ? $negotiation->conversation->receiver 
                  : $negotiation->conversation->sender;

        // Add to Client's Cart
        $cart = Cart::firstOrCreate(['user_id' => $client->id]);

        $cartItem = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $negotiation->product_id,
            ],
            [
                'quantity' => $negotiation->quantity,
                'price' => $negotiation->price 
            ]
        );

        return back()->with('success', 'تم تثبيت الاتفاق وإضافة المنتج لسلة العميل بنجاح');
    }

    private function authorizeAccess(Negotiation $negotiation)
    {
        $user = Auth::user();
        $conversation = $negotiation->conversation;
        
        if ($conversation->sender_id !== $user->id && $conversation->receiver_id !== $user->id) {
            abort(403);
        }
    }
}
