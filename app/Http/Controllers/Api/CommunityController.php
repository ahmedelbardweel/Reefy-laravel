<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with(['user', 'comments.user'])
             ->withCount(['likes', 'comments'])
             ->latest()
             ->paginate(15);
        return PostResource::collection($posts);
    }
    
    public function myProfile(Request $request)
    {
        $user = $request->user();
        $posts = $user->posts()
            ->with(['user', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(15);
            
        return response()->json([
            'user' => new \App\Http\Resources\UserResource($user),
            'posts' => PostResource::collection($posts),
            'stats' => [
                'posts_count' => $user->posts()->count(),
                // 'likes_received' => $user->posts()->withCount('likes')->get()->sum('likes_count') // Optional extra
            ]
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
             'content' => 'required|string|max:1000',
             'type' => 'nullable|in:general,question,tip,marketplace',
             'image' => 'nullable|image|max:2048'
        ]);
        
        if ($request->hasFile('image')) {
             $validated['image'] = $request->file('image')->store('posts', 'public');
        }
        
        // Default type to 'general' if not provided
        $validated['type'] = $validated['type'] ?? 'general';
        
        $post = $request->user()->posts()->create($validated);
        
        return new PostResource($post->load('user'));
    }
    
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user']);
        $post->loadCount(['likes', 'comments']);
        return new PostResource($post);
    }
    
    public function like(Request $request, Post $post)
    {
        $post->toggleLike($request->user());
        return response()->json(['message' => 'Like status toggled', 'likes_count' => $post->likes()->count()]);
    }
    
    public function storeComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:500' 
        ]);
        
        $comment = $post->comments()->create([
             'user_id' => $request->user()->id,
             'content' => $validated['content']
        ]);
        
        return new CommentResource($comment->load('user'));
    }
    
    public function likeComment(Request $request, Comment $comment)
    {
        // Assuming comments can be liked too
        // $comment->toggleLike($request->user());
        return response()->json(['message' => 'Comment like not implemented in model yet'], 501);
    }
}
