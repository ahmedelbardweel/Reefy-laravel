@extends('layouts.app')

@section('content')
<!-- Header with Search and Add -->
<header class="w-full px-6 py-5 sm:px-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-background-light dark:bg-background-dark shrink-0 border-b border-border-light dark:border-border-dark">
    <div class="flex flex-col gap-1">
        <h2 class="text-3xl font-black leading-tight tracking-tight">{{ __('inventory.mode_title') }}</h2>
        <p class="text-text-secondary-light dark:text-text-secondary-dark text-base">{{ __('inventory.subtitle') }}</p>
    </div>
    
    <div class="flex items-center gap-3">
         <!-- Search -->
        <div class="relative w-full sm:w-64">
             <form action="{{ route('inventory.index') }}" method="GET">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary-light dark:text-text-secondary-dark">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('market.search_placeholder') }}" 
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-none bg-surface-light dark:bg-surface-dark focus:ring-2 focus:ring-primary text-sm shadow-sm"
                >
            </form>
        </div>

        <a href="{{ route('inventory.create') }}" class="flex items-center justify-center gap-2 rounded-xl h-12 px-6 bg-primary hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 text-background-dark text-sm font-bold">
            <span class="material-symbols-outlined text-[20px]">add</span>
            <span class="truncate">{{ __('inventory.add_item') }}</span>
        </a>
    </div>
</header>

<!-- Main Scrollable Content -->
<div class="flex-1 overflow-y-auto px-6 sm:px-10 pb-10">
    <div class="max-w-7xl mx-auto py-8">
        
        <!-- Category Chips -->
        <div class="flex items-center gap-3 overflow-x-auto pb-4 mb-6 scrollbar-hide">
             <a href="{{ route('inventory.index') }}" 
                class="shrink-0 h-10 px-5 rounded-full {{ !request('category') ? 'bg-primary text-background-dark font-bold' : 'bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark text-text-main dark:text-white hover:border-primary hover:text-primary' }} text-sm shadow-sm transition-all active:scale-95 flex items-center justify-center">
                {{ __('inventory.category.all') }}
            </a>
            @php
                $categories = [
                    'seeds' => __('inventory.category.seeds'),
                    'fertilizers' => __('inventory.category.fertilizers'),
                    'equipment' => __('inventory.category.equipment'),
                    'pesticides' => __('inventory.category.pesticides'),
                    'harvest' => __('inventory.category.harvest')
                ];
            @endphp
            @foreach($categories as $key => $label)
            <a href="{{ route('inventory.index', ['category' => $key]) }}" 
               class="shrink-0 h-10 px-5 rounded-full {{ request('category') == $key ? 'bg-primary text-background-dark font-bold' : 'bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark text-text-main dark:text-white hover:border-primary hover:text-primary' }} text-sm font-medium transition-all active:scale-95 flex items-center gap-2">
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

        <!-- Inventory Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($items as $item)
            <div class="bg-surface-light dark:bg-surface-dark rounded-2xl overflow-hidden border border-border-light dark:border-border-dark hover:shadow-lg hover:border-primary/30 transition-all duration-300 group flex flex-col">
                <!-- Image -->
                <div class="relative h-48 w-full overflow-hidden bg-gray-100 dark:bg-gray-800">
                    <img alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                         src="{{ $item->image_url ?? 'https://placehold.co/400x300?text=' . urlencode($item->name) }}"/>
                    <div class="absolute top-3 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} bg-white/90 dark:bg-black/60 backdrop-blur text-text-main dark:text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                        {{ $item->quantity_value }} {{ $item->unit }}
                    </div>
                </div>

                <!-- Info -->
                <div class="p-4 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-1 rounded-md">{{ __('inventory.category.' . $item->category) }}</span>
                         <span class="text-xs text-text-muted">{{ $item->updated_at->diffForHumans() }}</span>
                    </div>
                    
                    <h3 class="font-bold text-lg text-text-main dark:text-white mb-1 leading-tight">{{ $item->name }}</h3>
                    <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark line-clamp-2 mb-4">{{ $item->description }}</p>
                    
                    <div class="mt-auto flex gap-2">
                        <a href="{{ route('inventory.edit', $item->id) }}" class="flex-1 flex items-center justify-center gap-2 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-text-main dark:text-white font-bold py-2.5 rounded-xl transition-colors text-sm">
                            <span class="material-symbols-outlined text-lg">edit</span>
                            {{ __('inventory.edit') }}
                        </a>
                        <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('inventory.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="size-10 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/10 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                <div class="size-24 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-4xl text-gray-400">inventory_2</span>
                </div>
                <h3 class="text-xl font-bold text-text-main dark:text-white mb-2">{{ __('inventory.empty') }}</h3>
                <p class="text-text-secondary-light dark:text-text-secondary-dark mb-6 max-w-sm">{{ __('inventory.empty_desc') }}</p>
                <a href="{{ route('inventory.create') }}" class="flex items-center gap-2 px-6 py-3 rounded-xl bg-primary text-background-dark font-bold hover:bg-primary-hover transition-colors shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined">add_circle</span>
                    <span>{{ __('inventory.add_item') }}</span>
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
