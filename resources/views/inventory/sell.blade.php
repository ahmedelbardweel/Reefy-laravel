@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-24 pt-28">
    <header class="bg-white dark:bg-slate-900 fixed top-0 w-full max-w-md mx-auto z-30 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
            <a href="{{ route('inventory.index') }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                <span class="material-symbols-outlined text-slate-600 dark:text-slate-300">arrow_forward</span>
            </a>
            <h1 class="text-xl font-bold font-display text-slate-900 dark:text-white">{{ __('inventory.sell_product') }}</h1>
        </div>
    </header>

    <div class="px-5 mt-5">
        <form action="{{ route('inventory.sell.action', $inventory->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-lg mb-1">{{ $inventory->name }}</h3>
                <p class="text-slate-500 text-sm mb-4">{{ __('inventory.available_qty') }} {{ $inventory->quantity_value }} {{ $inventory->unit }}</p>

                <div class="space-y-4">
                    <!-- صورة المنتج -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('inventory.product_image') }}</label>
                        @if($inventory->image_url)
                            <div class="mb-3 relative">
                                <img src="{{ $inventory->image_url }}" alt="{{ $inventory->name }}" class="w-full h-48 object-cover rounded-xl">
                                <div class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-lg">
                                    {{ __('inventory.current_image') }}
                                </div>
                            </div>
                        @else
                            <div class="mb-3 bg-slate-100 dark:bg-slate-900 h-48 rounded-xl flex items-center justify-center">
                                <div class="text-center">
                                    <span class="material-symbols-outlined text-5xl text-slate-300">add_photo_alternate</span>
                                    <p class="text-sm text-slate-400 mt-2">{{ __('inventory.no_image') }}</p>
                                </div>
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*" id="image-input"
                               class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 focus:outline-none focus:border-green-500">
                        <p class="text-xs text-slate-500 mt-1">{{ $inventory->image_url ? __('inventory.upload_new_image') : __('inventory.image_preferred') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('inventory.sell_qty') }} ({{ $inventory->unit }})</label>
                        <input type="number" name="quantity_to_sell" max="{{ $inventory->quantity_value }}" value="{{ $inventory->quantity_value }}" 
                               class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 focus:outline-none focus:border-green-500 font-bold">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('inventory.price_sar') }}</label>
                        <input type="number" name="price" step="0.01" required
                               class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 focus:outline-none focus:border-green-500 font-bold">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('inventory.additional_desc') }}</label>
                        <textarea name="description" rows="3"
                                  class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 focus:outline-none focus:border-green-500">{{ $inventory->description }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-green-500/30 hover:bg-green-700 transition-colors">
                {{ __('inventory.list_in_market') }}
            </button>
        </form>
    </div>
</div>
@endsection
