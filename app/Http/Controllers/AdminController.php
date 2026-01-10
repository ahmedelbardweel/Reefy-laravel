<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Crop;
use App\Models\Task;
use App\Models\Post;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index()
    {
        // Gather system statistics
        $stats = [
            'farmers_count' => User::where('role', 'farmer')->count(),
            'crops_count' => Crop::count(),
            'tasks_pending' => Task::where('status', 'pending')->count(),
            'tasks_completed' => Task::where('status', 'completed')->count(),
            'total_users' => User::count(),
        ];

        // Get recent farmers
        $recent_farmers = User::where('role', 'farmer')
                              ->latest()
                              ->take(5)
                              ->get();

        return view('admin.dashboard', compact('stats', 'recent_farmers'));
    }

    /**
     * Store new advice/tip directly from Admin.
     */
    public function storeAdvice(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'type' => 'tip', // This will appear as a "Tip" or "Advice" in the feed
            'likes_count' => 0,
            'comments_count' => 0,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'تم نشر النصيحة للمزارعين بنجاح');
    }

    /**
     * Delete a user (Moderation).
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $user->delete();

        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }
}
