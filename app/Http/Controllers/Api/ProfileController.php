<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Get current user profile data for setup
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setup()
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    /**
     * Complete user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'age' => 'required|integer|min:18|max:100',
            'address' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'workers_count' => 'nullable|integer|min:0',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'phone' => $validated['phone'],
            'age' => $validated['age'],
            'address' => $validated['address'],
            'bio' => $validated['bio'] ?? null,
            'workers_count' => $validated['workers_count'] ?? 0,
            'facebook' => $validated['facebook'] ?? null,
            'twitter' => $validated['twitter'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'profile_completed' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الملف الشخصي بنجاح!',
            'user' => $user->fresh()
        ]);
    }
}
