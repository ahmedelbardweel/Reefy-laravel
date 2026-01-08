<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\UserSettings;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        // Return user settings if they exist using UserSettings model
        $settings = $request->user()->settings;
        return response()->json($settings ?: []);
    }
    
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
             'language' => 'string|in:en,ar',
             'dark_mode' => 'boolean',
             'notifications_enabled' => 'boolean'
        ]);
        
        $settings = $request->user()->settings()->updateOrCreate(
             ['user_id' => $request->user()->id],
             $validated
        );
        
        return response()->json($settings);
    }

    public function toggleNotification(Request $request)
    {
        $settings = $request->user()->settings()->firstOrCreate(['user_id' => $request->user()->id]);
        $settings->notifications_enabled = !$settings->notifications_enabled;
        $settings->save();
        
        return response()->json([
            'message' => 'Notification status updated',
            'notifications_enabled' => $settings->notifications_enabled
        ]);
    }
    
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        if (!Hash::check($validated['current_password'], $request->user()->password)) {
             return response()->json(['message' => 'Current password incorrect'], 400);
        }
        
        $request->user()->update(['password' => Hash::make($validated['password'])]);
        
        return response()->json(['message' => 'Password updated successfully']);
    }
}
