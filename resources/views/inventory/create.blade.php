@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-24 pt-10">
    <div class="max-w-md mx-auto px-5">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-primary/20">
                <span class="material-symbols-outlined text-4xl text-primary">inventory_2</span>
            </div>
            <h1 class="text-2xl font-bold font-display text-slate-900 dark:text-white">{{ __('inventory.add_new_item') }}</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm leading-relaxed">
                {{ __('inventory.add_item_desc') }}
            </p>
        </div>

        <form action="{{ route('inventory.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Step 1: Item Info -->
            <div id="step-1" class="transition-all duration-300">
                <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm mb-6">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">label</span>
                        {{ __('inventory.item_info') }}
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">{{ __('inventory.item_name') }}</label>
                            <input type="text" name="name" required placeholder="مثال: بذور طماطم هجينة" 
                                class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary text-slate-900 dark:text-white placeholder:text-slate-300">
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2">{{ __('inventory.category_label') }}</label>
                            <div class="grid grid-cols-3 gap-2">
                                @php
                                    $categories = [
                                        'seeds' => ['icon' => 'grass', 'label' => __('inventory.category.seeds')],
                                        'fertilizers' => ['icon' => 'science', 'label' => __('inventory.category.fertilizers')],
                                        'pesticides' => ['icon' => 'pest_control', 'label' => __('inventory.category.pesticides')],
                                        'equipment' => ['icon' => 'handyman', 'label' => __('inventory.category.equipment')],
                                        'harvest' => ['icon' => 'agriculture', 'label' => __('inventory.category.harvest')],
                                        'other' => ['icon' => 'category', 'label' => __('inventory.category.other') ?? is_string(__('inventory.category.other')) ? __('inventory.category.other') : 'أخرى'],
                                    ];
                                @endphp
                                
                                @foreach($categories as $key => $data)
                                <label class="cursor-pointer">
                                    <input type="radio" name="category" value="{{ $key }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 
                                        peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary 
                                        transition-all h-24 text-center">
                                        <span class="material-symbols-outlined mb-2 text-2xl">{{ $data['icon'] }}</span>
                                        <span class="text-[10px] font-bold">{{ $data['label'] }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('inventory.index') }}" class="px-6 py-4 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-lg hover:bg-slate-200 transition-all text-center flex items-center justify-center">
                        <span class="material-symbols-outlined">close</span>
                    </a>
                    <button type="button" onclick="nextStep()" class="flex-1 py-4 rounded-xl bg-primary text-slate-900 font-bold text-lg shadow-lg shadow-primary/20 hover:bg-primary/90 hover:shadow-primary/30 transition-all flex items-center justify-center gap-2">
                        <span>{{ __('next') }}</span>
                        <span class="material-symbols-outlined rtl:rotate-180">arrow_forward</span>
                    </button>
                </div>
            </div>

            <!-- Step 2: Stock Details -->
            <div id="step-2" class="hidden transition-all duration-300">
                <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm mb-6">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">inventory</span>
                        {{ __('inventory.stock_details') }}
                    </h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Quantity -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">{{ __('inventory.quantity') }}</label>
                                <input type="number" name="quantity_value" step="0.01" required placeholder="0.00"
                                    class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary text-slate-900 dark:text-white placeholder:text-slate-300">
                            </div>

                            <!-- Unit -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">{{ __('inventory.unit') }}</label>
                                <div class="relative">
                                    <select name="unit" required
                                        class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary text-slate-900 dark:text-white appearance-none">
                                        <option value="كجم">كجم</option>
                                        <option value="جرام">جرام</option>
                                        <option value="لتر">لتر</option>
                                        <option value="مل">مل</option>
                                        <option value="كيس">كيس</option>
                                        <option value="قطعة">قطعة</option>
                                        <option value="صندوق">صندوق</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute left-3 top-3 text-slate-400 pointer-events-none">expand_more</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5">{{ __('inventory.notes_optional') }}</label>
                            <textarea name="description" rows="3" placeholder="{{ __('inventory.notes_placeholder') }}" 
                                class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary text-slate-900 dark:text-white placeholder:text-slate-300 resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="prevStep()" class="px-6 py-4 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-lg hover:bg-slate-200 transition-all">
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                    <button type="submit" class="flex-1 py-4 rounded-xl bg-primary text-slate-900 font-bold text-lg shadow-lg shadow-primary/20 hover:bg-primary/90 hover:shadow-primary/30 transition-all flex items-center justify-center gap-2">
                        <span>{{ __('inventory.save_item') }}</span>
                        <span class="material-symbols-outlined rtl:rotate-180">check</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-open step 2 if errors exist in step 2 fields
        const hasStep2Errors = {{ $errors->hasAny(['quantity_value', 'unit', 'description']) ? 'true' : 'false' }};
        if (hasStep2Errors) {
            document.getElementById('step-1').classList.add('hidden');
            document.getElementById('step-2').classList.remove('hidden');
        }
    });

    function nextStep() {
        const name = document.querySelector('input[name="name"]');
        
        let isValid = true;
        
        if (!name.value.trim()) {
            isValid = false;
            name.classList.add('ring-2', 'ring-red-500', 'bg-red-50', 'dark:bg-red-900/10');
            name.addEventListener('input', function() {
                this.classList.remove('ring-2', 'ring-red-500', 'bg-red-50', 'dark:bg-red-900/10');
            }, {once: true});
        }

        if (isValid) {
            document.getElementById('step-1').classList.add('hidden');
            document.getElementById('step-2').classList.remove('hidden');
        }
    }

    function prevStep() {
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-1').classList.remove('hidden');
    }
</script>
@endsection
