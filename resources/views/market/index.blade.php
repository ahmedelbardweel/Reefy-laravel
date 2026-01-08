@extends('layouts.market')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header / Categories -->
    <div class="bg-white dark:bg-[#1a2e1a] rounded-xl p-4 shadow-sm border border-[#f0f4f0] dark:border-[#2a3e2a]">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-[#111811] dark:text-white font-display">{{ __('market.title') }}</h1>
                <p class="text-sm text-[#618961] dark:text-gray-400">{{ __('market.subtitle') }}</p>
            </div>
            
            <!-- Categories -->
            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 hide-scrollbar">
                <a href="{{ route('market.index') }}" 
                    class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-bold transition-all {{ !request('category') ? 'bg-primary text-[#111811]' : 'bg-[#f0f4f0] dark:bg-[#253825] text-[#111811] dark:text-gray-300 hover:bg-primary/20' }}">
                    {{ __('market.all') }}
                </a>
                @php
                    $categories = [
                        'vegetables' => __('market.category.vegetables'),
                        'fruits' => __('market.category.fruits'),
                        'grains' => __('market.category.grains'),
                        'dates' => __('market.category.dates'),
                        'honey' => __('market.category.honey')
                    ];
                @endphp
                @foreach($categories as $key => $label)
                <a href="{{ route('market.index', ['category' => $key]) }}" 
                    class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-bold transition-all {{ request('category') == $key ? 'bg-primary text-[#111811]' : 'bg-[#f0f4f0] dark:bg-[#253825] text-[#111811] dark:text-gray-300 hover:bg-primary/20' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($products as $product)
        <a href="{{ route('market.show', $product->id) }}" class="group bg-white dark:bg-[#1a2e1a] rounded-xl overflow-hidden shadow-sm border border-[#f0f4f0] dark:border-[#2a3e2a] hover:border-primary hover:shadow-md transition-all h-full flex flex-col">
            <div class="aspect-[4/3] relative overflow-hidden bg-[#f0f4f0] dark:bg-black/20">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center text-[#618961]">
                        <span class="material-symbols-outlined text-4xl">inventory_2</span>
                    </div>
                @endif
                <div class="absolute top-2 start-2 bg-white/90 dark:bg-black/60 backdrop-blur px-2 py-1 rounded-lg text-[10px] font-bold shadow-sm text-[#111811] dark:text-white">
                    {{ $product->stock_quantity }} {{ $product->unit ?? __('market.unit') }}
                </div>
            </div>
            <div class="p-3 flex flex-col flex-1">
                <div class="flex items-start justify-between gap-1 mb-1">
                    <h3 class="font-bold text-[#111811] dark:text-white text-base line-clamp-1 group-hover:text-primary transition-colors">{{ $product->name }}</h3>
                </div>
                <div class="flex items-center justify-between mt-auto">
                    <div class="flex items-center gap-1 text-[11px] text-[#618961] dark:text-gray-400">
                        <span class="material-symbols-outlined text-[14px]">store</span>
                        <span class="truncate max-w-[80px]">{{ $product->user->name ?? __('market.farmer') }}</span>
                    </div>
                    <span class="text-primary font-bold text-lg whitespace-nowrap">{{ $product->price }} <span class="text-xs">{{ __('currency.sar') }}</span></span>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full py-16 flex flex-col items-center justify-center text-center">
            <div class="size-20 bg-[#f0f4f0] dark:bg-[#253825] rounded-full flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-4xl text-[#618961]">search_off</span>
            </div>
            <h3 class="font-bold text-lg text-[#111811] dark:text-white">{{ __('market.no_products') }}</h3>
            <p class="text-sm text-[#618961] mt-1 max-w-xs mx-auto">{{ __('market.no_products_desc') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

