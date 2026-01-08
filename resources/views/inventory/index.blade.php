@extends('layouts.app')

@section('content')
<!-- Top Navbar -->
<header class="sticky top-0 z-40 bg-surface-light dark:bg-surface-dark border-b border-border-light dark:border-border-dark px-4 lg:px-10 py-3 shadow-sm flex items-center justify-between gap-4">
    <!-- Logo Section (Hidden on mobile as it's in sidebar) -->
    <div class="hidden lg:flex items-center gap-2 lg:gap-4 shrink-0">
        <div class="size-10 bg-primary/20 rounded-lg flex items-center justify-center text-primary">
            <span class="material-symbols-outlined text-3xl">agriculture</span>
        </div>
        <h2 class="text-text-main dark:text-white text-xl font-black tracking-tight">{{ __('market.title') }}</h2>
    </div>

    <!-- Mobile Menu Button -->
    <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 text-text-main dark:text-white">
        <span class="material-symbols-outlined">menu</span>
    </button>

    <!-- Search Bar -->
    <div class="flex-1 max-w-2xl px-4 lg:px-12">
        <form action="{{ route('inventory.index') }}" method="GET" class="relative flex w-full items-center">
            <input type="hidden" name="mode" value="{{ $mode }}">
            <input type="hidden" name="category" value="{{ $category }}">
            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none text-text-secondary-light dark:text-text-secondary-dark">
                <span class="material-symbols-outlined">search</span>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" class="block w-full rounded-xl border-none bg-background-light dark:bg-background-dark py-2.5 {{ app()->getLocale() == 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} text-sm text-text-main dark:text-white focus:ring-2 focus:ring-primary placeholder:text-text-secondary-light dark:placeholder:text-text-secondary-dark" placeholder="{{ __('market.search_placeholder') }}"/>
        </form>
    </div>

    <!-- Actions & Nav -->
    <div class="flex items-center gap-2 lg:gap-6 shrink-0">
        <nav class="hidden xl:flex items-center gap-6">
            <a href="{{ route('inventory.index', ['mode' => 'market']) }}" class="text-sm font-bold {{ $mode === 'market' ? 'text-primary' : 'text-text-main dark:text-white hover:text-primary' }} transition-colors">{{ __('market.nav.home') }}</a>
            <a href="{{ route('inventory.index', ['mode' => 'inventory']) }}" class="text-sm font-bold {{ $mode === 'inventory' ? 'text-primary' : 'text-text-secondary-light dark:text-text-secondary-dark hover:text-primary' }} transition-colors">{{ __('market.nav.my_products') }}</a>
            <a href="{{ route('orders.index') }}" class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-primary transition-colors">{{ __('market.nav.orders') }}</a>
        </nav>
        <div class="flex items-center gap-2 border-r border-l-0 {{ app()->getLocale() == 'ar' ? 'border-r border-l-0 pr-2 lg:pr-6' : 'border-l border-r-0 pl-2 lg:pl-6' }} border-border-light dark:border-border-dark">
            <a href="{{ route('cart.index') }}" class="relative p-2 rounded-lg hover:bg-background-light dark:hover:bg-background-dark text-text-main dark:text-gray-200 transition-colors group">
                <span class="material-symbols-outlined">shopping_cart</span>
                @php
                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->first()?->items()->count() ?? 0;
                @endphp
                @if($cartCount > 0)
                <span class="absolute top-1 {{ app()->getLocale() == 'ar' ? 'left-1' : 'right-1' }} size-2 bg-primary rounded-full animate-pulse"></span>
                @endif
            </a>
            <button class="p-2 rounded-lg hover:bg-background-light dark:hover:bg-background-dark text-text-main dark:text-gray-200 transition-colors">
                <span class="material-symbols-outlined">notifications</span>
            </button>
             <div class="size-9 rounded-full bg-cover bg-center border border-border-light dark:border-border-dark" style='background-image: url("{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.Auth::user()->name }}");'></div>
        </div>
    </div>
</header>

<!-- Main Layout -->
<div class="flex-1 max-w-[1440px] mx-auto w-full p-4 lg:p-8 flex flex-col lg:flex-row gap-8 overflow-y-auto scrollbar-hide">
    <!-- Sidebar Filters (Desktop) -->
    <aside class="w-full lg:w-64 shrink-0 flex flex-col gap-6 hidden lg:block">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-5 border border-border-light dark:border-border-dark shadow-sm sticky top-24">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-text-main dark:text-white">{{ __('market.filters.title') }}</h3>
                <a href="{{ route('inventory.index', ['mode' => $mode]) }}" class="text-xs font-bold text-primary hover:underline">{{ __('market.filters.clear') }}</a>
            </div>
            
            @if($mode === 'market')
            <!-- Price Range -->
            <div class="mb-6">
                <h4 class="font-bold text-sm mb-3 flex items-center justify-between">
                    {{ __('market.filters.price') }}
                    <span class="material-symbols-outlined text-sm text-text-secondary-light dark:text-text-secondary-dark">expand_less</span>
                </h4>
                <div class="px-2">
                    <input class="w-full accent-primary h-1 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer" max="1000" min="0" type="range" 
                           value="{{ request('max_price', 1000) }}" 
                           oninput="document.getElementById('price-val').innerText = this.value" 
                           onchange="updateFilter('max_price', this.value)"/>
                    <div class="flex justify-between text-xs font-medium text-text-secondary-light dark:text-text-secondary-dark mt-2">
                        <span>0</span>
                        <span><span id="price-val">{{ request('max_price', 1000) }}</span> SAR</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Categories Checkbox -->
            <div class="mb-6">
                <h4 class="font-bold text-sm mb-3">{{ __('market.filters.categories') }}</h4>
                <div class="space-y-2">
                    @php
                        $filterCats = [
                            'seeds' => __('inventory.category.seeds'),
                            'fertilizers' => __('inventory.category.fertilizers'),
                            'equipment' => __('inventory.category.equipment'),
                             'pesticides' => __('inventory.category.pesticides'),
                             'harvest' => __('inventory.category.harvest')
                        ];
                    @endphp
                    @foreach($filterCats as $key => $label)
                    <a href="{{ route('inventory.index', array_merge(request()->query(), ['category' => $key])) }}" class="flex items-center gap-3 cursor-pointer group">
                        <div class="size-4 rounded border {{ $category == $key ? 'bg-primary border-primary' : 'border-gray-300 dark:border-gray-600' }} flex items-center justify-center">
                            @if($category == $key) <span class="material-symbols-outlined text-white text-[10px] font-bold">check</span> @endif
                        </div>
                        <span class="text-sm {{ $category == $key ? 'text-primary font-bold' : 'text-text-secondary-light dark:text-text-secondary-dark group-hover:text-text-main dark:group-hover:text-white' }} transition-colors">{{ $label }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Rating (Market Only) -->
            @if($mode === 'market')
            <div>
                <h4 class="font-bold text-sm mb-3">{{ __('market.filters.rating') }}</h4>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input class="size-4 border-gray-300 text-primary focus:ring-primary" name="rating" type="radio"/>
                        <div class="flex text-yellow-400 text-sm">
                            <span class="material-symbols-outlined text-[18px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[18px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[18px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[18px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[18px] fill-current">star_half</span>
                        </div>
                        <span class="text-xs text-text-secondary-light dark:text-text-secondary-dark">4.5+</span>
                    </label>
                </div>
            </div>
            @endif
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 min-w-0">
        <!-- Page Heading & Breadcrumb -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="text-3xl lg:text-4xl font-black text-text-main dark:text-white tracking-tight">
                    {{ $mode === 'market' ? __('market.title') : __('inventory.mode_title') }}
                </h1>
                <p class="text-text-secondary-light dark:text-text-secondary-dark">
                    {{ $mode === 'market' ? __('market.subtitle') : __('inventory.subtitle') }}
                </p>
            </div>
            @if($mode === 'inventory')
            <a href="{{ route('inventory.create') }}" class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-primary text-background-dark font-bold hover:bg-primary-hover transition-colors shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">add</span>
                <span>{{ __('inventory.add_item') }}</span>
            </a>
            @endif
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-surface-light dark:bg-surface-dark p-5 rounded-xl border border-border-light dark:border-border-dark shadow-sm flex flex-col justify-between h-32 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark mb-1">{{ __('market.stats.total_products') }}</p>
                    <p class="text-3xl font-black text-text-main dark:text-white">{{ $mode === 'market' ? $marketStats['total_items'] : $stats['total_items'] }}</p>
                </div>
                <div class="relative z-10 flex items-center text-primary text-sm font-bold gap-1">
                    <span class="material-symbols-outlined text-lg">trending_up</span>
                    <span>+12%</span>
                </div>
                <span class="material-symbols-outlined absolute -left-4 -bottom-4 text-[100px] text-primary/5 group-hover:text-primary/10 transition-colors rotate-12">inventory_2</span>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark p-5 rounded-xl border border-border-light dark:border-border-dark shadow-sm flex flex-col justify-between h-32 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark mb-1">{{ __('market.stats.offers') }}</p>
                    <p class="text-3xl font-black text-text-main dark:text-white">{{ $marketStats['offers'] }}</p>
                </div>
                <div class="relative z-10 flex items-center text-orange-500 text-sm font-bold gap-1">
                    <span class="material-symbols-outlined text-lg">local_offer</span>
                    <span>30% OFF</span>
                </div>
                <span class="material-symbols-outlined absolute -left-4 -bottom-4 text-[100px] text-orange-500/5 group-hover:text-orange-500/10 transition-colors rotate-12">percent</span>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark p-5 rounded-xl border border-border-light dark:border-border-dark shadow-sm flex flex-col justify-between h-32 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark mb-1">{{ __('market.stats.stock_alerts') }}</p>
                    <p class="text-3xl font-black text-text-main dark:text-white">{{ $mode === 'market' ? 3 : $stats['low_stock'] }}</p>
                </div>
                <div class="relative z-10 flex items-center text-red-500 text-sm font-bold gap-1">
                    <span class="material-symbols-outlined text-lg">warning</span>
                    <span>Alerts</span>
                </div>
                <span class="material-symbols-outlined absolute -left-4 -bottom-4 text-[100px] text-red-500/5 group-hover:text-red-500/10 transition-colors rotate-12">low_priority</span>
            </div>
        </div>

        <!-- Chips / Quick Filters -->
        <div class="flex items-center gap-3 overflow-x-auto pb-4 mb-6 scrollbar-hide">
            <a href="{{ route('inventory.index', array_merge(request()->query(), ['category' => 'all'])) }}" class="shrink-0 h-10 px-5 rounded-full {{ $category == 'all' ? 'bg-primary text-background-dark font-bold' : 'bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark text-text-main dark:text-white hover:border-primary hover:text-primary' }} text-sm shadow-sm transition-all active:scale-95 flex items-center justify-center">
                {{ __('inventory.category.all') }}
            </a>
            @foreach($filterCats as $key => $label)
            <a href="{{ route('inventory.index', array_merge(request()->query(), ['category' => $key])) }}" class="shrink-0 h-10 px-5 rounded-full {{ $category == $key ? 'bg-primary text-background-dark font-bold' : 'bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark text-text-main dark:text-white hover:border-primary hover:text-primary' }} text-sm font-medium transition-all active:scale-95 flex items-center gap-2">
                @if($key == 'seeds') <span class="material-symbols-outlined text-[18px]">grass</span>
                @elseif($key == 'fertilizers') <span class="material-symbols-outlined text-[18px]">compost</span>
                @elseif($key == 'equipment') <span class="material-symbols-outlined text-[18px]">agriculture</span>
                @elseif($key == 'pesticides') <span class="material-symbols-outlined text-[18px]">pest_control</span>
                @else <span class="material-symbols-outlined text-[18px]">category</span>
                @endif
                {{ $label }}
            </a>
            @endforeach
        </div>

        <!-- GRID CONTENT -->
        @if($mode === 'market')
        <!-- Market Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($products as $product)
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl overflow-hidden border border-border-light dark:border-border-dark hover:shadow-lg transition-all duration-300 group flex flex-col">
                <a href="{{ route('market.show', $product->id) }}" class="block">
                    <div class="relative h-48 w-full overflow-hidden bg-gray-100 dark:bg-gray-800">
                        <img alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{ $product->image_url ?? 'https://placehold.co/400x300?text=' . urlencode($product->name) }}"/>
                        <div class="absolute top-3 {{ app()->getLocale() == 'ar' ? 'left-3' : 'right-3' }} bg-surface-light dark:bg-black/50 backdrop-blur-md rounded-lg px-2 py-1 flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-outlined text-yellow-400 text-sm fill-current filled">star</span>
                            <span class="text-xs font-bold text-text-main dark:text-white">4.8</span>
                        </div>
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-primary bg-primary/10 px-2 py-1 rounded-md">{{ __('inventory.category.' . $product->category) }}</span>
                            <span class="text-xs text-text-secondary-light dark:text-text-secondary-dark">{{ optional($product->user)->name ?? 'Mawred' }}</span>
                        </div>
                        <h3 class="font-bold text-lg text-text-main dark:text-white mb-2 leading-tight line-clamp-2">{{ $product->name }}</h3>
                    </div>
                </a>
                    <div class="flex items-end justify-between mt-4">
                        <div class="flex flex-col">
                            <span class="text-xl font-black text-text-main dark:text-white">{{ $product->price }} SAR</span>
                        </div>
                        @if(auth()->user()->role === 'client')
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="bg-primary hover:bg-primary-hover text-background-dark font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                                <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                                {{ __('market.btn.add') }}
                            </button>
                        </form>
                        @endif
                    </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">storefront</span>
                <p class="text-text-secondary-light dark:text-text-secondary-dark">{{ __('market.empty') }}</p>
            </div>
            @endforelse
        </div>
        
        @else
        <!-- User Inventory Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($items as $item)
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl overflow-hidden border border-border-light dark:border-border-dark hover:shadow-lg transition-all duration-300 group flex flex-col">
                <div class="relative h-48 w-full overflow-hidden bg-gray-100 dark:bg-gray-800">
                    <img alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{ $item->image_url ?? 'https://placehold.co/400x300?text=' . urlencode($item->name) }}"/>
                    <div class="absolute top-3 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} bg-primary text-background-dark text-xs font-bold px-2 py-1 rounded-md shadow-sm">
                        {{ $item->quantity_value }} {{ $item->unit }}
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-primary bg-primary/10 px-2 py-1 rounded-md">{{ __('inventory.category.' . $item->category) }}</span>
                    </div>
                    <h3 class="font-bold text-lg text-text-main dark:text-white mb-2 leading-tight">{{ $item->name }}</h3>
                    <div class="flex-1"></div>
                    <div class="grid grid-cols-2 gap-2 mt-4">
                        <a href="{{ route('inventory.edit', $item->id) }}" class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark hover:border-primary text-text-main dark:text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors text-sm">
                            <span class="material-symbols-outlined text-lg">edit</span>
                            {{ __('inventory.edit') }}
                        </a>
                        <a href="{{ route('inventory.sell.view', $item->id) }}" class="bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 font-bold py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors text-sm hover:bg-orange-200 dark:hover:bg-orange-900/50">
                            <span class="material-symbols-outlined text-lg">sell</span>
                            {{ __('inventory.sell') }}
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">inventory_2</span>
                <p class="text-text-secondary-light dark:text-text-secondary-dark">{{ __('inventory.empty') }}</p>
                <a href="{{ route('inventory.create') }}" class="inline-flex items-center gap-2 mt-4 text-primary font-bold hover:underline">
                    <span class="material-symbols-outlined">add_circle</span>
                    {{ __('inventory.add_item') }}
                </a>
            </div>
            @endforelse
        </div>
        @endif

        @if($mode === 'market' && count($products) > 0)
        <!-- Load More -->
        <div class="mt-12 flex justify-center">
            <button class="px-8 py-3 rounded-full border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-text-main dark:text-white font-bold text-sm hover:bg-background-light dark:hover:bg-background-dark transition-colors shadow-sm flex items-center gap-2">
                {{ __('market.load_more') }}
                <span class="material-symbols-outlined text-primary">expand_more</span>
            </button>
        </div>
        @endif
    </main>
</div>

<style>
    /* Hide scrollbar for chrome/safari/opera */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    .scrollbar-hide {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
    }
</style>

<script>
    function updateFilter(key, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(key, value);
        window.location.href = url.toString();
    }
</script>
@endsection
