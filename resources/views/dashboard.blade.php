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
        <!-- Notification Bell -->
        <a href="{{ route('notifications.index') }}" class="relative w-10 h-10 rounded-full bg-background-light dark:bg-background-dark hover:bg-primary/10 flex items-center justify-center transition-colors group">
            <span class="material-symbols-outlined text-text-main dark:text-white group-hover:text-primary transition-colors">notifications</span>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center border-2 border-white dark:border-surface-dark">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
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
        
        <!-- Smart Farm Insights (New) -->
        <!-- Admin Tips & Alerts -->
        @if($tips->count() > 0)
        <div>
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">campaign</span>
                {{ __('dashboard.admin_tips') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($tips as $tip)
                <div class="bg-blue-50 dark:bg-blue-900/10 border-l-4 border-blue-500 p-4 rounded-r-xl shadow-sm">
                    <div class="flex items-start">
                        <span class="material-symbols-outlined text-blue-500 mr-2">lightbulb</span>
                        <div>
                            <h4 class="font-bold text-blue-700 dark:text-blue-300">{{ __('dashboard.tip_title') }}</h4>
                            <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">{{ $tip->content }}</p>
                            <span class="text-xs text-blue-400 mt-2 block">{{ $tip->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Weather Stats Grid (Redesigned) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-orange-400 to-red-500 text-white shadow-lg shadow-orange-500/20 group hover:scale-[1.02] transition-transform duration-300">
                <div class="absolute -right-4 -top-4 size-24 bg-white/10 rounded-full blur-xl group-hover:size-32 transition-all duration-500"></div>
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-3xl">thermostat</span>
                        <p class="font-medium opacity-90">{{ __('weather.temperature') }}</p>
                    </div>
                    <div>
                        <p class="text-4xl font-black">{{ $weather['temp'] ?? '--' }}°C</p>
                        <p class="text-sm opacity-80 mt-1">{{ __('weather.high') }}: {{ $weather['temp_max'] ?? '--' }}° • {{ __('weather.low') }}: {{ $weather['temp_min'] ?? '--' }}°</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-blue-400 to-indigo-600 text-white shadow-lg shadow-blue-500/20 group hover:scale-[1.02] transition-transform duration-300">
                 <div class="absolute -right-4 -top-4 size-24 bg-white/10 rounded-full blur-xl group-hover:size-32 transition-all duration-500"></div>
                 <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-3xl">humidity_percentage</span>
                        <p class="font-medium opacity-90">{{ __('weather.humidity') }}</p>
                    </div>
                    <div>
                        <p class="text-4xl font-black">{{ $weather['humidity'] ?? '--' }}%</p>
                        <p class="text-sm opacity-80 mt-1">{{ __('weather.dew_point') }}: {{ $weather['dew_point'] ?? '--' }}°</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-emerald-400 to-teal-600 text-white shadow-lg shadow-emerald-500/20 group hover:scale-[1.02] transition-transform duration-300">
                 <div class="absolute -right-4 -top-4 size-24 bg-white/10 rounded-full blur-xl group-hover:size-32 transition-all duration-500"></div>
                 <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-3xl">air</span>
                        <p class="font-medium opacity-90">{{ __('weather.wind_speed') }}</p>
                    </div>
                    <div>
                        <p class="text-4xl font-black dir-ltr text-right">{{ $weather['wind_speed'] ?? '--' }} <span class="text-lg">{{ __('weather.km_h') }}</span></p>
                        <p class="text-sm opacity-80 mt-1">{{ __('weather.direction') }}: {{ $weather['wind_dir'] ?? 'NW' }}</p>
                    </div>
                </div>
            </div>

             <div class="relative overflow-hidden rounded-2xl p-6 bg-white dark:bg-surface-dark border border-gray-100 dark:border-gray-800 text-text-main dark:text-white shadow-lg group hover:scale-[1.02] transition-transform duration-300">
                 <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="size-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600">
                             <span class="material-symbols-outlined">sunny</span>
                        </div>
                        <p class="font-medium text-text-secondary-light dark:text-text-secondary-dark">{{ __('weather.forecast') }}</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold capitalize">{{ $weather['condition'] ?? '--' }}</p>
                        <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mt-1">{{ __('weather.feels_like') }} {{ $weather['feels_like'] ?? $weather['temp'] ?? '--' }}°</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Tasks & Quick Actions -->
            <div class="lg:col-span-2 flex flex-col gap-8">
               
                 <!-- Quick Actions -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-3xl p-6 shadow-sm border border-border-light dark:border-border-dark">
                    <h3 class="text-lg font-bold mb-4 px-2">{{ __('dashboard.quick_actions') }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('crops.create') }}" class="flex flex-col items-center gap-3 p-4 rounded-2xl bg-background-light dark:bg-background-dark hover:bg-primary/5 hover:border-primary/50 border border-transparent transition-all group">
                            <div class="size-12 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined">add</span>
                            </div>
                            <span class="font-semibold text-sm">{{ __('crops.add_new') }}</span>
                        </a>
                        <a href="{{ route('tasks.index') }}" class="flex flex-col items-center gap-3 p-4 rounded-2xl bg-background-light dark:bg-background-dark hover:bg-blue-50 dark:hover:bg-blue-900/10 hover:border-blue-200 border border-transparent transition-all group">
                             <div class="size-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined">event_note</span>
                            </div>
                            <span class="font-semibold text-sm">{{ __('tasks.create') }}</span>
                        </a>

                         <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-3 p-4 rounded-2xl bg-background-light dark:bg-background-dark hover:bg-orange-50 dark:hover:bg-orange-900/10 hover:border-orange-200 border border-transparent transition-all group">
                             <div class="size-12 rounded-xl bg-orange-100 dark:bg-orange-900/30 text-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined">analytics</span>
                            </div>
                            <span class="font-semibold text-sm">{{ __('nav.reports') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Daily Tasks -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-3xl p-6 shadow-sm border border-border-light dark:border-border-dark flex-1">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                             <div class="size-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined">task</span>
                            </div>
                            <h3 class="text-xl font-bold">{{ __('dashboard.tasks_today') }}</h3>
                        </div>
                        <a href="{{ route('tasks.index') }}" class="text-primary text-sm font-bold hover:underline">{{ __('view_all_link') }}</a>
                    </div>
                    
                    <div class="flex flex-col gap-3">
                         @forelse($tasks ?? [] as $task)
                        <div class="group flex items-center gap-4 p-4 rounded-2xl border border-border-light dark:border-border-dark hover:border-primary/30 hover:shadow-md transition-all bg-background-light/50 dark:bg-background-dark/50">
                             <div class="relative">
                                <input type="checkbox" {{ $task->completed ? 'checked' : '' }} onchange="toggleTaskFromDashboard(event, {{ $task->id }})" class="peer size-6 rounded-lg border-2 border-gray-300 dark:border-gray-600 text-primary focus:ring-primary/20 transition-all cursor-pointer"/>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-base {{ $task->completed ? 'line-through text-gray-400' : '' }}">{{ $task->title }}</h4>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500">{{ $task->priority }}</span>
                                    @if($task->due_date)
                                    <span class="text-xs text-red-500 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                                        {{ $task->due_date->format('H:i') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                             <div class="bg-gray-50 dark:bg-gray-800/50 rounded-full size-20 flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-4xl text-gray-400">task_alt</span>
                             </div>
                            <p class="text-text-secondary-light dark:text-text-secondary-dark font-medium">{{ __('dashboard.no_tasks') }}</p>
                            <p class="text-sm text-gray-400 mt-1">{{ __('dashboard.all_caught_up') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Right Column: My Crops Status -->
            <div class="flex flex-col gap-6">
                
                <div class="bg-surface-light dark:bg-surface-dark rounded-3xl p-6 shadow-sm border border-border-light dark:border-border-dark h-full">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold">{{ __('dashboard.my_crops') }}</h3>
                         <a href="{{ route('crops.index') }}" class="size-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center hover:bg-primary hover:text-white transition-colors">
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>

                    <div class="flex flex-col gap-4">
                        @forelse($crops ?? [] as $crop)
                        <div class="relative overflow-hidden rounded-2xl group cursor-pointer hover:shadow-lg transition-all duration-300">
                            <!-- Background Image with Gradient Overlay -->
                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" style="background-image: url('{{ $crop->image_url ?? 'https://placehold.co/400x300' }}');"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                            
                            <div class="relative z-10 p-5 pt-20 text-white">
                                <div class="flex justify-between items-end">
                                    <div>
                                        <h4 class="text-xl font-bold leading-none mb-1">{{ $crop->name }}</h4>
                                        <p class="text-sm opacity-80 mb-3">{{ $crop->type }}</p>
                                        
                                        <!-- Progress Bar -->
                                        <div class="w-full bg-white/20 h-1.5 rounded-full backdrop-blur-sm overflow-hidden mb-1">
                                            <div class="bg-primary h-full rounded-full" style="width: {{ $crop->progress ?? 50 }}%"></div>
                                        </div>
                                        <p class="text-xs opacity-70">{{ $crop->progress ?? 50 }}% Growth</p>
                                    </div>
                                    <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-lg text-xs font-bold border border-white/10">
                                        {{ __('crops.status.' . $crop->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 px-4 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl">
                             <p class="text-text-secondary-light dark:text-text-secondary-dark mb-4">{{ __('crops.no_crops') }}</p>
                             <a href="{{ route('crops.create') }}" class="inline-flex items-center gap-2 text-primary font-bold hover:underline">
                                 <span class="material-symbols-outlined text-lg">add_circle</span>
                                 {{ __('crops.add_first') }}
                             </a>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
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
