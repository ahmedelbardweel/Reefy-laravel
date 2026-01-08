@extends('layouts.market')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('orders.index') }}" class="size-10 rounded-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark flex items-center justify-center text-text-secondary-light dark:text-text-secondary-dark hover:border-primary hover:text-primary transition-all">
            <span class="material-symbols-outlined rtl:rotate-180">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-black text-text-main dark:text-white tracking-tight flex items-center gap-3">
                {{ __('orders.details_title') }} #{{ $order->id }}
                <span class="px-2.5 py-1 rounded-md text-xs font-bold
                    @if($order->status == 'completed') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                    @elseif($order->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                    @elseif($order->status == 'cancelled') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                    @else bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </h1>
            <p class="text-text-secondary-light dark:text-text-secondary-dark">{{ $order->created_at->format('Y/m/d H:i') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content: Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark overflow-hidden">
                <div class="p-4 border-b border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark/50">
                    <h2 class="font-bold text-lg text-text-main dark:text-white">{{ __('orders.items') }}</h2>
                </div>
                <div class="divide-y divide-border-light dark:divide-border-dark">
                    @foreach($order->items as $item)
                    <div class="p-4 flex gap-4">
                        <div class="size-20 rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden shrink-0">
                            <img src="{{ $item->product->image_url ?? 'https://placehold.co/200?text=Product' }}" class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-text-main dark:text-white">{{ $item->product->name }}</h3>
                                <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">{{ __('orders.seller') }}: {{ optional($item->product->user)->name ?? __('market.unknown_seller') }}</p>
                            </div>
                            <div class="flex items-end justify-between">
                                <span class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark">x{{ $item->quantity }}</span>
                                <span class="font-bold text-primary">{{ $item->total }} {{ __('currency') ?? 'SAR' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar: Summary -->
        <div class="space-y-6">
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark p-5 sticky top-24">
                <h2 class="font-bold text-lg text-text-main dark:text-white mb-4">{{ __('orders.summary') }}</h2>
                
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-text-secondary-light dark:text-text-secondary-dark">{{ __('orders.subtotal') }}</span>
                    <span class="text-sm font-bold text-text-main dark:text-white">{{ $order->total_amount }} {{ __('currency') ?? 'SAR' }}</span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm text-text-secondary-light dark:text-text-secondary-dark">{{ __('orders.tax') }}</span>
                    <span class="text-sm font-bold text-text-main dark:text-white">0.00 {{ __('currency') ?? 'SAR' }}</span>
                </div>
                
                <div class="h-px bg-border-light dark:border-border-dark my-4"></div>
                
                <div class="flex justify-between items-center mb-6">
                    <span class="text-base font-bold text-text-main dark:text-white">{{ __('orders.total') }}</span>
                    <span class="text-xl font-black text-primary">{{ $order->total_amount }} {{ __('currency') ?? 'SAR' }}</span>
                </div>

                @if($order->status == 'pending')
                <button class="w-full py-3 rounded-lg bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 font-bold hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                    {{ __('orders.cancel') }}
                </button>
                @endif
                <a href="{{ route('chat.index') }}" class="w-full mt-3 py-3 rounded-lg border border-border-light dark:border-border-dark text-text-main dark:text-white font-bold hover:bg-background-light dark:hover:bg-background-dark transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">support_agent</span>
                    {{ __('orders.help') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
