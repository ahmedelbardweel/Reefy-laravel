<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function setup()
    {
        return view('profile.setup');
    }

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

        $user = auth()->user();
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

        return redirect()->route('dashboard')->with('success', 'تم تحديث الملف الشخصي بنجاح!');
    }
}
