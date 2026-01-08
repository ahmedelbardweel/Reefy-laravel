<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Negotiation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $allConversations = Conversation::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->orWhere('receiver_id', $user->id);
        })
        ->with(['sender', 'receiver', 'product', 'messages']) // Eager load messages for preview
        ->latest()
        ->get();

        $negotiations = $allConversations->whereNotNull('product_id');
        $chats = $allConversations->whereNull('product_id');

        if ($user->role === 'client') {
            return view('chat.client_show', compact('negotiations', 'chats'));
        }
        return view('chat.index', compact('negotiations', 'chats'));
    }

    public function start(Product $product)
    {
        $user = Auth::user();
        
        // Check if product is a system product (no owner)
        if (!$product->user_id) {
            return back()->with('error', 'لا يمكن التفاوض على منتجات النظام. يرجى الشراء مباشرة.');
        }
        
        if ($product->user_id == $user->id) {
            return back()->with('error', 'لا يمكنك التفاوض مع نفسك');
        }

        // Check if conversation exists for this product between these users
        $conversation = Conversation::where('product_id', $product->id)
            ->where(function ($q) use ($user, $product) {
                $q->where(function ($q2) use ($user, $product) {
                    $q2->where('sender_id', $user->id)
                       ->where('receiver_id', $product->user_id);
                })->orWhere(function ($q2) use ($user, $product) {
                    $q2->where('sender_id', $product->user_id)
                       ->where('receiver_id', $user->id);
                });
            })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $user->id,
                'receiver_id' => $product->user_id,
                'product_id' => $product->id
            ]);
        }

        // Initialize Negotiation if not exists
        if (!$conversation->negotiation) {
            $conversation->negotiation()->create([
                'product_id' => $product->id,
                'price' => $product->price,
                'quantity' => 1,
                'status' => 'pending'
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    public function startWithUser(\App\Models\User $otherUser)
    {
        $user = Auth::user();

        if ($otherUser->id == $user->id) {
            return back();
        }

        // Check for generic conversation (null product_id)
        $conversation = Conversation::whereNull('product_id')
            ->where(function ($q) use ($user, $otherUser) {
                $q->where(function ($q2) use ($user, $otherUser) {
                    $q2->where('sender_id', $user->id)
                       ->where('receiver_id', $otherUser->id);
                })->orWhere(function ($q2) use ($user, $otherUser) {
                    $q2->where('sender_id', $otherUser->id)
                       ->where('receiver_id', $user->id);
                });
            })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $user->id,
                'receiver_id' => $otherUser->id,
                'product_id' => null
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    public function show(Conversation $conversation)
    {
        $this->authorizeAccess($conversation);
        
        $conversation->load(['messages.user', 'product', 'product.user', 'negotiation']);
        
        // Mark messages as read? (Simplified for now)
        
        if (request()->wantsJson()) {
            return response()->json([
                'messages' => $conversation->messages,
                'negotiation' => $conversation->negotiation
            ]);
        }
        
        if (Auth::user()->role === 'client') {
            return view('chat.client_show', compact('conversation'));
        }
        return view('chat.show', compact('conversation'));
    }

    public function send(Request $request, Conversation $conversation)
    {
        $this->authorizeAccess($conversation);
        
        $request->validate(['content' => 'required|string']);

        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('user')
            ]);
        }

        return back();
    }

    private function authorizeAccess(Conversation $conversation)
    {
        if ($conversation->sender_id !== Auth::id() && $conversation->receiver_id !== Auth::id()) {
            abort(403);
        }
    }
}
