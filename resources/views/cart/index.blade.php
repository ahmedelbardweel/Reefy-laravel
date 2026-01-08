@extends('layouts.market')

@section('content')
<main class="flex-1 w-full max-w-[1440px] mx-auto px-4 lg:px-10 py-6 lg:py-10">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm mb-6 text-text-secondary-light dark:text-text-secondary-dark">
        <a class="hover:text-primary transition-colors" href="{{ route('dashboard') }}">{{ __('nav.dashboard') }}</a>
        <span class="material-symbols-outlined text-[16px] rtl:rotate-180">chevron_right</span>
        <a class="hover:text-primary transition-colors" href="{{ route('inventory.index', ['mode' => 'market']) }}">{{ __('nav.market') }}</a>
        <span class="material-symbols-outlined text-[16px] rtl:rotate-180">chevron_right</span>
        <span class="text-text-main dark:text-white font-medium">{{ __('cart.title') }}</span>
    </div>

    <!-- Page Heading -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-text-main dark:text-white mb-2">{{ __('cart.title') }}</h1>
            <p class="text-text-secondary-light dark:text-text-secondary-dark">{{ __('cart.items_count') }}: <span class="font-bold text-primary dark:text-primary">{{ $cart->items->count() }}</span></p>
        </div>
        <a class="text-primary hover:text-primary-hover dark:hover:text-[#4ff44f] text-sm font-bold flex items-center gap-1 transition-colors" href="{{ route('market.index') }}">
            <span class="material-symbols-outlined text-[20px]">add</span>
            {{ __('cart.browse_market') }}
        </a>
    </div>

    @if($cart->items->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Cart Items List (Right Side in RTL) -->
        <div class="lg:col-span-8 flex flex-col gap-4">
            <!-- Table Header (Hidden on mobile) -->
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark text-sm font-bold text-text-secondary-light dark:text-text-secondary-dark">
                <div class="col-span-6">{{ __('orders.items') }}</div>
                <div class="col-span-2 text-center">{{ __('market.filters.price') }}</div>
                <div class="col-span-2 text-center">{{ __('orders.actions') }}</div> <!-- Quantity replacement -->
                <div class="col-span-2 text-center">{{ __('orders.total') }}</div>
            </div>

            @foreach($cart->items as $item)
            <!-- Cart Item -->
            <div class="group relative flex flex-col md:grid md:grid-cols-12 gap-4 items-center p-4 md:p-6 bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm hover:shadow-md transition-shadow">
                <!-- Product Info -->
                <div class="w-full md:col-span-6 flex items-center gap-4">
                    <div class="shrink-0 size-20 md:size-24 rounded-lg bg-gray-100 dark:bg-gray-800 bg-center bg-cover border border-border-light dark:border-border-dark" style='background-image: url("{{ $item->product->image_url ?? "https://placehold.co/200?text=Product" }}");'></div>
                    <div class="flex flex-col gap-1">
                        <h3 class="font-bold text-lg text-text-main dark:text-white leading-tight">{{ $item->product->name }}</h3>
                        <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">storefront</span>
                            {{ optional($item->product->user)->name ?? __('market.unknown_seller') }}
                        </p>
                        <span class="md:hidden text-primary font-bold mt-1">{{ $item->product->price }} {{ __('currency') ?? 'SAR' }}</span>
                    </div>
                </div>
                
                <!-- Price (Desktop) -->
                <div class="hidden md:flex md:col-span-2 justify-center font-medium text-text-secondary-light dark:text-text-secondary-dark">
                    {{ $item->product->price }} {{ __('currency') ?? 'SAR' }}
                </div>

                <!-- Quantity -->
                <div class="w-full md:w-auto md:col-span-2 flex items-center justify-between md:justify-center bg-background-light dark:bg-background-dark border dark:border-border-dark rounded-lg p-1">
                     <!-- 
                        NOTE: Actual quantity update logic needs JS or separate forms.
                        For now, display quantity visually. To implement interactions properly, we need update routes.
                        Assuming static viewing for MVP step or simple increment via full reload if desired, 
                        but standard cart usually has AJAX.
                        We will keep it simple: Read-only quantity or basic buttons if route existed. 
                        Since no update route exists in snippet, showing quantity.
                     -->
                    <span class="flex items-center justify-center size-8 text-text-main dark:text-white">{{ $item->quantity }}</span>
                </div>

                <!-- Total & Actions -->
                <div class="w-full md:w-auto md:col-span-2 flex items-center justify-between md:justify-center gap-4 mt-2 md:mt-0 pt-2 md:pt-0 border-t md:border-t-0 border-border-light dark:border-border-dark">
                    <span class="md:hidden text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark">{{ __('orders.total') }}:</span>
                    <div class="flex items-center gap-4">
                        <span class="font-bold text-lg text-text-main dark:text-white">{{ $item->quantity * $item->product->price }} {{ __('currency') ?? 'SAR' }}</span>
                        
                        <!-- Actions -->
                         <div class="flex items-center gap-2">
                             @if($item->product->user_id)
                             <!-- Chat with Farmer -->
                            <a href="{{ route('chat.user', $item->product->user->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="{{ __('chat.chat_with_farmer') }}">
                                <span class="material-symbols-outlined text-[20px]">chat</span>
                            </a>
                            
                            <!-- Negotiate -->
                            <a href="{{ route('chat.start', $item->product->id) }}" class="p-1.5 text-yellow-600 hover:bg-yellow-50 dark:text-yellow-400 dark:hover:bg-yellow-900/30 rounded-lg transition-colors" title="{{ __('chat.negotiate') }}">
                                <span class="material-symbols-outlined text-[20px]">handshake</span>
                            </a>
                            @endif

                            <!-- Remove Item Form -->
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="{{ __('inventory.delete') }}">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                         </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Sidebar (Left Side in RTL) -->
        <div class="lg:col-span-4 flex flex-col gap-6 sticky top-24">
            <!-- Summary Card -->
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark p-6 shadow-sm">
                <h2 class="text-xl font-bold mb-6 text-text-main dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">receipt_long</span>
                    {{ __('orders.summary') }}
                </h2>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-text-secondary-light dark:text-text-secondary-dark">{{ __('orders.subtotal') }}</span>
                        <span class="font-bold text-text-main dark:text-white">{{ $cart->items->sum(function($item){ return $item->quantity * $item->product->price; }) }} {{ __('currency') ?? 'SAR' }}</span>
                    </div>
                    <!-- Placeholders for Shipping/Tax if needed, static for now -->
                </div>
                
                <div class="border-t border-border-light dark:border-border-dark my-4"></div>
                
                <div class="flex justify-between items-end mb-6">
                    <span class="font-bold text-lg text-text-main dark:text-white">{{ __('orders.total') }}</span>
                    <div class="text-left">
                        <span class="block font-black text-2xl text-primary leading-none">{{ $cart->items->sum(function($item){ return $item->quantity * $item->product->price; }) }} {{ __('currency') ?? 'SAR' }}</span>
                    </div>
                </div>

                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full h-12 flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-black font-bold text-base rounded-xl transition-all shadow-[0_4px_14px_0_rgba(19,236,19,0.39)] hover:shadow-[0_6px_20px_rgba(19,236,19,0.23)]">
                        {{ __('cart.checkout') }}
                        <span class="material-symbols-outlined rtl:rotate-180">arrow_forward</span>
                    </button>
                </form>

                <div class="mt-4 flex justify-center gap-4 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                    <!-- Payment icons placeholder -->
                    <div class="h-6 w-10 bg-contain bg-center bg-no-repeat" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDTkgV0LasyETO7-4PFtMYkAL-IikY3CF7-69mUsKPtGsSOF75kfhFsSe9MX1d84IDNxlnulwFfXAY3TcqBXT6YmRlZrY_-Dyw3oLRBbZhwiSm8ye0FfWjlZBK5JiOJ6XEBiu7t-7oXww31O8dpsyKGkqNTU1XjmvKfM61b0mM_SMqxPvMRJBlMGZ7PXGoo5u7vViisbF8CronI39YfUQK4KR00Si8XptLkAWvXEGXaohZFKB2aRT3ExQkuXgrcZM4NUZTtcD-MRQ')"></div>
                    <div class="h-6 w-10 bg-contain bg-center bg-no-repeat" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAMJcg2P5uOXfsQCUgv7_WLSmk0aim2VHcA5zYrXolTspnyktS_I-jZ6Klias_2A893CadZ5FXa59O8VpImKFj4804cAIytjiVRjp454kZQ-iZRURzqPtRAlmZK-ZpPYnjM_U95yJf5Hf5xf7Y6Pnt2YsOR5ZoPzH1yHZpdmT7JjZwhaiqDKHIKIITjPlyjW69wG9LhEjsRjEUhyxluBcUkOn-kZpSN0MLA29NNZgvdsb6SkGTAUNHrDE5yVQwlRMpH0SmZUnqvbA')"></div>
                </div>
            </div>

            <!-- Trust Badge -->
            <div class="flex items-center gap-3 p-4 bg-green-50 dark:bg-[#102210] border border-green-100 dark:border-primary/20 rounded-lg">
                <span class="material-symbols-outlined text-primary text-3xl">verified_user</span>
                <div>
                    <p class="font-bold text-sm text-text-main dark:text-white">تسوق آمن 100%</p>
                    <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">نضمن لك جودة المنتجات واسترجاع الأموال.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations Section (Optional / Mock data) -->
    <div class="mt-16 border-t border-border-light dark:border-border-dark pt-10">
        <h3 class="text-2xl font-bold text-text-main dark:text-white mb-6">مزارعون اشتروا أيضاً</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Mock Items - In real app, these would come from controller -->
             <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark overflow-hidden group cursor-pointer hover:border-primary transition-colors">
                <div class="aspect-[4/3] bg-gray-100 dark:bg-gray-800 bg-center bg-cover relative group-hover:scale-105 transition-transform duration-500" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuADu8isBcDLJ4AO6Ef9CIXaRc5PTLptYY1XO3bIErOmYnw5rEeyD6pTdDd-w4ccQUaot2wvMUeb4hWMfmdDcOF1TS0paRq98zx9RHrsIBhyCgYJGHynJBEcgbTE0mHCQjpP1xIhjXAYxQDJ4CEjGzEl21YMLzxTg_nSvQwRvbiTs18D8TdnX1b5YEyTNYUr9dvgYJmPLTetFUj5zvsvMvcN98r1hm5GJAusgm-SqIAHZACpcqM2K6aI2O3Nl7oBTKMrVDPYds3fCA");'></div>
                <div class="p-3">
                    <h4 class="font-bold text-text-main dark:text-white truncate">نظام ري بالتنقيط</h4>
                    <p class="text-primary font-bold text-sm mt-1">120 SAR</p>
                </div>
            </div>
             <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark overflow-hidden group cursor-pointer hover:border-primary transition-colors">
                <div class="aspect-[4/3] bg-gray-100 dark:bg-gray-800 bg-center bg-cover relative group-hover:scale-105 transition-transform duration-500" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD0uRSupU_-YTrDKF65b7GX6qdbgz5RZ0-s9QSLIfgWcBuE5nzrw7CaMkfxiSwdwvj-DeevhkcDvzF5cNLNXX6IzcQ3u756r_ItS8YMrxlv235JCBBvaUQEqYglxEVQicSp6vUD4LL8T8CvUAULfUGxgcDFO5UGEBz4c0-3mx_aCjtLC0b9OLLKTkBrDM_gWcRulu4El4vCLfi6BWEa2_uN2lTfOXuwOPRdaRY-CejdUDMC1WVV95Bw3O7q8wwfxGjhZBr61Uqoug");'></div>
                <div class="p-3">
                    <h4 class="font-bold text-text-main dark:text-white truncate">بذور طماطم</h4>
                    <p class="text-primary font-bold text-sm mt-1">45 SAR</p>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-32 h-32 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600">shopping_cart_off</span>
        </div>
        <h2 class="text-xl font-bold text-text-main dark:text-white mb-2">{{ __('cart.empty_title') }}</h2>
        <p class="text-text-secondary-light dark:text-text-secondary-dark mb-8 max-w-xs mx-auto">{{ __('cart.empty_desc') }}</p>
        <a href="{{ route('inventory.index', ['mode' => 'market']) }}" class="px-8 py-3 bg-primary text-background-dark rounded-xl font-bold shadow-lg shadow-primary/20 hover:bg-primary-hover transition-all">
            {{ __('cart.browse_market') }}
        </a>
    </div>
    @endif
</main>
@endsection
