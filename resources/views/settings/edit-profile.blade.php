@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-24 pt-20">
    <!-- Header -->
    <header class="px-5 py-4 bg-white dark:bg-slate-900 fixed top-0 w-full max-w-md mx-auto z-30 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
        <a href="{{ route('settings.index') }}" class="text-slate-600 dark:text-slate-300 hover:text-green-600">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="text-lg font-bold font-display text-slate-900 dark:text-white">{{ __('settings.edit_profile') }}</h1>
    </header>

    <div class="p-5">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-3 rounded-xl mb-4 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Profile Picture -->
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full bg-slate-200 overflow-hidden border-4 border-white dark:border-slate-700 shadow-lg">
                        <img id="avatar-preview" src="{{ Auth::user()->avatar ?? 'https://i.pravatar.cc/150?u=' . Auth::user()->email }}" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <label for="avatar-input" class="absolute bottom-0 right-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:bg-green-600 transition-colors">
                        <span class="material-symbols-outlined text-sm">photo_camera</span>
                    </label>
                    <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/*" onchange="document.getElementById('avatar-preview').src = window.URL.createObjectURL(this.files[0])">
                </div>
            </div>

            <!-- Name -->
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('settings.full_name') }}</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 py-3 px-4 focus:border-green-500 focus:ring-green-500 transition-all shadow-sm">
                @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Farm Name -->
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('settings.farm_name') }}</label>
                <input type="text" name="farm_name" value="{{ old('farm_name', 'مزرعة الوفاء') }}"
                       class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 py-3 px-4 focus:border-green-500 focus:ring-green-500 transition-all shadow-sm">
                @error('farm_name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-xl shadow-lg transition-all transform hover:scale-[1.02]">
                {{ __('settings.save_changes') }}
            </button>
        </form>
    </div>
</div>
@endsection
