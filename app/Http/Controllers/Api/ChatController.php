<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $conversations = Conversation::where('sender_id', $request->user()->id)
             ->orWhere('receiver_id', $request->user()->id)
             ->with(['sender', 'receiver', 'product', 'lastMessage'])
             ->orderBy('updated_at', 'desc')
             ->get();

        return ConversationResource::collection($conversations);
    }

    // Start a conversation for a product
    public function start(Request $request, \App\Models\Product $product)
    {
        if ($product->user_id == $request->user()->id) {
            return response()->json(['message' => 'Cannot chat with yourself'], 400);
        }

        $conversation = Conversation::firstOrCreate([
            'sender_id' => $request->user()->id,
            'receiver_id' => $product->user_id,
            'product_id' => $product->id,
        ]);
        
        return new ConversationResource($conversation->load(['sender', 'receiver', 'product']));
    }

    public function show(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        return MessageResource::collection($messages);
    }

    // Start a conversation with a specific user
    public function startWithUser(Request $request, $otherUser)
    {
        // Find the other user
        $otherUserModel = \App\Models\User::findOrFail($otherUser);
        
        if ($otherUserModel->id == $request->user()->id) {
            return response()->json(['message' => 'Cannot chat with yourself'], 400);
        }

        // Find or create conversation between these two users (without product)
        $conversation = Conversation::where(function($query) use ($request, $otherUserModel) {
            $query->where('sender_id', $request->user()->id)
                  ->where('receiver_id', $otherUserModel->id);
        })->orWhere(function($query) use ($request, $otherUserModel) {
            $query->where('sender_id', $otherUserModel->id)
                  ->where('receiver_id', $request->user()->id);
        })->whereNull('product_id')->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $request->user()->id,
                'receiver_id' => $otherUserModel->id,
                'product_id' => null,
            ]);
        }
        
        return new ConversationResource($conversation->load(['sender', 'receiver']));
    }

    public function send(Request $request, Conversation $conversation)
    {
        $this->authorize('update', $conversation);
        
        $validated = $request->validate(['content' => 'required|string']);
        
        $message = $conversation->messages()->create([
             'user_id' => $request->user()->id,
             'content' => $validated['content']
        ]);
        
        $conversation->touch(); // Updated at
        
        return new MessageResource($message);
    }
}
