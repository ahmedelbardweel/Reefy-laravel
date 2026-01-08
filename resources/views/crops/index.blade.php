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
            <input class="block w-full p-2.5 {{ app()->getLocale() == 'ar' ? 'pr-10' : 'pl-10' }} text-sm text-text-main dark:text-white bg-background-light dark:bg-background-dark border-none rounded-lg focus:ring-2 focus:ring-primary focus:outline-none placeholder-text-secondary-light dark:placeholder-text-secondary-dark" placeholder="{{ __('crops.search_placeholder') }}" type="text"/>
        </div>
    </div>
    
    <!-- Right Actions -->
    <div class="flex items-center gap-4">
        <button class="relative p-2 text-text-secondary-light dark:text-text-secondary-dark hover:text-primary transition-colors rounded-full hover:bg-background-light dark:hover:bg-background-dark">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2 {{ app()->getLocale() == 'ar' ? 'right-2' : 'left-2' }} size-2 bg-red-500 rounded-full border border-surface-light dark:border-surface-dark"></span>
        </button>
        <a href="{{ route('crops.create') }}" class="hidden md:flex items-center gap-2 px-4 py-2 bg-primary text-background-dark text-sm font-bold rounded-lg hover:bg-green-400 transition-colors shadow-sm shadow-green-500/20">
            <span class="material-symbols-outlined text-[20px]">add</span>
            <span>{{ __('crops.add_new') }}</span>
        </a>
    </div>
</header>

