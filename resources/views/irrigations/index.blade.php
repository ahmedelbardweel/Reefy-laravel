@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-24 pt-20">
    <!-- Header -->
    <header class="px-5 py-4 bg-white dark:bg-slate-900 fixed top-0 w-full max-w-md mx-auto z-30 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center transition-all bg-opacity-95 backdrop-blur-sm">
        <a href="{{ route('dashboard') }}" class="p-2 -mr-2 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
            <span class="material-symbols-outlined rtl:rotate-180">arrow_forward</span>
        </a>
        <h1 class="text-lg font-bold font-display text-slate-900 dark:text-white">سجل الري</h1>
        <div class="w-8"></div>
    </header>

    <div class="p-5 space-y-6">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-500 text-white p-4 rounded-3xl shadow-lg shadow-blue-500/20">
                <span class="block text-xs font-bold opacity-80 mb-1">إجمالي اليوم</span>
                <p class="text-3xl font-black">{{ $totalToday }} <span class="text-sm font-medium">لتر</span></p>
            </div>
            <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm">
                <span class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">عمليات الري</span>
                <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $irrigations->count() }}</p>
            </div>
        </div>

        <!-- Detailed Log -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-900 dark:text-white">تفاصيل العمليات</h3>
            </div>
            
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($irrigations as $irrigation)
                <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">water_drop</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-white text-sm">
                                    {{ $irrigation->crop ? ($irrigation->crop->name_en && app()->getLocale() == 'en' ? $irrigation->crop->name_en : $irrigation->crop->name) : 'محصول محذوف' }}
                                </h4>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">{{ $irrigation->notes }}</p>
                            </div>
                        </div>
                        <span class="font-black text-blue-600 dark:text-blue-400 dir-ltr text-sm">+{{ $irrigation->amount_liters }} L</span>
                    </div>
                    <div class="flex items-center gap-4 text-[10px] text-slate-400 pr-13">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined !text-[12px]">calendar_today</span>
                            {{ $irrigation->date->format('Y-m-d') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined !text-[12px]">schedule</span>
                            {{ $irrigation->created_at->format('h:i A') }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-500">
                    لا توجد سجلات ري حتى الآن
                </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($irrigations->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $irrigations->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
