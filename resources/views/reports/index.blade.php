@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-24 pt-20">
    <!-- Header -->
    <header class="px-5 py-4 bg-white dark:bg-slate-900 fixed top-0 w-full max-w-md mx-auto z-30 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center transition-all bg-opacity-95 backdrop-blur-sm">
        <a href="{{ route('dashboard') }}" class="p-2 -mr-2 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
            <span class="material-symbols-outlined rtl:rotate-180">arrow_forward</span>
        </a>
        <h1 class="text-lg font-bold font-display text-slate-900 dark:text-white">{{ __('reports.page_title') }}</h1>
        <button class="p-2 -ml-2 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
            <span class="material-symbols-outlined">share</span>
        </button>
    </header>

    <div class="p-5 space-y-6">
        
        <!-- Period Tabs -->
        <div class="bg-gray-200 dark:bg-slate-800 p-1 rounded-xl flex text-center text-sm font-bold">
            @php
                $periods = [
                    'weekly' => __('reports.period.weekly'),
                    'monthly' => __('reports.period.monthly'),
                    'quarterly' => __('reports.period.quarterly'),
                    'yearly' => __('reports.period.yearly')
                ];
                $currentPeriod = $period;
            @endphp
            @foreach($periods as $key => $label)
            <a href="{{ route('reports.index', ['period' => $key]) }}" 
               class="flex-1 py-1.5 rounded-lg transition-all {{ $currentPeriod === $key ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        <!-- Last Update Badge -->
        <div class="flex justify-start">
            <span class="bg-primary/20 text-green-700 dark:text-green-400 text-[10px] font-bold px-3 py-1 rounded-full">
                {{ __('reports.last_update_today') }}
            </span>
        </div>

        <!-- Farm Summary -->
        <div>
            <h2 class="text-xl font-bold font-display text-slate-900 dark:text-white mb-4">{{ __('reports.farm_summary') }}</h2>
            <div class="grid grid-cols-2 gap-4">
                <!-- Production Card -->
                <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-full text-green-600">
                            <span class="material-symbols-outlined text-xl">agriculture</span>
                        </div>
                    </div>
                    <span class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">{{ __('reports.total_production') }}</span>
                    <p class="text-lg font-black text-slate-900 dark:text-white mb-2">{{ number_format($productionCurrent) }} <span class="text-xs font-medium">{{ __('unit.ton') }}</span></p>
                    <div class="flex items-center gap-1 text-[10px] font-bold {{ $productionGrowth >= 0 ? 'text-green-600' : 'text-red-500' }}">
                        <span class="material-symbols-outlined text-[12px]">{{ $productionGrowth >= 0 ? 'trending_up' : 'trending_down' }}</span>
                        <span dir="ltr">%{{ abs($productionGrowth) }}</span>
                        <span class="text-slate-400 font-normal">{{ __('reports.vs_last_period') }}</span>
                    </div>
                </div>

                <!-- Tasks Card -->
                <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
                     <div class="flex justify-between items-start mb-3">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-full text-blue-600">
                            <span class="material-symbols-outlined text-xl">check_circle</span>
                        </div>
                    </div>
                    <span class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">{{ __('reports.tasks_completed') }}</span>
                    <p class="text-3xl font-black text-slate-900 dark:text-white mb-4">٪{{ $taskCompletionRate }}</p>
                    <!-- Simple Progress Bar -->
                    <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full" style="width: {{ $taskCompletionRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Crop Performance (Simple Bar Chart Visualization) -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm">
             <div class="flex justify-between items-start mb-6">
                <div>
                     <h3 class="font-bold text-slate-900 dark:text-white">{{ __('reports.crop_performance') }}</h3>
                     <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('reports.crop_performance_desc') }}</p>
                </div>
                <button class="w-8 h-8 flex items-center justify-center bg-slate-100 dark:bg-slate-700 rounded-lg text-slate-600 dark:text-slate-300">
                    <span class="material-symbols-outlined text-lg">bar_chart</span>
                </button>
            </div>
            
            <!-- Chart Area -->
            <div class="flex items-end justify-between h-40 gap-4 px-2">
                @php
                    $maxPerformance = 0;
                    if(count($cropPerformance) > 0) {
                        $maxPerformance = max($cropPerformance);
                    }
                    $maxPerformance = $maxPerformance > 0 ? $maxPerformance : 1;
                @endphp

                @forelse($cropPerformance as $name => $total)
                <div class="flex flex-col items-center gap-2 flex-1 group">
                    <div class="w-full bg-primary rounded-t-lg relative group-hover:opacity-90 transition-opacity" 
                         style="height: {{ max(10, min(100, ($total / $maxPerformance) * 100)) }}%"></div>
                    <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300 truncate w-full text-center">{{ $name }}</span>
                </div>
                @empty
                <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">{{ __('reports.no_harvest_data') }}</div>
                @endforelse
            </div>
        </div>

        <!-- Resources & Expenses -->
        <div>
            <h2 class="text-xl font-bold font-display text-slate-900 dark:text-white mb-4">{{ __('reports.resources_expenses') }}</h2>
            <div class="flex flex-col gap-3">
                <!-- Water -->
                <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                             <h3 class="font-bold text-slate-900 dark:text-white">{{ __('reports.water_consumption') }}</h3>
                         </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">{{ number_format($waterCurrent) }} {{ __('reports.liters_this_period') }}</p>
                        <span class="text-[10px] font-bold {{ $waterChange <= 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $waterChange <= 0 ? '-' : '+' }}{{ abs($waterChange) }}% {{ $waterChange <= 0 ? __('reports.status.improved') : __('reports.status.increase') }}
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600">
                        <span class="material-symbols-outlined">water_drop</span>
                    </div>
                </div>

                <!-- Money -->
                <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
                    <div>
                         <div class="flex items-center gap-2 mb-1">
                             <h3 class="font-bold text-slate-900 dark:text-white">{{ __('reports.operational_expenses') }}</h3>
                         </div>
                        <p class="text-lg font-black text-slate-900 dark:text-white mb-0.5">{{ number_format($expensesCurrent) }} <span class="text-xs font-normal">{{ __('currency') }}</span></p>
                         <span class="text-[10px] text-slate-400 block mb-2">{{ __('reports.expenses_desc') }}</span>
                         <span class="text-[10px] font-bold {{ $expensesChange > 0 ? 'text-red-500' : 'text-green-600' }}">
                            {{ $expensesChange > 0 ? '+' : '-' }}{{ abs($expensesChange) }}% {{ $expensesChange > 0 ? __('reports.status.high') : __('reports.status.low') }}
                        </span>
                    </div>
                     <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/20 flex items-center justify-center text-amber-600">
                        <span class="material-symbols-outlined">account_balance_wallet</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Status (Donut Chart) -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm">
            <div class="flex justify-between items-start mb-6">
                <div>
                     <h3 class="font-bold text-slate-900 dark:text-white">{{ __('reports.task_status') }}</h3>
                     <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('reports.task_status_desc') }}</p>
                </div>
                <a href="{{ route('tasks.index') }}" class="text-green-600 text-xs font-bold hover:underline">عرض الكل</a>
            </div>

            <div class="flex items-center justify-between">
                <!-- Legend -->
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-bold">{{ __('task.status.completed') }} ({{ $taskDistribution['completed'] }})</span>
                    </div>
                     <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-bold">{{ __('task.status.in_progress') }} ({{ $taskDistribution['in_progress'] }})</span>
                    </div>
                     <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-bold">{{ __('task.status.pending') }} ({{ $taskDistribution['pending'] }})</span>
                    </div>
                </div>

                <!-- Donut Chart (CSS Conic Gradient) -->
                @php
                    $total = $totalTasks ?: 1;
                    $pCompleted = ($taskDistribution['completed'] / $total) * 100;
                    $pInProgress = ($taskDistribution['in_progress'] / $total) * 100;
                    $pPending = ($taskDistribution['pending'] / $total) * 100;
                    
                    // Calculate deg stops
                    $stop1 = $pCompleted; 
                    $stop2 = $stop1 + $pInProgress;
                @endphp
                <div class="relative w-32 h-32 rounded-full flex items-center justify-center shadow-inner"
                     style="background: conic-gradient(
                        #22c55e 0% {{ $stop1 }}%, 
                        #3b82f6 {{ $stop1 }}% {{ $stop2 }}%, 
                        #ef4444 {{ $stop2 }}% 100%
                     );">
                     <!-- Inner Circle -->
                     <div class="w-20 h-20 bg-white dark:bg-slate-800 rounded-full flex flex-col items-center justify-center shadow-sm">
                         <span class="text-xl font-black text-slate-900 dark:text-white">{{ $totalTasks }}</span>
                         <span class="text-[10px] text-slate-400">{{ __('task.unit') }}</span>
                     </div>
                </div>
            </div>
        </div>

        <!-- Recent Reports (Static List for now as placeholders) -->
        <div>
            <h2 class="text-lg font-bold font-display text-slate-900 dark:text-white mb-4">{{ __('reports.recent_reports') }}</h2>
            <div class="flex flex-col gap-3">
                <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm flex items-center gap-4 cursor-pointer hover:bg-slate-50 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center text-red-600">
                        <span class="material-symbols-outlined text-xl">picture_as_pdf</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('reports.harvest_report') }} - {{ now()->format('M Y') }}</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">2.50 ميجابايت • PDF</p>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 rtl:rotate-180 text-lg">chevron_right</span>
                </div>
                
                 <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm flex items-center gap-4 cursor-pointer hover:bg-slate-50 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center text-green-600">
                        <span class="material-symbols-outlined text-xl">table_chart</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('reports.soil_analysis') }}</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">800 كيلوبايت • XLSX</p>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 rtl:rotate-180 text-lg">chevron_right</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