<!-- Scrollable Content -->
<div class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth">
    <div class="max-w-7xl mx-auto flex flex-col gap-8">
        <!-- Page Heading & Stats -->
        <div class="flex flex-col gap-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-black leading-tight">{{ __('crops.title') }}</h2>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark mt-1">{{ __('crops.subtitle') }}</p>
                </div>
                <!-- Mobile FAB replacement -->
                <a href="{{ route('crops.create') }}" class="md:hidden w-full flex items-center justify-center gap-2 px-4 py-3 bg-primary text-background-dark text-sm font-bold rounded-lg shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    <span>{{ __('crops.add_crop') }}</span>
                </a>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Active Crops -->
                <div class="bg-surface-light dark:bg-surface-dark p-5 rounded-2xl border border-border-light dark:border-border-dark shadow-sm flex flex-col gap-2 relative overflow-hidden group">
                    <div class="absolute {{ app()->getLocale() == 'ar' ? '-right-4' : '-left-4' }} -top-4 bg-green-50 dark:bg-green-900/10 size-24 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <span class="text-text-secondary-light dark:text-text-secondary-dark font-medium text-sm">{{ __('crops.stats.active') }}</span>
                        <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">trending_up</span>
                            {{ $crops->count() }}
                        </span>
                    </div>
                    <div class="flex items-end gap-2 relative z-10">
                        <span class="text-3xl font-bold">{{ $crops->count() }}</span>
                        <span class="text-sm text-text-secondary-light dark:text-text-secondary-dark mb-1">{{ __('crops.stats.types') }}</span>
                    </div>
                </div>
                
                <!-- Upcoming Harvest -->
                <div class="bg-surface-light dark:bg-surface-dark p-5 rounded-2xl border border-border-light dark:border-border-dark shadow-sm flex flex-col gap-2 relative overflow-hidden group">
                    <div class="absolute {{ app()->getLocale() == 'ar' ? '-right-4' : '-left-4' }} -top-4 bg-orange-50 dark:bg-orange-900/10 size-24 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <span class="text-text-secondary-light dark:text-text-secondary-dark font-medium text-sm">{{ __('crops.stats.harvest_soon') }}</span>
                        <span class="bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs font-bold px-2 py-1 rounded-full">{{ __('crops.stats.within_days') }}</span>
                    </div>
                    <div class="flex items-end gap-2 relative z-10">
                        <span class="text-3xl font-bold">{{ $crops->where('status', 'ready')->count() }}</span>
                        <span class="text-sm text-text-secondary-light dark:text-text-secondary-dark mb-1">{{ __('crops.stats.crops') }}</span>
                    </div>
                </div>
                
                <!-- Revenue Projection -->
                <div class="bg-surface-light dark:bg-surface-dark p-5 rounded-2xl border border-border-light dark:border-border-dark shadow-sm flex flex-col gap-2 relative overflow-hidden group">
                    <div class="absolute {{ app()->getLocale() == 'ar' ? '-right-4' : '-left-4' }} -top-4 bg-blue-50 dark:bg-blue-900/10 size-24 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <span class="text-text-secondary-light dark:text-text-secondary-dark font-medium text-sm">{{ __('crops.stats.revenue') }}</span>
                        <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold px-2 py-1 rounded-full">+10%</span>
                    </div>
                    <div class="flex items-end gap-2 relative z-10">
                        <span class="text-3xl font-bold">45,000</span>
                        <span class="text-sm text-text-secondary-light dark:text-text-secondary-dark mb-1">{{ __('currency') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters & Sort -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-surface-light dark:bg-surface-dark p-2 rounded-xl border border-border-light dark:border-border-dark shadow-sm sticky top-0 z-10">
            <!-- Tabs -->
            <div class="flex p-1 bg-background-light dark:bg-background-dark rounded-lg overflow-x-auto w-full sm:w-auto">
                <a href="{{ route('crops.index') }}" class="px-4 py-2 text-sm {{ !request('filter') ? 'font-bold rounded-md bg-surface-light dark:bg-surface-dark shadow-sm' : 'font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-text-main' }} transition-all whitespace-nowrap">{{ __('crops.filter.all') }}</a>
                <a href="{{ route('crops.index', ['filter' => 'growing']) }}" class="px-4 py-2 text-sm {{ request('filter') == 'growing' ? 'font-bold rounded-md bg-surface-light dark:bg-surface-dark shadow-sm' : 'font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-text-main' }} transition-all whitespace-nowrap">{{ __('crops.filter.growing') }}</a>
                <a href="{{ route('crops.index', ['filter' => 'ready']) }}" class="px-4 py-2 text-sm {{ request('filter') == 'ready' ? 'font-bold rounded-md bg-surface-light dark:bg-surface-dark shadow-sm' : 'font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-text-main' }} transition-all whitespace-nowrap">{{ __('crops.filter.ready_harvest') }}</a>
                <a href="{{ route('crops.index', ['filter' => 'sold']) }}" class="px-4 py-2 text-sm {{ request('filter') == 'sold' ? 'font-bold rounded-md bg-surface-light dark:bg-surface-dark shadow-sm' : 'font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-text-main' }} transition-all whitespace-nowrap">{{ __('crops.filter.sold') }}</a>
            </div>
            
            <!-- Sort & View Toggle -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <select class="appearance-none w-full sm:w-48 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 {{ app()->getLocale() == 'ar' ? 'pr-8' : 'pl-8' }}">
                        <option>{{ __('crops.sort.newest') }}</option>
                        <option>{{ __('crops.sort.harvest_soon') }}</option>
                        <option>{{ __('crops.sort.highest_value') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0' : 'left-0' }} flex items-center px-2 text-text-secondary-light dark:text-text-secondary-dark">
                        <span class="material-symbols-outlined text-[20px]">sort</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Crop Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-8">
            @forelse($crops as $crop)
            <!-- Crop Card -->
            <a href="{{ route('crops.show', $crop->id) }}" class="bg-surface-light dark:bg-surface-dark rounded-2xl overflow-hidden border border-border-light dark:border-border-dark shadow-sm hover:shadow-md transition-shadow group cursor-pointer flex flex-col {{ $crop->status == 'ready' ? 'border-r-4 border-r-primary' : '' }}">
                <div class="relative h-48 bg-gray-200">
                    <img alt="{{ $crop->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $crop->image_url ?? 'https://placehold.co/400x300?text=' . urlencode($crop->name) }}"/>
                    <div class="absolute bottom-3 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }}">
                        @if($crop->status == 'growing')
                        <span class="bg-green-100/90 dark:bg-green-900/90 backdrop-blur-sm text-green-800 dark:text-green-300 text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px] filled">spa</span>
                            {{ __('crops.status.growing') }}
                        </span>
                        @elseif($crop->status == 'ready')
                        <span class="bg-primary text-background-dark text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-outlined text-[14px]">agriculture</span>
                            {{ __('crops.status.ready_harvest') }}
                        </span>
                        @else
                        <span class="bg-gray-200/90 dark:bg-gray-700/90 backdrop-blur-sm text-gray-700 dark:text-gray-200 text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">check_circle</span>
                            {{ __('crops.status.' . $crop->status) }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-1 gap-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold">{{ $crop->name }}</h3>
                            <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">{{ $crop->location ?? __('crops.field') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 mt-auto">
                        <div class="flex justify-between text-sm py-1 border-b border-border-light dark:border-border-dark border-dashed">
                            <span class="text-text-secondary-light dark:text-text-secondary-dark">{{ __('crops.planted_date') }}:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($crop->planted_date)->isoFormat('D MMMM YYYY') }}</span>
                        </div>
                        @if($crop->harvest_date)
                        <div class="flex justify-between text-sm py-1">
                            <span class="text-text-secondary-light dark:text-text-secondary-dark">{{ __('crops.expected_harvest') }}:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($crop->harvest_date)->isoFormat('D MMMM YYYY') }}</span>
                        </div>
                        @endif
                    </div>
                    @if($crop->status == 'growing')
                    <div class="mt-2 w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 overflow-hidden">
                        <?php
                            $planted = \Carbon\Carbon::parse($crop->planted_date);
                            $harvest = \Carbon\Carbon::parse($crop->harvest_date);
                            $now = \Carbon\Carbon::now();
                            $total = $planted->diffInDays($harvest);
                            $elapsed = $planted->diffInDays($now);
                            $progress = $total > 0 ? min(($elapsed / $total) * 100, 100) : 0;
                        ?>
                        <div class="bg-primary h-2 rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-text-secondary-light dark:text-text-secondary-dark">
                        <span>{{ __('crops.growth_progress') }}</span>
                        <span>{{ round($progress) }}%</span>
                    </div>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-12">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">potted_plant</span>
                <p class="text-text-secondary-light dark:text-text-secondary-dark">{{ __('crops.no_crops') }}</p>
            </div>
            @endforelse
            
            <!-- Add New Crop Card -->
            <a href="{{ route('crops.create') }}" class="bg-background-light dark:bg-background-dark rounded-2xl border-2 border-dashed border-border-light dark:border-border-dark hover:border-primary/50 cursor-pointer flex flex-col items-center justify-center p-6 min-h-[380px] group transition-colors">
                <div class="size-16 rounded-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-sm">
                    <span class="material-symbols-outlined text-3xl text-primary">add</span>
                </div>
                <h3 class="text-lg font-bold mb-1">{{ __('crops.new_planting') }}</h3>
                <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark text-center">{{ __('crops.start_new_cycle') }}</p>
            </a>
        </div>
    </div>
</div>
@endsection
