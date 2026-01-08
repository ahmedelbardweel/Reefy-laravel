@extends('layouts.market')

@section('content')
<main class="flex-1 w-full px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">{{ __('orders.title') }}</h1>
        <p class="text-slate-500 dark:text-slate-400">{{ __('orders.subtitle') }}</p>
    </div>

    <!-- Filter & Search Bar -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 border-b border-slate-200 dark:border-slate-700 pb-1">
        <!-- Tabs -->
        <div class="flex gap-8 relative top-[1px]">
            <button class="pb-3 border-b-2 border-primary text-slate-900 dark:text-white font-bold text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                {{ __('orders.active') }}
                <span class="bg-primary/20 text-green-800 dark:text-green-300 text-xs py-0.5 px-2 rounded-full font-grotesk">{{ $orders->whereIn('status', ['pending', 'processing', 'shipped'])->count() }}</span>
            </button>
            <button class="pb-3 border-b-2 border-transparent hover:border-slate-300 text-slate-500 dark:text-slate-400 font-medium text-sm flex items-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[20px]">history</span>
                {{ __('orders.history') }}
            </button>
        </div>
        <!-- Order Search -->
        <div class="relative w-full md:w-auto md:min-w-[320px] mb-2 md:mb-0">
            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                <span class="material-symbols-outlined text-[20px]">filter_list</span>
            </span>
            <input class="block w-full rounded-lg border-0 py-2 pr-10 pl-4 text-slate-900 ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary dark:bg-slate-800 dark:text-white dark:ring-slate-700 sm:text-sm bg-white dark:bg-opacity-50" placeholder="{{ __('orders.search_placeholder') }}" type="text"/>
        </div>
    </div>

    <!-- Orders Grid -->
    <div class="flex flex-col gap-6">
        @forelse($orders as $order)
            @php
                $firstItem = $order->items->first();
                $productName = $firstItem ? $firstItem->product->name : __('orders.unknown_product');
                $moreCount = $order->items->count() - 1;
                $displayTitle = $productName . ($moreCount > 0 ? " (+{$moreCount})" : "");
                
                $isActive = in_array($order->status, ['pending', 'processing', 'shipped']);
            @endphp
            
            @if($isActive)
            <!-- Active Order Card (Expanded) -->
            <div class="bg-surface-light dark:bg-[#1a2e1a] rounded-xl shadow-[0_2px_8px_rgba(0,0,0,0.04)] border border-slate-100 dark:border-[#2a3e2a] overflow-hidden group">
                <!-- Header -->
                <div class="p-5 flex flex-wrap md:flex-nowrap justify-between items-start gap-4 border-b border-slate-100 dark:border-[#2a3e2a] bg-slate-50/50 dark:bg-[#1a2e1a]/30">
                    <div class="flex gap-4">
                        <div class="size-16 rounded-lg bg-slate-200 dark:bg-slate-700 bg-cover bg-center shrink-0 border border-slate-200 dark:border-slate-600" style="background-image: url('{{ $firstItem && $firstItem->product->image_url ? $firstItem->product->image_url : "https://placehold.co/100" }}');"></div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ $displayTitle }}</h3>
                                <span class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400 font-grotesk">
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">tag</span> #{{ $order->id }}</span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">calendar_today</span> {{ $order->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1 min-w-[120px]">
                        <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('orders.total') }}</span>
                        <span class="text-xl font-bold text-slate-900 dark:text-white font-grotesk">{{ $order->total_amount }} {{ __('currency.sar') }}</span>
                    </div>
                </div>
                <!-- Timeline Visualization -->
                <div class="p-6 bg-white dark:bg-[#1a2e1a]">
                    <div class="relative">
                        <!-- Progress Bar Background -->
                        <div class="absolute top-1/2 left-0 right-0 h-1 bg-slate-100 dark:bg-slate-700 -translate-y-1/2 rounded-full hidden md:block z-0"></div>
                        <!-- Progress Bar Active (Mock 50% for Pending) -->
                        <div class="absolute top-1/2 right-0 {{ $order->status == 'pending' ? 'w-1/4' : ($order->status == 'processing' ? 'w-2/4' : 'w-3/4') }} h-1 bg-primary -translate-y-1/2 rounded-full hidden md:block z-0 transition-all duration-500"></div>
                        
                        <div class="relative z-10 grid grid-cols-1 md:grid-cols-4 gap-6 md:gap-0 text-center">
                             <!-- Step 1: Placed -->
                            <div class="flex md:flex-col items-center gap-4 md:gap-2">
                                <div class="size-8 rounded-full bg-primary flex items-center justify-center text-slate-900 font-bold shrink-0 ring-4 ring-white dark:ring-[#1a2e1a] shadow-sm">
                                    <span class="material-symbols-outlined text-[18px]">check</span>
                                </div>
                                <div class="text-right md:text-center">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ __('orders.status.placed') }}</p>
                                    <p class="text-xs text-slate-500 font-grotesk">{{ $order->created_at->format('H:i A') }}</p>
                                </div>
                            </div>
                             <!-- Step 3: Processing/On Way (Active) -->
                            <div class="flex md:flex-col items-center gap-4 md:gap-2">
                                <div class="size-10 rounded-full {{ $order->status != 'pending' ? 'bg-primary' : 'bg-slate-200 dark:bg-slate-700' }} flex items-center justify-center text-white font-bold shrink-0 ring-4 ring-white dark:ring-[#1a2e1a] shadow-lg scale-110">
                                    <span class="material-symbols-outlined text-[20px] {{ $order->status != 'pending' ? 'text-black' : 'text-slate-400' }}">local_shipping</span>
                                </div>
                                <div class="text-right md:text-center">
                                    <p class="text-sm font-bold {{ $order->status != 'pending' ? 'text-primary' : 'text-slate-500' }}">{{ __('orders.status.processing') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer Actions -->
                <div class="p-4 bg-slate-50 dark:bg-[#1a2e1a]/50 border-t border-slate-100 dark:border-[#2a3e2a] flex justify-end gap-3">
                    <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 rounded-lg text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-600 bg-white dark:bg-[#1a2e1a] shadow-sm transition-all">
                        {{ __('orders.details') }}
                    </a>
                </div>
            </div>
            @else
            <!-- Past Order Card -->
            <div class="bg-surface-light dark:bg-[#1a2e1a] rounded-xl border border-slate-100 dark:border-[#2a3e2a] overflow-hidden opacity-90 hover:opacity-100 transition-opacity">
                <div class="p-5 flex flex-wrap md:flex-nowrap justify-between items-center gap-4">
                    <div class="flex gap-4 items-center">
                        <div class="size-16 rounded-lg bg-slate-200 dark:bg-slate-700 bg-cover bg-center shrink-0 border border-slate-200 dark:border-slate-600 grayscale group-hover:grayscale-0 transition-all" style="background-image: url('{{ $firstItem && $firstItem->product->image_url ? $firstItem->product->image_url : "https://placehold.co/100" }}');"></div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-bold text-lg text-slate-700 dark:text-slate-200">{{ $displayTitle }}</h3>
                                <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:text-green-400 ring-1 ring-inset ring-green-600/20 gap-1">
                                    <span class="size-1.5 rounded-full bg-green-500"></span>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400 font-grotesk">
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">tag</span> #{{ $order->id }}</span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span>{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 self-end md:self-center w-full md:w-auto justify-between md:justify-end">
                         <div class="hidden md:flex flex-col items-end gap-1 min-w-[120px] border-r border-slate-100 dark:border-slate-700 pr-4 mr-2">
                            <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('orders.total') }}</span>
                            <span class="text-xl font-bold text-slate-900 dark:text-white font-grotesk">{{ $order->total_amount }} {{ __('currency.sar') }}</span>
                        </div>
                        <div class="flex gap-2">
                              <a href="{{ route('orders.show', $order->id) }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                {{ __('orders.details') }}
                            </a>
                            <button class="px-4 py-2 rounded-lg text-sm font-bold text-slate-900 bg-primary hover:bg-[#0fdc0f] shadow-[0_0_15px_rgba(19,236,19,0.3)] transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">refresh</span>
                                {{ __('orders.reorder') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="flex flex-col items-center justify-center py-20 bg-surface-light dark:bg-[#1a2e1a] rounded-xl border border-border-light dark:border-[#2a3e2a]">
                <div class="size-24 rounded-full bg-background-light dark:bg-background-dark flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-5xl text-text-secondary-light dark:text-text-secondary-dark">inventory_2</span>
                </div>
                <h2 class="text-xl font-bold text-text-main dark:text-white mb-2">{{ __('orders.empty') }}</h2>
                <a href="{{ route('market.index') }}" class="mt-4 px-6 py-2 bg-primary text-black font-bold rounded-lg hover:bg-primary-hover transition-colors">
                    {{ __('orders.browse_market') }}
                </a>
            </div>
        @endforelse
    </div>
</main>
@endsection
