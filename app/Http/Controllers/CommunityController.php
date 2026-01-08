<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'all');
        
        $query = Post::with('user', 'comments', 'product')->latest();
        
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        $posts = $query->get();
        
        // Get user's products for marketplace posts
        $products = Product::where('user_id', Auth::id())->get();
        
        return view('community.index', compact('posts', 'type', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'type' => 'required|in:general,question,tip,marketplace',
            'product_id' => 'nullable|exists:products,id'
        ]);

        Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'type' => $request->type,
            'product_id' => $request->product_id,
        ]);

        return redirect()->route('community.index')->with('success', 'تم نشر المشاركة بنجاح');
    }

    public function like(Post $post)
    {
        // Simple increment for now, ideally unique like per user
        $post->increment('likes_count');
        return response()->json(['success' => true, 'likes' => $post->likes_count]);
    }

    public function myProfile()
    {
        $user = Auth::user();
        $posts = Post::with('user', 'comments')
            ->where('user_id', $user->id)
            ->latest()
            ->get();
            
        return view('community.profile', compact('posts', 'user'));
    }

    public function show(Post $post)
    {
        $post->load('user', 'comments.user', 'product');
        return view('community.show', compact('post'));
    }

    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        $post->increment('comments_count');

        return redirect()->route('community.show', $post)->with('success', 'تم إضافة التعليق بنجاح');
    }

    public function likeComment(Comment $comment)
    {
        $comment->increment('likes_count');
        return response()->json(['success' => true, 'likes' => $comment->likes_count]);
    }
}
