@extends('layouts.app')

@section('content')
<!-- Top Header -->
<header class="sticky top-0 z-50 bg-surface-light dark:bg-surface-dark border-b border-border-light dark:border-border-dark flex items-center justify-between px-6 h-16 shrink-0">
    <!-- Mobile Menu Button -->
    <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 text-text-main dark:text-white">
        <span class="material-symbols-outlined">menu</span>
    </button>
    
    <!-- Search Bar -->
    <div class="flex-1 max-w-md hidden md:flex items-center">
        <div class="relative w-full">
            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none text-text-secondary-light dark:text-text-secondary-dark">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </div>
            <input class="block w-full p-2.5 {{ app()->getLocale() == 'ar' ? 'pr-10' : 'pl-10' }} text-sm text-text-main dark:text-white bg-background-light dark:bg-background-dark border-none rounded-lg focus:ring-2 focus:ring-primary focus:outline-none placeholder-text-secondary-light dark:placeholder-text-secondary-dark" placeholder="{{ __('search') }}" type="text"/>
        </div>
    </div>
    
    <!-- Right Actions -->
    <div class="flex items-center gap-4">
        <a href="{{ route('chat.index') }}" class="relative p-2 text-text-secondary-light dark:text-text-secondary-dark hover:text-primary transition-colors rounded-full hover:bg-background-light dark:hover:bg-background-dark">
            <span class="material-symbols-outlined">notifications</span>
            @if(isset($unreadCount) && $unreadCount > 0)
            <span class="absolute top-2 {{ app()->getLocale() == 'ar' ? 'right-2' : 'left-2' }} size-2 bg-red-500 rounded-full border border-surface-light dark:border-surface-dark"></span>
            @endif
        </a>
    </div>
</header>

<!-- Scrollable Content -->
<div class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth">
    <div class="max-w-7xl mx-auto flex flex-col gap-8">
        <!-- Headline -->
        <div>
            <h1 class="text-3xl md:text-4xl font-bold leading-tight">{{ __('dashboard.welcome', ['name' => Auth::user()->name]) }}</h1>
            <p class="text-text-secondary-light dark:text-text-secondary-dark mt-2 text-lg">{{ __('dashboard.welcome_desc') }}</p>
        </div>
        
        <!-- Weather Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex flex-col gap-3 rounded-xl p-5 bg-surface-light dark:bg-surface-dark shadow-sm border border-border-light dark:border-border-dark">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400">
                        <span class="material-symbols-outlined">thermostat</span>
                    </div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">{{ __('weather.temperature') }}</p>
                </div>
                <p class="text-2xl font-bold dir-ltr text-right">{{ $weather['temp'] ?? '--' }}°C</p>
            </div>
            <div class="flex flex-col gap-3 rounded-xl p-5 bg-surface-light dark:bg-surface-dark shadow-sm border border-border-light dark:border-border-dark">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <span class="material-symbols-outlined">humidity_percentage</span>
                    </div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">{{ __('weather.humidity') }}</p>
                </div>
                <p class="text-2xl font-bold dir-ltr text-right">{{ $weather['humidity'] ?? '--' }}%</p>
            </div>
            <div class="flex flex-col gap-3 rounded-xl p-5 bg-surface-light dark:bg-surface-dark shadow-sm border border-border-light dark:border-border-dark">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300">
                        <span class="material-symbols-outlined">air</span>
                    </div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">{{ __('weather.wind_speed') }}</p>
                </div>
                <p class="text-2xl font-bold">{{ $weather['wind_speed'] ?? '--' }} {{ __('weather.km_h') }}</p>
            </div>
            <div class="flex flex-col gap-3 rounded-xl p-5 bg-surface-light dark:bg-surface-dark shadow-sm border border-border-light dark:border-border-dark">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 dark:text-yellow-400">
                        <span class="material-symbols-outlined">sunny</span>
                    </div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">{{ __('weather.forecast') }}</p>
                </div>
                <p class="text-2xl font-bold">{{ $weather['condition'] ?? '--' }}</p>
            </div>
        </div>
        
        <!-- Alerts & Tasks Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Urgent Alert (Span 2 cols) -->
            <div class="lg:col-span-2 flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">{{ __('dashboard.alerts') }}</h3>
                    <a href="#" class="text-primary text-sm font-medium hover:underline">{{ __('crops.show.view_all') }}</a>
                </div>
                <div class="rounded-xl p-5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 size-10 rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-red-600 dark:text-red-400">
                            <span class="material-symbols-outlined">warning</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-red-900 dark:text-red-200 mb-1">{{ __('dashboard.alert_title') }}</h4>
                            <p class="text-sm text-red-700 dark:text-red-300">{{ __('dashboard.alert_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Daily Tasks -->
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">{{ __('dashboard.tasks_today') }}</h3>
                    <a href="{{ route('tasks.index') }}" class="text-primary text-sm font-medium hover:underline">{{ __('crops.show.view_all') }}</a>
                </div>
                <div class="rounded-xl p-5 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark">
                    @forelse($tasks ?? [] as $task)
                    <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition-colors cursor-pointer">
                        <input type="checkbox" {{ $task->completed ? 'checked' : '' }} onchange="toggleTaskFromDashboard(event, {{ $task->id }})" class="size-5 rounded border-gray-300 text-primary focus:ring-primary"/>
                        <span class="text-sm {{ $task->completed ? 'line-through text-text-secondary-light dark:text-text-secondary-dark' : '' }}">{{ $task->title }}</span>
                    </label>
                    @empty
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm text-center py-4">{{ __('dashboard.no_tasks') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Crop Overview -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">{{ __('dashboard.my_crops') }}</h3>
                <a href="{{ route('crops.index') }}" class="text-primary text-sm font-medium hover:underline">{{ __('crops.show.view_all') }}</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($crops ?? [] as $crop)
                <a href="{{ route('crops.show', $crop->id) }}" class="bg-surface-light dark:bg-surface-dark rounded-2xl overflow-hidden border border-border-light dark:border-border-dark shadow-sm hover:shadow-md transition-shadow group cursor-pointer flex flex-col">
                    <div class="relative h-40 bg-gray-200">
                        <img alt="{{ $crop->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $crop->image_url ?? 'https://placehold.co/400x300?text=' . urlencode($crop->name) }}"/>
                        <div class="absolute bottom-3 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }}">
                            <span class="bg-green-100/90 dark:bg-green-900/90 backdrop-blur-sm text-green-800 dark:text-green-300 text-xs font-bold px-3 py-1.5 rounded-full">
                                {{ __('crops.status.' . $crop->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4 flex flex-col gap-2">
                        <h4 class="text-base font-bold">{{ $crop->name }}</h4>
                        <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">{{ $crop->type }}</p>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-12">
                    <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">potted_plant</span>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark">{{ __('crops.no_crops') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    function toggleTaskFromDashboard(event, taskId) {
        const checkbox = event.target;
        checkbox.disabled = true;
        
        fetch(`/farmer/tasks/${taskId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            // Remove task element from UI
            const label = checkbox.closest('label');
            if (label) {
                label.style.transition = 'all 0.3s ease';
                label.style.opacity = '0';
                setTimeout(() => label.remove(), 300);
            } else {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            checkbox.disabled = false;
            checkbox.checked = !checkbox.checked;
            alert('حدث خطأ في تحديث المهمة');
        });
    }
</script>
@endsection
