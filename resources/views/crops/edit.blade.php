<!DOCTYPE html>
<html class="light" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ __('crops.edit.title') }} - AgriConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&amp;family=Noto+Sans+Arabic:wght@400;500;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec13",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102210",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a2e1a",
                        "text-main": "#111811",
                        "text-secondary": "#618961",
                        "border-light": "#dbe6db",
                        "border-dark": "#2a422a",
                    },
                    fontFamily: {
                        "display": ["Manrope", "Noto Sans Arabic", "sans-serif"],
                        "body": ["Manrope", "Noto Sans Arabic", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Manrope', 'Noto Sans Arabic', sans-serif; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-text-main dark:text-white transition-colors duration-200">
<div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 bg-surface-light dark:bg-surface-dark border-b border-border-light dark:border-border-dark px-10 py-3 shadow-sm">
        <div class="flex items-center justify-between whitespace-nowrap">
            <div class="flex items-center gap-4 text-text-main dark:text-white">
                <div class="size-8 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">agriculture</span>
                </div>
                <h2 class="text-text-main dark:text-white text-xl font-bold leading-tight tracking-tight">AgriConnect</h2>
            </div>
            <div class="hidden md:flex flex-1 justify-end gap-8">
                <nav class="flex items-center gap-9">
                    <a class="text-text-main dark:text-white hover:text-primary transition-colors text-sm font-medium leading-normal" href="{{ route('dashboard') }}">{{ __('dashboard') }}</a>
                    <a class="text-text-main dark:text-white hover:text-primary transition-colors text-sm font-medium leading-normal" href="{{ route('crops.index') }}">{{ __('crops.title') }}</a>

                    <a class="text-text-main dark:text-white hover:text-primary transition-colors text-sm font-medium leading-normal" href="{{ route('profile.setup') }}">{{ __('profile') }}</a>
                </nav>
                <div class="flex items-center gap-4">
                     <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/20 hover:bg-primary/30 text-text-main dark:text-white text-sm font-bold leading-normal transition-colors">
                            <span class="truncate">{{ __('logout') }}</span>
                        </button>
                    </form>
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border-2 border-primary" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCCuuLGgPDllEO0ezToZrGEyJLLXNgzyCTjYzbqFN4qgTgjU0FMf62mtmX9J8kikWk4bIDGfiNFcY9_j3tgB6mQBwM4QvH6TPddozleNgm9mb6WApa9ZGel3VXJidBDGIpSjl8VshozDiPwQdj8001DiaobSHUdWzJ4DKAnLIcLvl-AUvekruObESRjWEZZMUTgC_d5bjnQI6A8A8gVwsNQeKTd4Ib_yptSm6UIyKf6HiMYm1c6Cu4yDKNXlAZxqAj1vqFHhs-qrg");'></div>
                </div>
            </div>
        </div>
    </header>

    <div class="layout-container flex h-full grow flex-col">
        <form action="{{ route('crops.update', $crop->id) }}" method="POST" enctype="multipart/form-data" class="px-4 md:px-10 lg:px-40 flex flex-1 justify-center py-8">
            @csrf
            @method('PUT')
            
            <div class="layout-content-container flex flex-col max-w-[960px] flex-1 gap-6">
                
                @if ($errors->any())
                <div class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-4 rounded-xl font-bold">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Page Heading & Actions -->
                <div class="flex flex-wrap justify-between items-end gap-4 pb-4 border-b border-border-light dark:border-border-dark">
                    <div class="flex flex-col gap-2">
                        <h1 class="text-text-main dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">{{ __('crops.edit.title') }}</h1>
                        <p class="text-text-secondary dark:text-gray-400 text-base font-normal leading-normal">{{ __('crops.edit.desc') }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('crops.show', $crop->id) }}" class="flex min-w-[84px] cursor-pointer items-center justify-center rounded-lg h-10 px-6 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark text-text-main dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-bold transition-colors">
                            <span class="truncate">{{ __('crops.cancel') }}</span>
                        </a>
                        <button type="submit" class="flex min-w-[84px] cursor-pointer items-center justify-center rounded-lg h-10 px-6 bg-primary hover:bg-green-500 text-black text-sm font-bold shadow-md shadow-green-500/20 transition-all">
                            <span class="truncate">{{ __('crops.save_changes') }}</span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left Column: Image Upload -->
                    <div class="lg:col-span-4 flex flex-col gap-6">
                        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl shadow-sm border border-border-light dark:border-border-dark">
                            <label class="text-text-main dark:text-white text-lg font-bold mb-4 block">{{ __('crops.create.image_section') }}</label>
                            <div class="group relative aspect-square w-full overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800 border-2 border-dashed border-border-light dark:border-border-dark flex items-center justify-center cursor-pointer hover:border-primary transition-colors">
                                <div class="absolute inset-0 bg-cover bg-center opacity-80 group-hover:scale-105 transition-transform duration-500" style="background-image: url('{{ $crop->image_url ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuAKAhIJmFz-euEv-mk3rAwtZDIA362BNMZH6mXBm03cSf6QTzk-aBq_HZgSaVXnRfQnO6YDvU94-0y53OASMXgKY1HJjIBYuyjzE4SStxxmeQPZ_i6b_km2oPW6PAOIZYEtRCBozb80q7OVsS9ffCCWW-slF4vD8xGOXrw1IftYRMefNKgbnYEHxMsG2rLKuysAGTLBIGk4wouiGYXVk_3-bNZLft2qx72v3F-RBsVIxul4qyBa7iqiw0T2VU5Crwux6x1OtN6nAw' }}');"></div>
                                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors flex flex-col items-center justify-center text-white">
                                    <div class="bg-white/20 backdrop-blur-md p-3 rounded-full mb-2">
                                        <span class="material-symbols-outlined text-3xl">add_a_photo</span>
                                    </div>
                                    <span class="text-sm font-bold">{{ __('crops.change_image') }}</span>
                                </div>
                                <input name="image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" type="file"/>
                            </div>
                            <p class="text-text-secondary text-xs mt-3 text-center">{{ __('crops.default_image_note') }}</p>
                        </div>
                    </div>

                    <!-- Right Column: Form Data -->
                    <div class="lg:col-span-8 flex flex-col gap-6">
                        <!-- Section 1: Basic Info -->
                        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl shadow-sm border border-border-light dark:border-border-dark">
                            <h3 class="text-text-main dark:text-white text-lg font-bold mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">info</span>
                                {{ __('crops.basic_info') }}
                            </h3>
                            
                            <!-- Segmented Buttons for Crop Type -->
                            <div class="mb-6">
                                <label class="text-text-main dark:text-white text-base font-medium pb-2 block">{{ __('crops.type') }}</label>
                                <div class="flex w-full p-1 bg-background-light dark:bg-background-dark rounded-lg border border-border-light dark:border-border-dark">
                                    <label class="flex-1 cursor-pointer">
                                        <input class="peer sr-only" name="type" type="radio" value="Vegetable" {{ $crop->type == 'Vegetable' ? 'checked' : '' }}/>
                                        <div class="flex items-center justify-center gap-2 py-2.5 rounded-md text-text-secondary peer-checked:bg-white dark:peer-checked:bg-surface-dark peer-checked:text-text-main dark:peer-checked:text-white peer-checked:shadow-sm transition-all text-sm font-bold hover:bg-gray-200/50 dark:hover:bg-gray-700/50">
                                            <span class="material-symbols-outlined text-lg">potted_plant</span>
                                            {{ __('crops.type.vegetables') }}
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input class="peer sr-only" name="type" type="radio" value="Fruit" {{ $crop->type == 'Fruit' ? 'checked' : '' }}/>
                                        <div class="flex items-center justify-center gap-2 py-2.5 rounded-md text-text-secondary peer-checked:bg-white dark:peer-checked:bg-surface-dark peer-checked:text-text-main dark:peer-checked:text-white peer-checked:shadow-sm transition-all text-sm font-bold hover:bg-gray-200/50 dark:hover:bg-gray-700/50">
                                            <span class="material-symbols-outlined text-lg">nutrition</span>
                                            {{ __('crops.type.fruits') }}
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Crop Name -->
                            <div class="mb-2">
                                <label class="flex flex-col w-full">
                                    <p class="text-text-main dark:text-white text-base font-medium pb-2">{{ __('crops.name') }}</p>
                                    <div class="relative">
                                        <input name="name" value="{{ old('name', $crop->name) }}" class="w-full rounded-lg text-text-main dark:text-white bg-white dark:bg-background-dark border border-border-light dark:border-border-dark focus:border-primary focus:ring-1 focus:ring-primary h-12 px-4 pl-10 text-base placeholder:text-text-secondary/60 outline-none transition-all" placeholder="{{ __('crops.name') }}" required/>
                                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary pointer-events-none">search</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Section 2: Logistics & Dates -->
                        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl shadow-sm border border-border-light dark:border-border-dark">
                            <h3 class="text-text-main dark:text-white text-lg font-bold mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">calendar_month</span>
                                {{ __('crops.logistics') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Planting Date -->
                                <label class="flex flex-col">
                                    <p class="text-text-main dark:text-white text-base font-medium pb-2">{{ __('crops.planting_date') }}</p>
                                    <div class="relative">
                                        <input name="planting_date" value="{{ old('planting_date', \Carbon\Carbon::parse($crop->planting_date)->format('Y-m-d')) }}" class="w-full rounded-lg text-text-main dark:text-white bg-white dark:bg-background-dark border border-border-light dark:border-border-dark focus:border-primary focus:ring-1 focus:ring-primary h-12 px-4 text-base placeholder:text-text-secondary outline-none transition-all" type="date" required/>
                                    </div>
                                </label>
                                
                                <!-- Harvest Date (Auto) -->
                                <label class="flex flex-col relative">
                                    <div class="flex items-center justify-between pb-2">
                                        <p class="text-text-main dark:text-white text-base font-medium">{{ __('crops.harvest_date') }}</p>
                                        <span class="text-[10px] font-bold bg-primary/20 text-green-800 dark:text-green-300 px-2 py-0.5 rounded-full">{{ __('crops.auto_calc') }}</span>
                                    </div>
                                    <div class="relative">
                                        <input name="harvest_date" value="{{ old('harvest_date', $crop->harvest_date ? \Carbon\Carbon::parse($crop->harvest_date)->format('Y-m-d') : '') }}" class="w-full rounded-lg text-text-secondary bg-background-light dark:bg-black/20 border border-border-light dark:border-border-dark focus:border-primary focus:ring-1 focus:ring-primary h-12 px-4 text-base outline-none cursor-not-allowed opacity-80" type="date"/>
                                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary text-lg">auto_awesome</span>
                                    </div>
                                    <p class="text-xs text-text-secondary mt-1.5 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">info</span>
                                        {{ __('crops.calc_note') }}
                                    </p>
                                </label>
                            </div>

                            <!-- Field Selector -->
                            <div class="mb-2">
                                <label class="flex flex-col w-full">
                                    <p class="text-text-main dark:text-white text-base font-medium pb-2">{{ __('crops.field_name') }}</p>
                                    <div class="relative group cursor-pointer">
                                        <select name="field_name" class="w-full appearance-none rounded-lg text-text-main dark:text-white bg-white dark:bg-background-dark border border-border-light dark:border-border-dark focus:border-primary focus:ring-1 focus:ring-primary h-14 px-4 pl-10 text-base outline-none transition-all cursor-pointer">
                                            <option disabled="" value="">{{ __('crops.field_placeholder') }}</option>
                                            <option value="field1" {{ $crop->field_name == 'field1' ? 'selected' : '' }}>North Field (5 acres)</option>
                                            <option value="field2" {{ $crop->field_name == 'field2' ? 'selected' : '' }}>Greenhouse 1</option>
                                            <option value="field3" {{ $crop->field_name == 'field3' ? 'selected' : '' }}>Palm Farm</option>
                                        </select>
                                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary pointer-events-none group-hover:text-primary transition-colors">expand_more</span>
                                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-text-secondary pointer-events-none text-xl">location_on</span>
                                    </div>
                                    <div class="mt-3 flex items-center gap-2">
                                        <button type="button" class="text-sm text-primary font-bold hover:underline flex items-center gap-1">
                                            <span class="material-symbols-outlined text-base">add_location_alt</span>
                                            {{ __('crops.add_new_field') }}
                                        </button>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
