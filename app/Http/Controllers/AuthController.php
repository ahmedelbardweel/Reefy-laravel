<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Web: Show Auth View
    public function index()
    {
        return view('auth.index');
    }

    // Web: Show Register View
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // API & Web: Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:farmer,client',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput()->with('auth_mode', 'register');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);
        }

        Auth::login($user);
        
        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('profile.setup');
    }

    // API & Web: Login
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Invalid login details'], 401);
            }
            return back()->with('error', 'Invalid login details')->withInput()->with('auth_mode', 'login');
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (!$user->profile_completed) {
            return redirect()->route('profile.setup');
        }

        return redirect()->route('dashboard');
    }

    // API & Web: Logout
    public function logout(Request $request)
    {
        // Revoke token if API
        if ($request->user()) {
             $request->user()->tokens()->delete();
        }

        Auth::guard('web')->logout();

        if ($request->wantsJson()) {
             return response()->json(['message' => 'Logged out successfully']);
        }

        return redirect()->route('login');
    }
}
