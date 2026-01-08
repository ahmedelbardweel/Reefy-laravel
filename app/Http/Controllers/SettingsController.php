<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\UserSettings;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = UserSettings::firstOrCreate(
            ['user_id' => $user->id],
            [
                'language' => 'ar',
                'units' => 'metric',
                'weather_alerts' => true,
                'irrigation_reminders' => true,
                'crop_updates' => false,
            ]
        );

        return view('settings.index', compact('user', 'settings'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('settings.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'farm_name' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        // Assuming you have a farm_name field in users table, otherwise skip
        // $user->farm_name = $request->farm_name;
        $user->save();

        return redirect()->route('settings.index')->with('success', __('messages.profile_updated'));
    }

    public function editPassword()
    {
        return view('settings.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('messages.password_incorrect')]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('settings.index')->with('success', __('messages.password_changed'));
    }

    public function updatePreferences(Request $request)
    {
        $request->validate([
            'type' => 'required|in:language,units',
            'value' => 'required|string',
        ]);

        $settings = UserSettings::firstOrCreate(['user_id' => Auth::id()]);
        
        if ($request->type === 'language') {
            $settings->language = $request->value;
            Session::put('locale', $request->value);
        } elseif ($request->type === 'units') {
            $settings->units = $request->value;
        }
        
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => __('messages.saved'),
            'reload' => $request->type === 'language' // Reload page if language changed
        ]);
    }

    public function toggleNotification(Request $request)
    {
        $request->validate([
            'type' => 'required|in:weather_alerts,irrigation_reminders,crop_updates',
            'value' => 'required|boolean',
        ]);

        $settings = UserSettings::firstOrCreate(['user_id' => Auth::id()]);
        $settings->{$request->type} = $request->value;
        $settings->save();

        return response()->json([
            'success' => true,
            'value' => $settings->{$request->type}
        ]);
    }
}
