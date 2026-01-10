@extends('layouts.app')

@section('content')
<!-- Header / Breadcrumb -->
<div class="px-6 py-4 flex items-center gap-2 text-sm text-text-muted">
    <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">home</span>
        {{ __('dashboard') }}
    </a>
    <span class="material-symbols-outlined text-[16px] rtl:rotate-180 text-gray-400">chevron_left</span>
    <a href="{{ route('crops.index') }}" class="hover:text-primary transition-colors">{{ __('crops.title') }}</a>
    <span class="material-symbols-outlined text-[16px] rtl:rotate-180 text-gray-400">chevron_left</span>
    <span class="font-bold text-text-main dark:text-white">{{ $crop->name }}</span>
</div>

<div class="px-6 pb-8 max-w-7xl mx-auto flex flex-col gap-6">

    <!-- Hero Section: Crop Overview -->
    <div class="relative overflow-hidden rounded-3xl bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark shadow-sm">
        <!-- Background Decoration -->
        <div class="absolute top-0 ltr:right-0 rtl:left-0 w-2/3 h-full bg-gradient-to-l rtl:bg-gradient-to-r from-primary/10 to-transparent opacity-50"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row p-6 md:p-8 gap-6 md:gap-10 items-start md:items-center">
            <!-- Image with Status Badge -->
            <div class="relative shrink-0 group">
                <div class="size-32 md:size-40 rounded-2xl bg-cover bg-center shadow-lg border-4 border-white dark:border-surface-dark group-hover:scale-105 transition-transform duration-500" 
                     style="background-image: url('{{ $crop->image_url ?? 'https://placehold.co/400x300' }}');">
                </div>
                <!-- Active Badge -->
                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-surface-light dark:bg-surface-dark px-3 py-1 rounded-full shadow-md border border-border-light dark:border-border-dark flex items-center gap-1 whitespace-nowrap">
                    <span class="size-2.5 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-text-main dark:text-white">{{ __('crops.show.active') }}</span>
                </div>
            </div>

            <!-- Details -->
            <div class="flex-1 w-full">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black text-text-main dark:text-white tracking-tight mb-2">{{ $crop->name }}</h1>
                        <div class="flex flex-wrap items-center gap-3 text-sm text-text-muted">
                            <span class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-lg">
                                <span class="material-symbols-outlined text-[18px]">category</span>
                                {{ $crop->type }}
                            </span>
                            <span class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-lg">
                                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                                {{ __('crops.show.planting_date_label') }}: {{ \Carbon\Carbon::parse($crop->planting_date)->format('Y-m-d') }}
                            </span>
                            @if($crop->area)
                            <span class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-lg">
                                <span class="material-symbols-outlined text-[18px]">square_foot</span>
                                {{ $crop->area }} م²
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Hero Actions -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('crops.edit', $crop->id) }}" class="flex items-center gap-2 px-4 py-2 rounded-xl border border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-white/5 font-bold transition-all text-sm">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                            <span class="hidden sm:inline">{{ __('crops.show.edit_details') }}</span>
                        </a>
                        <form action="{{ route('crops.harvest', $crop->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('{{ __('crops.confirm_harvest') }}')" class="flex items-center gap-2 px-5 py-2 rounded-xl bg-primary text-background-dark font-bold hover:bg-green-400 transition-all shadow-lg shadow-green-500/20 text-sm">
                                <span class="material-symbols-outlined text-[20px] filled">agriculture</span>
                                <span>{{ __('crops.show.harvest_action') }}</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="bg-white/50 dark:bg-black/20 rounded-xl p-4 border border-border-light dark:border-border-dark">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">timelapse</span>
                            {{ __('crops.show.progress_title') }}
                        </span>
                        <span class="font-black text-primary">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-primary to-green-400 h-full rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs font-medium text-text-muted">
                        <span>{{ __('crops.show.days_remaining', ['days' => $daysRemaining]) }}</span>
                        <span>{{ __('crops.show.expected_harvest') }}: {{ $harvestDate->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Vital Stats & Status -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Vital Cards Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Health -->
                <div class="group bg-surface-light dark:bg-surface-dark p-5 rounded-2xl border border-border-light dark:border-border-dark hover:border-green-400 transition-all shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600">
                            <span class="material-symbols-outlined filled">eco</span>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 rounded bg-green-50 dark:bg-green-900/20 text-green-600">{{ $crop->status }}</span>
                    </div>
                    <p class="text-sm text-text-muted mb-1">{{ __('crops.show.health') }}</p>
                    <h3 class="text-2xl font-black text-text-main dark:text-white capitalize">{{ __('crops.status.' . $crop->status) ?? 'Excellent' }}</h3>
                </div>

                <!-- Moisture -->
                <div class="group bg-surface-light dark:bg-surface-dark p-5 rounded-2xl border border-border-light dark:border-border-dark hover:border-blue-400 transition-all shadow-sm">
                     <div class="flex justify-between items-start mb-2">
                        <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600">
                            <span class="material-symbols-outlined filled">water_drop</span>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 rounded bg-blue-50 dark:bg-blue-900/20 text-blue-600">Live</span>
                    </div>
                    <p class="text-sm text-text-muted mb-1">{{ __('crops.show.moisture') }}</p>
                    <h3 class="text-2xl font-black text-text-main dark:text-white">{{ $sensorData['moisture'] }}%</h3>
                </div>

                <!-- Temp -->
                <div class="group bg-surface-light dark:bg-surface-dark p-5 rounded-2xl border border-border-light dark:border-border-dark hover:border-yellow-400 transition-all shadow-sm">
                     <div class="flex justify-between items-start mb-2">
                        <div class="p-2 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600">
                            <span class="material-symbols-outlined filled">thermostat</span>
                        </div>
                         <span class="text-xs font-bold px-2 py-1 rounded bg-gray-50 dark:bg-gray-800 text-gray-500">Live</span>
                    </div>
                    <p class="text-sm text-text-muted mb-1">{{ __('crops.show.temp') }}</p>
                    <h3 class="text-2xl font-black text-text-main dark:text-white">{{ $sensorData['temp'] }}°C</h3>
                </div>
            </div>

            <!-- Update Status Section -->
            <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 border border-border-light dark:border-border-dark shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-primary/10 rounded-lg text-primary-dark">
                        <span class="material-symbols-outlined">edit_note</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">{{ __('crops.show.update_status') }}</h3>
                        <p class="text-sm text-text-muted">{{ __('crops.show.update_desc') }}</p>
                    </div>
                </div>

                <form action="{{ route('crops.update', $crop->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <!-- Radio Options -->
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="excellent" class="peer sr-only" {{ $crop->status == 'excellent' ? 'checked' : '' }}>
                            <div class="flex flex-col items-center justify-center gap-2 p-3 rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary-dark transition-all hover:bg-gray-100 dark:hover:bg-gray-800">
                                <span class="material-symbols-outlined filled">sentiment_satisfied</span>
                                <span class="text-xs font-bold">{{ __('crops.show.status_excellent') }}</span>
                            </div>
                        </label>

                         <label class="cursor-pointer">
                            <input type="radio" name="status" value="warning" class="peer sr-only" {{ $crop->status == 'warning' ? 'checked' : '' }}>
                             <div class="flex flex-col items-center justify-center gap-2 p-3 rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark peer-checked:border-blue-500 peer-checked:bg-blue-500/10 peer-checked:text-blue-600 transition-all hover:bg-gray-100 dark:hover:bg-gray-800">
                                <span class="material-symbols-outlined">water_drop</span>
                                <span class="text-xs font-bold">{{ __('crops.show.status_water') }}</span>
                            </div>
                        </label>

                         <label class="cursor-pointer">
                            <input type="radio" name="status" value="infected" class="peer sr-only" {{ $crop->status == 'infected' ? 'checked' : '' }}>
                             <div class="flex flex-col items-center justify-center gap-2 p-3 rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark peer-checked:border-red-500 peer-checked:bg-red-500/10 peer-checked:text-red-600 transition-all hover:bg-gray-100 dark:hover:bg-gray-800">
                                <span class="material-symbols-outlined">bug_report</span>
                                <span class="text-xs font-bold">{{ __('crops.show.status_pests') }}</span>
                            </div>
                        </label>

                          <label class="cursor-pointer">
                            <input type="radio" name="status" value="good" class="peer sr-only" {{ $crop->status == 'good' ? 'checked' : '' }}>
                             <div class="flex flex-col items-center justify-center gap-2 p-3 rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark peer-checked:border-yellow-500 peer-checked:bg-yellow-500/10 peer-checked:text-yellow-600 transition-all hover:bg-gray-100 dark:hover:bg-gray-800">
                                <span class="material-symbols-outlined">wb_sunny</span>
                                <span class="text-xs font-bold">{{ __('crops.show.status_heat') }}</span>
                            </div>
                        </label>
                    </div>

                    <div class="relative">
                        <textarea name="field_name" rows="3" class="w-full rounded-xl bg-background-light dark:bg-background-dark border-border-light dark:border-border-dark p-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary resize-none" placeholder="{{ __('crops.show.add_note') }}">{{ $crop->field_name }}</textarea>
                        <button type="submit" class="absolute bottom-3 left-3 bg-primary text-background-dark text-xs font-bold px-4 py-2 rounded-lg hover:bg-primary-dark hover:text-white transition-colors">
                            {{ __('crops.show.save_update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: AI Advice & History -->
        <div class="flex flex-col gap-6">
            
            <!-- Smart Recommendations (AI Style) -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-background-dark to-black text-white p-6 shadow-xl">
                 <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 rounded-full blur-3xl"></div>
                 <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-4 text-primary">
                        <span class="material-symbols-outlined animate-pulse">auto_awesome</span>
                        <h3 class="font-bold text-lg">{{ __('crops.show.recommendations') }}</h3>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/10 mb-5">
                         <p class="text-sm leading-relaxed text-gray-200">
                            {{ $smartAdvice }}
                        </p>
                    </div>
                    <button onclick="document.getElementById('irrigationModal').classList.remove('hidden')" class="w-full py-3 rounded-xl bg-primary text-background-dark font-bold text-sm hover:bg-green-400 transition-colors flex items-center justify-center gap-2 shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined text-[18px]">water_drop</span>
                        {{ __('crops.show.schedule_irrigation') }}
                    </button>
                 </div>
            </div>

            <!-- Quick Actions List -->
            <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-5 border border-border-light dark:border-border-dark shadow-sm">
                <h3 class="text-sm font-bold text-text-muted uppercase tracking-wider mb-3 px-1">{{ __('crops.show.quick_actions') }}</h3>
                <div class="space-y-2">


                     <a href="{{ route('crops.growth-report', $crop->id) }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-background-light dark:bg-background-dark hover:bg-orange-50 dark:hover:bg-orange-900/10 group transition-all">
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">analytics</span>
                            </div>
                            <span class="font-bold text-sm text-text-main dark:text-white">{{ __('crops.show.growth_report') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-gray-400 group-hover:text-orange-600 rtl:rotate-180">chevron_right</span>
                    </a>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 border border-border-light dark:border-border-dark shadow-sm flex-1">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-lg">{{ __('crops.show.history') }}</h3>
                    <a href="{{ route('crops.activity-log', $crop->id) }}" class="text-xs font-bold text-primary hover:underline">{{ __('crops.show.view_all') }}</a>
                </div>

                <div class="relative pl-4 ltr:pl-4 rtl:pl-0 rtl:pr-4 border-l-2 ltr:border-l-2 rtl:border-l-0 rtl:border-r-2 border-gray-100 dark:border-gray-800 space-y-6">
                    @forelse($irrigations->take(3) as $irrigation)
                    <div class="relative">
                        <span class="absolute top-1 -left-[21px] rtl:-right-[21px] size-3 rounded-full bg-blue-500 ring-4 ring-white dark:ring-surface-dark"></span>
                        <p class="text-sm font-bold">{{ __('crops.timeline.irrigation') }} <span class="text-blue-500">{{ $irrigation->amount }}L</span></p>
                        <span class="text-xs text-text-muted">{{ \Carbon\Carbon::parse($irrigation->created_at)->diffForHumans() }}</span>
                    </div>
                    @empty
                    @endforelse

                     <div class="relative">
                        <span class="absolute top-1 -left-[21px] rtl:-right-[21px] size-3 rounded-full bg-primary ring-4 ring-white dark:ring-surface-dark"></span>
                        <p class="text-sm font-bold">{{ __('crops.timeline.planting') }}</p>
                        <span class="text-xs text-text-muted">{{ \Carbon\Carbon::parse($crop->created_at)->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modals (Sell & Irrigate) - Kept same but ensure styling consistency -->
@include('components.crop-modals', ['crop' => $crop])

@endsection
