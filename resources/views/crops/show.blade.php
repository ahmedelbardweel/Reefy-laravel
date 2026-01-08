<!DOCTYPE html>
<html class="light" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ $crop->name }} - MyFarm</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;700;800&amp;family=Manrope:wght@300;400;500;700;800&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Theme Config -->
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec13",
                        "primary-dark": "#0fb80f",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102210",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a2e1a",
                        "text-main": "#111811",
                        "text-muted": "#618961",
                    },
                    fontFamily: {
                        "display": ["Cairo", "Manrope", "sans-serif"],
                        "body": ["Cairo", "Manrope", "sans-serif"],
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem", 
                        "lg": "0.75rem", 
                        "xl": "1rem", 
                        "2xl": "1.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
<style>
        body {
            font-family: 'Cairo', 'Manrope', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        ::-webkit-scrollbar-thumb {
            background: #dbe6db; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #618961; 
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-text-main dark:text-white min-h-screen flex flex-col font-display selection:bg-primary selection:text-background-dark">
<!-- Navbar -->
<header class="sticky top-0 z-50 bg-surface-light dark:bg-surface-dark border-b border-[#f0f4f0] dark:border-[#2a3e2a] px-6 py-3 shadow-sm">
<div class="max-w-7xl mx-auto flex items-center justify-between">
<div class="flex items-center gap-8">
<!-- Logo -->
<a class="flex items-center gap-3 text-text-main dark:text-white group" href="{{ route('dashboard') }}">
<div class="size-10 bg-primary/20 rounded-full flex items-center justify-center text-primary-dark group-hover:bg-primary group-hover:text-background-dark transition-colors">
<span class="material-symbols-outlined filled">agriculture</span>
</div>
<h2 class="text-xl font-bold tracking-tight">AgriConnect</h2>
</a>
<!-- Search -->
<div class="hidden md:flex items-center w-80 relative">
<span class="material-symbols-outlined absolute right-3 text-text-muted">search</span>
<input class="w-full bg-background-light dark:bg-background-dark border-none rounded-lg py-2.5 pr-10 pl-4 text-sm focus:ring-2 focus:ring-primary/50 text-text-main dark:text-white placeholder:text-text-muted/70" placeholder="{{ __('market.search_placeholder') }}" type="text"/>
</div>
</div>
<div class="flex items-center gap-6">
<!-- Nav Links -->
<nav class="hidden lg:flex items-center gap-6">
<a class="text-sm font-bold text-text-main dark:text-white hover:text-primary-dark transition-colors" href="{{ route('dashboard') }}">{{ __('dashboard') }}</a>
<a class="text-sm font-medium text-text-muted dark:text-gray-300 hover:text-primary-dark transition-colors" href="{{ route('crops.index') }}">{{ __('crops.title') }}</a>
<a class="text-sm font-medium text-text-muted dark:text-gray-300 hover:text-primary-dark transition-colors" href="{{ route('market.index') }}">{{ __('market') }}</a>
<a class="text-sm font-medium text-text-muted dark:text-gray-300 hover:text-primary-dark transition-colors" href="{{ route('reports.index') }}">{{ __('reports.title') }}</a>
</nav>
<!-- Actions -->
<div class="flex items-center gap-3 border-r border-[#f0f4f0] dark:border-[#2a3e2a] pr-4 mr-2">
    <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
        <span class="material-symbols-outlined text-lg">language</span>
    </a>
<button class="size-10 flex items-center justify-center rounded-full hover:bg-background-light dark:hover:bg-white/5 text-text-muted transition-colors relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border-2 border-white dark:border-surface-dark"></span>
</button>
<div class="size-10 rounded-full bg-cover bg-center ring-2 ring-primary/20" data-alt="User profile picture" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCKw0cDe30BO1LLLrlbCupfHR7OU09epPpv574CPAIYkJDvdJSN17rGpgPTxrhdnZFH_FEXA8jmcC6PvEaiNXW0OzoEFzSjLHcL19mItnR4ElAX4U1vdEEV2CDSQlabHdVDosQzJQmfMYrcE2Hg8kim4kPl0HlbUeCj2FK5DG19a6NMxmCfDNrz1OoP9-OXBLIJkzv2zunwD8wLgqwbbxnVRyKyTyV09TKVb4FxE1ydpqJi-INDY7JvsjQa2C2B40hDCkXY0tsltA");'>
</div>
</div>
</div>
</div>
</header>
<!-- Main Content -->
<main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 py-8">
<!-- Breadcrumbs -->
<div class="flex items-center gap-2 text-sm text-text-muted mb-6">
<a class="hover:text-primary-dark transition-colors flex items-center gap-1" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined text-[18px]">home</span>
                {{ __('dashboard') }}
            </a>
<span class="material-symbols-outlined text-[16px] rtl:rotate-180">chevron_left</span>
<a class="hover:text-primary-dark transition-colors" href="{{ route('crops.index') }}">{{ __('crops.title') }}</a>
<span class="material-symbols-outlined text-[16px] rtl:rotate-180">chevron_left</span>
<span class="font-bold text-text-main dark:text-white">{{ $crop->name }}</span>
</div>
<!-- Page Header -->
<div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 shadow-sm border border-[#e5e7eb] dark:border-[#2a3e2a] mb-8 relative overflow-hidden group">
<div class="absolute top-0 left-0 w-2 h-full bg-primary"></div>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
<div class="flex items-start gap-5">
<div class="size-24 rounded-xl bg-cover bg-center shadow-md shrink-0" data-alt="{{ $crop->name }}" style='background-image: url("{{ $crop->image_url ?? "https://lh3.googleusercontent.com/aida-public/AB6AXuBD_os02U0a4q0l8roR_b1c8G0QEXAn8c-8ycAtnFaH-_3GEQ7EQT969rocQDV0GXJDDozW_WiSk7xCtUw6x35sXsnNtFSMBL_a2xMdbSlaMrW4mFXW2LxitfZi_6y2otzut6PR1wegWuM_Al1ikO1Wp7tNnDcrgzeV7l9ECKcTrct1evPlUP4IC3v-aeyiFu7jx2zQQoUVyCF4qSVDBI4DUxariKRFMjICs5MNTkXQ12uBa2cA1Z_ef13EPP6Mpsp5PG64jLJFtA" }}");'>
</div>
<div class="flex flex-col gap-1">
<div class="flex items-center gap-3">
<h1 class="text-3xl font-black text-text-main dark:text-white">{{ $crop->name }}</h1>
<span class="px-2.5 py-0.5 rounded-full bg-primary/20 text-primary-dark text-xs font-bold border border-primary/20">{{ __('crops.show.active') }}</span>
</div>
<p class="text-text-muted dark:text-gray-400 text-base">{{ __('crops.show.variety') }}: {{ $crop->type ?? 'Unknown' }} • {{ __('crops.show.planting_date_label') }}: {{ \Carbon\Carbon::parse($crop->planting_date)->isoFormat('LL') }}</p>
<div class="flex items-center gap-4 mt-2 text-sm">
<div class="flex items-center gap-1 text-text-muted dark:text-gray-400">
<span class="material-symbols-outlined text-[18px]">location_on</span>
<span>{{ $crop->field_name ?? 'المنطقة الشرقية' }}</span>
</div>
<div class="flex items-center gap-1 text-text-muted dark:text-gray-400">
<span class="material-symbols-outlined text-[18px]">square_foot</span>
<span>٢ هكتار</span>
</div>
</div>
</div>
</div>
<div class="flex items-center gap-3 self-end md:self-center">
<a href="{{ route('crops.edit', $crop->id) }}" class="flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-white/5 text-text-main dark:text-white font-bold transition-all text-sm">
<span class="material-symbols-outlined text-[20px]">edit</span>
                        {{ __('crops.show.edit_details') }}
                    </a>
<form action="{{ route('crops.harvest', $crop->id) }}" method="POST">
    @csrf
    <button type="submit" onclick="return confirm('{{ __('crops.confirm_harvest') }}')" class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-background-dark font-bold hover:bg-primary-dark hover:text-white transition-all shadow-lg shadow-primary/25 text-sm">
    <span class="material-symbols-outlined text-[20px] filled">add_task</span>
                            {{ __('crops.show.harvest_action') }}
                        </button>
</form>
</div>
</div>
</div>
<!-- Dashboard Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
<!-- Left Column (Main Stats) -->
<div class="lg:col-span-2 flex flex-col gap-6">
<!-- Progress Card -->
<div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-[#e5e7eb] dark:border-[#2a3e2a] shadow-sm">
<div class="flex items-center justify-between mb-4">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary-dark">timelapse</span>
<h3 class="text-lg font-bold">{{ __('crops.show.progress_title') }}</h3>
</div>
<span class="text-2xl font-black text-text-main dark:text-white">75%</span>
</div>
<div class="relative w-full h-4 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden mb-3">
<div class="absolute top-0 right-0 h-full bg-primary rounded-full ltr:left-0 ltr:right-auto" style="width: 75%;">
<div class="absolute inset-0 bg-white/20 animate-[pulse_2s_infinite]"></div>
</div>
</div>
<div class="flex justify-between items-center text-sm">
<p class="text-text-muted dark:text-gray-400">{{ __('crops.show.days_remaining', ['days' => 20]) }}</p>
<p class="text-text-muted dark:text-gray-400">{{ __('crops.show.expected_harvest') }}: {{ $crop->harvest_date ? \Carbon\Carbon::parse($crop->harvest_date)->isoFormat('LL') : '15 مايو 2023' }}</p>
</div>
</div>
<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
<!-- Stat 1 -->
<div class="bg-surface-light dark:bg-surface-dark p-5 rounded-xl border border-[#e5e7eb] dark:border-[#2a3e2a] flex flex-col gap-2 hover:border-primary/50 transition-colors group">
<div class="flex justify-between items-start">
<p class="text-text-muted dark:text-gray-400 text-sm font-medium">{{ __('crops.show.health') }}</p>
<span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform filled">eco</span>
</div>
<p class="text-2xl font-bold text-text-main dark:text-white">{{ __('crops.show.status_excellent') }}</p>
<span class="text-xs text-primary-dark bg-primary/10 w-fit px-2 py-0.5 rounded flex items-center gap-1">
<span class="material-symbols-outlined text-[12px]">trending_up</span> {{ __('crops.show.status_stable') }}
                        </span>
</div>
<!-- Stat 2 -->
<div class="bg-surface-light dark:bg-surface-dark p-5 rounded-xl border border-[#e5e7eb] dark:border-[#2a3e2a] flex flex-col gap-2 hover:border-blue-400 transition-colors group">
<div class="flex justify-between items-start">
<p class="text-text-muted dark:text-gray-400 text-sm font-medium">{{ __('crops.show.moisture') }}</p>
<span class="material-symbols-outlined text-blue-500 group-hover:scale-110 transition-transform">water_drop</span>
</div>
<p class="text-2xl font-bold text-text-main dark:text-white">45%</p>
<span class="text-xs text-orange-600 bg-orange-100 dark:bg-orange-900/30 w-fit px-2 py-0.5 rounded flex items-center gap-1">
<span class="material-symbols-outlined text-[12px]">warning</span> {{ __('crops.show.status_low') }}
                        </span>
</div>
<!-- Stat 3 -->
<div class="bg-surface-light dark:bg-surface-dark p-5 rounded-xl border border-[#e5e7eb] dark:border-[#2a3e2a] flex flex-col gap-2 hover:border-yellow-400 transition-colors group">
<div class="flex justify-between items-start">
<p class="text-text-muted dark:text-gray-400 text-sm font-medium">{{ __('crops.show.temp') }}</p>
<span class="material-symbols-outlined text-yellow-500 group-hover:scale-110 transition-transform">thermostat</span>
</div>
<p class="text-2xl font-bold text-text-main dark:text-white">28°C</p>
<span class="text-xs text-text-muted dark:text-gray-500 w-fit px-2 py-0.5 rounded">
                           {{ __('crops.show.status_good') }}
                        </span>
</div>
</div>
<!-- Status Update Section -->
<div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-[#e5e7eb] dark:border-[#2a3e2a] shadow-sm">
<h3 class="text-lg font-bold mb-4 flex items-center gap-2">
<span class="material-symbols-outlined text-primary-dark">edit_note</span>
                        {{ __('crops.show.update_status') }}
                    </h3>
<div class="flex flex-col gap-4">
<form action="{{ route('crops.update', $crop->id) }}" method="POST" class="flex flex-col gap-4">
    @csrf
    @method('PUT')
<p class="text-sm text-text-muted dark:text-gray-400">{{ __('crops.show.update_desc') }}</p>
<div class="flex flex-wrap gap-3">
<label class="cursor-pointer group">
<input {{ $crop->status == 'excellent' ? 'checked' : '' }} class="peer sr-only" name="status" type="radio" value="excellent"/>
<div class="px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-white/5 peer-checked:bg-primary peer-checked:text-background-dark peer-checked:border-primary hover:bg-gray-100 dark:hover:bg-white/10 transition-all flex items-center gap-2">
<span class="material-symbols-outlined filled text-green-600 peer-checked:text-background-dark">sentiment_satisfied</span>
<span class="font-bold text-sm">{{ __('crops.show.status_excellent') }}</span>
</div>
</label>
<label class="cursor-pointer group">
<input {{ $crop->status == 'warning' ? 'checked' : '' }} class="peer sr-only" name="status" type="radio" value="warning"/>
<div class="px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-white/5 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 hover:bg-gray-100 dark:hover:bg-white/10 transition-all flex items-center gap-2">
<span class="material-symbols-outlined text-blue-500 peer-checked:text-white">water_drop</span>
<span class="font-bold text-sm">{{ __('crops.show.status_water') }}</span>
</div>
</label>
<label class="cursor-pointer group">
<input {{ $crop->status == 'infected' ? 'checked' : '' }} class="peer sr-only" name="status" type="radio" value="infected"/>
<div class="px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-white/5 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 hover:bg-gray-100 dark:hover:bg-white/10 transition-all flex items-center gap-2">
<span class="material-symbols-outlined text-red-500 peer-checked:text-white">bug_report</span>
<span class="font-bold text-sm">{{ __('crops.show.status_pests') }}</span>
</div>
</label>
<label class="cursor-pointer group">
<input {{ $crop->status == 'good' ? 'checked' : '' }} class="peer sr-only" name="status" type="radio" value="good"/>
<div class="px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-white/5 peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-yellow-500 hover:bg-gray-100 dark:hover:bg-white/10 transition-all flex items-center gap-2">
<span class="material-symbols-outlined text-yellow-500 peer-checked:text-white">wb_sunny</span>
<span class="font-bold text-sm">{{ __('crops.show.status_heat') }}</span>
</div>
</label>
</div>
<div class="relative mt-2">
<textarea name="field_name" class="w-full bg-background-light dark:bg-background-dark border border-gray-200 dark:border-gray-700 rounded-lg p-3 text-sm focus:ring-2 focus:ring-primary/50 focus:border-primary resize-none h-24" placeholder="{{ __('crops.show.add_note') }}">{{ $crop->field_name }}</textarea>
<button type="submit" class="absolute bottom-3 left-3 bg-primary text-background-dark px-4 py-1.5 rounded text-xs font-bold hover:bg-primary-dark hover:text-white transition-colors">{{ __('crops.show.save_update') }}</button>
</div>
</form>
</div>
</div>
</div>
<!-- Right Column (Advisory & History) -->
<div class="flex flex-col gap-6">
<!-- Weather / Advisory Widget -->
<div class="bg-gradient-to-br from-[#102210] to-[#1a3a1a] text-white rounded-xl p-6 shadow-md relative overflow-hidden">
<div class="absolute top-0 left-0 w-32 h-32 bg-primary/20 rounded-full blur-2xl -translate-x-10 -translate-y-10"></div>
<div class="relative z-10">
<div class="flex items-center gap-2 mb-4">
<span class="material-symbols-outlined text-primary">tips_and_updates</span>
<h3 class="font-bold">{{ __('crops.show.recommendations') }}</h3>
</div>
<div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/10 mb-4">
<p class="text-sm leading-relaxed text-gray-200">
                                {{ __('crops.show.recommendation_text', ['moisture' => '45%']) }}
                            </p>
</div>
<button onclick="document.getElementById('irrigationModal').classList.remove('hidden')" class="w-full py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
    <span class="material-symbols-outlined text-[18px]">calendar_add_on</span>
    {{ __('crops.show.schedule_irrigation') }}
</button>
</div>
</div>
<!-- History Timeline -->
<div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-[#e5e7eb] dark:border-[#2a3e2a] shadow-sm flex-grow">
<div class="flex items-center justify-between mb-6">
<h3 class="text-lg font-bold">{{ __('crops.show.history') }}</h3>
<a class="text-xs text-primary-dark font-bold hover:underline" href="{{ route('crops.activity-log', $crop->id) }}">{{ __('crops.show.view_all') }}</a>
</div>
<div class="flex flex-col gap-0 relative">
<!-- Line -->
<div class="absolute right-[19px] top-2 bottom-4 w-0.5 bg-gray-100 dark:bg-gray-800 rtl:right-[19px] ltr:left-[19px]"></div>

@forelse($irrigations as $irrigation)
<!-- Irrigation Item -->
<div class="relative flex gap-4 pb-6">
<div class="z-10 mt-1 size-10 rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center shrink-0 border-4 border-surface-light dark:border-surface-dark">
<span class="material-symbols-outlined text-[20px]">water_drop</span>
</div>
<div class="flex flex-col gap-1">
<p class="text-sm font-bold text-text-main dark:text-white">{{ __('crops.timeline.irrigation') }} - {{ $irrigation->amount }} {{ __('liter') }}</p>
<span class="text-xs text-text-muted">{{ \Carbon\Carbon::parse($irrigation->created_at)->diffForHumans() }}</span>
</div>
</div>
@empty
@endforelse

@if($crop->updated_at != $crop->created_at)
<!-- Status Update Item -->
<div class="relative flex gap-4 pb-6">
<div class="z-10 mt-1 size-10 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 flex items-center justify-center shrink-0 border-4 border-surface-light dark:border-surface-dark">
<span class="material-symbols-outlined text-[20px] filled">check_circle</span>
</div>
<div class="flex flex-col gap-1">
<p class="text-sm font-bold text-text-main dark:text-white">{{ __('crops.timeline.status_update') }}</p>
<span class="text-xs text-text-muted">{{ \Carbon\Carbon::parse($crop->updated_at)->diffForHumans() }}</span>
</div>
</div>
@endif

<!-- Planting Item -->
<div class="relative flex gap-4">
<div class="z-10 mt-1 size-10 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500 flex items-center justify-center shrink-0 border-4 border-surface-light dark:border-surface-dark">
<span class="material-symbols-outlined text-[20px]">agriculture</span>
</div>
<div class="flex flex-col gap-1">
<p class="text-sm font-bold text-text-main dark:text-white">{{ __('crops.timeline.planting') }}</p>
<span class="text-xs text-text-muted">{{ \Carbon\Carbon::parse($crop->created_at)->isoFormat('LL') }}</span>
</div>
</div>
</div>
</div>
<!-- Quick Actions Card -->
<div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-[#e5e7eb] dark:border-[#2a3e2a] shadow-sm">
<h3 class="text-sm font-bold mb-3 text-text-muted uppercase tracking-wider">{{ __('crops.show.quick_actions') }}</h3>
<div class="flex flex-col gap-2">
<button onclick="document.getElementById('sellModal').classList.remove('hidden')" class="w-full text-right px-4 py-3 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition-colors text-sm font-medium flex items-center justify-between group">
<span class="flex items-center gap-2">
<span class="material-symbols-outlined text-gray-400 group-hover:text-primary transition-colors">sell</span>
                                {{ __('crops.show.sell_market') }}
                            </span>
<span class="material-symbols-outlined text-[16px] text-gray-300 group-hover:text-primary transition-colors rtl:rotate-180">chevron_right</span>
</button>
<a href="{{ route('crops.growth-report', $crop->id) }}" class="w-full text-right px-4 py-3 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition-colors text-sm font-medium flex items-center justify-between group">
<span class="flex items-center gap-2">
<span class="material-symbols-outlined text-gray-400 group-hover:text-primary transition-colors">description</span>
                                {{ __('crops.show.growth_report') }}
                            </span>
<span class="material-symbols-outlined text-[16px] text-gray-300 group-hover:text-primary transition-colors rtl:rotate-180">chevron_right</span>
</a>
</div>
</div>
</div>
</main>

<!-- Sell to Market Modal -->
<div id="sellModal" class="hidden fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
    <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-8 max-w-md w-full shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold">{{ __('crops.sell_to_market_title') }}</h3>
            <button onclick="document.getElementById('sellModal').classList.add('hidden')" class="size-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('crops.sell-to-market', $crop->id) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('price') }} ({{ __('currency') }})</label>
                    <input type="number" name="price" step="0.01" min="0" required class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark px-4 py-3 focus:ring-2 focus:ring-primary/50">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('quantity') }} ({{ __('kg') }})</label>
                    <input type="number" name="quantity" step="1" min="1" required class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark px-4 py-3 focus:ring-2 focus:ring-primary/50">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('description') }} ({{ __('optional') }})</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark px-4 py-3 focus:ring-2 focus:ring-primary/50 resize-none"></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('sellModal').classList.add('hidden')" class="flex-1 px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors font-medium">
                    {{ __('cancel') }}
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-primary text-background-dark rounded-lg hover:bg-primary-dark transition-colors font-bold shadow-lg shadow-primary/25">
                    {{ __('list_in_market') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Irrigation Modal -->
<div id="irrigationModal" class="hidden fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
    <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-8 max-w-md w-full shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold">{{ __('crops.irrigation_title') }}</h3>
            <button onclick="document.getElementById('irrigationModal').classList.add('hidden')" class="size-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('crops.irrigate', $crop->id) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('crops.irrigation_amount') }} ({{ __('liter') }})</label>
                    <input type="number" name="amount" step="1" min="1" value="50" required class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark px-4 py-3 focus:ring-2 focus:ring-primary/50">
                    <p class="text-xs text-text-muted mt-2">{{ __('crops.irrigation_note') }}</p>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('irrigationModal').classList.add('hidden')" class="flex-1 px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors font-medium">
                    {{ __('cancel') }}
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-bold shadow-lg shadow-blue-500/25">
                    {{ __('crops.confirm_irrigation') }}
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
