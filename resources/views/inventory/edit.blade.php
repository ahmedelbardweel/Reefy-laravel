@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900">
    <!-- Header -->
    <header class="bg-white dark:bg-slate-900 px-6 py-4 sticky top-0 z-20 border-b border-slate-100 dark:border-slate-800 flex items-center gap-4">
        <a href="{{ route('inventory.index') }}" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
        </a>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('inventory.edit_item') }}</h1>
    </header>

    <form action="{{ route('inventory.update', $inventory->id) }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Name -->
        <div>
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('inventory.item_name') }}</label>
            <input type="text" name="name" required value="{{ old('name', $inventory->name) }}"
                class="w-full px-4 py-3.5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition text-slate-900 dark:text-white placeholder:text-slate-400"
                placeholder="مثال: بذور طماطم">
        </div>

        <!-- Category -->
        <div>
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">{{ __('inventory.category_label') }}</label>
            <div class="grid grid-cols-3 gap-3">
                @php
                    $categories = [
                        'seeds' => ['icon' => 'grass', 'label' => __('inventory.category.seeds')],
                        'fertilizers' => ['icon' => 'science', 'label' => __('inventory.category.fertilizers')],
                        'pesticides' => ['icon' => 'pest_control', 'label' => __('inventory.category.pesticides')],
                        'equipment' => ['icon' => 'handyman', 'label' => __('inventory.category.equipment')],
                        'harvest' => ['icon' => 'agriculture', 'label' => __('inventory.category.harvest')],
                        'other' => ['icon' => 'inventory_2', 'label' => __('inventory.category.other') ?? is_string(__('inventory.category.other')) ? __('inventory.category.other') : 'أخرى'],
                    ];
                @endphp
                
                @foreach($categories as $key => $data)
                <label class="cursor-pointer group">
                    <input type="radio" name="category" value="{{ $key }}" class="peer sr-only" {{ old('category', $inventory->category) == $key ? 'checked' : '' }}>
                    <div class="flex flex-col items-center justify-center p-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 
                        peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary
                        group-hover:border-primary/50 transition h-full text-center">
                        <span class="material-symbols-outlined mb-1.5 peer-checked:fill-current">{{ $data['icon'] }}</span>
                        <span class="text-xs font-bold">{{ $data['label'] }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Quantity & Unit -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('inventory.quantity') }}</label>
                <input type="number" name="quantity_value" step="0.01" required value="{{ old('quantity_value', $inventory->quantity_value) }}"
                    class="w-full px-4 py-3.5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition text-slate-900 dark:text-white"
                    placeholder="0.00">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('inventory.unit') }}</label>
                <div class="relative">
                    <select name="unit" required
                        class="w-full px-4 py-3.5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition text-slate-900 dark:text-white appearance-none cursor-pointer">
                        @php
                            $units = ['كجم', 'جرام', 'لتر', 'مل', 'كيس', 'قطعة', 'صندوق'];
                        @endphp
                        @foreach($units as $unit)
                            <option value="{{ $unit }}" {{ old('unit', $inventory->unit) == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('inventory.notes_optional') }}</label>
            <textarea name="description" rows="3"
                class="w-full px-4 py-3.5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition text-slate-900 dark:text-white placeholder:text-slate-400 resizable-none"
                placeholder="تفاصيل إضافية...">{{ old('description', $inventory->description) }}</textarea>
        </div>

        <!-- Submit -->
        <button type="submit" 
            class="w-full bg-primary text-slate-900 py-4 rounded-2xl font-bold text-lg shadow-lg shadow-primary/25 hover:shadow-primary/40 hover:scale-[1.02] transition-all active:scale-[0.98]">
            {{ __('inventory.update_item') }}
        </button>
    </form>
</div>
@endsection
