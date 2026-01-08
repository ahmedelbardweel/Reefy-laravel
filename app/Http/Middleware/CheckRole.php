<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // If user has the required role, allow access
        if ($user->role === $role) {
            return $next($request);
        }

        // Redirect to appropriate dashboard if role mismatch
        if ($user->role === 'farmer') {
            return redirect()->route('dashboard');
        } elseif ($user->role === 'client') {
            return redirect()->route('client.dashboard');
        }

        return redirect('/');
    }
}
