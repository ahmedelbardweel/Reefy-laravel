@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-24 pt-20">
    <!-- Header -->
    <header class="px-5 py-4 bg-white dark:bg-slate-900 fixed top-0 w-full max-w-md mx-auto z-30 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
        <a href="{{ route('settings.index') }}" class="text-slate-600 dark:text-slate-300 hover:text-green-600">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="text-lg font-bold font-display text-slate-900 dark:text-white">{{ __('settings.change_password') }}</h1>
    </header>

    <div class="p-5">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-3 rounded-xl mb-4 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('settings.password.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('settings.current_password') }}</label>
                <input type="password" name="current_password" required
                       class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 py-3 px-4 focus:border-green-500 focus:ring-green-500 transition-all shadow-sm">
                @error('current_password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('settings.new_password') }}</label>
                <input type="password" name="new_password" required
                       class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 py-3 px-4 focus:border-green-500 focus:ring-green-500 transition-all shadow-sm">
                @error('new_password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-slate-400 mt-1">{{ __('settings.password_min') }}</p>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('settings.confirm_password') }}</label>
                <input type="password" name="new_password_confirmation" required
                       class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 py-3 px-4 focus:border-green-500 focus:ring-green-500 transition-all shadow-sm">
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-xl shadow-lg transition-all transform hover:scale-[1.02]">
                {{ __('settings.change_password') }}
            </button>
        </form>

        <!-- Security Tip -->
        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl flex items-start gap-3">
            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
            <div class="text-sm text-blue-800 dark:text-blue-300">
                <p class="font-bold mb-1">{{ __('settings.security_tip') }}</p>
                <p class="text-xs">{{ __('settings.security_tip_desc') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
