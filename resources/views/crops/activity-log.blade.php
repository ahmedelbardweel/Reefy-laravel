<!DOCTYPE html>
<html class="light" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ __('crops.activity_log_title') }} - {{ $crop->name }}</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;700;800&amp;family=Manrope:wght@300;400;500;700;800&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                    },
                },
            },
        }
    </script>
    <style>
        body {font-family: 'Cairo', 'Manrope', sans-serif;}
        .material-symbols-outlined {font-variation-settings: 'FILL' 0, 'wght' 400;}
        .material-symbols-outlined.filled {font-variation-settings: 'FILL' 1;}
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-text-main dark:text-white min-h-screen">
<!-- Header -->
<header class="sticky top-0 z-50 bg-surface-light dark:bg-surface-dark border-b border-[#e5e7eb] dark:border-[#2a3e2a] px-6 py-3 shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('crops.show', $crop->id) }}" class="flex items-center gap-2 text-text-muted hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
                <span class="text-sm font-medium">{{ __('back') }}</span>
            </a>
            <div class="h-6 w-px bg-gray-200 dark:bg-gray-700"></div>
            <h1 class="text-lg font-bold">{{ __('crops.activity_log_title') }} - {{ $crop->name }}</h1>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
    <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-8 shadow-sm border border-[#e5e7eb] dark:border-[#2a3e2a]">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black">{{ __('crops.show.history') }}</h2>
                <p class="text-sm text-text-muted mt-1">{{ __('crops.activity_log_desc') }}</p>
            </div>
            <div class="text-sm text-text-muted">
                {{ __('total') }}: {{ $irrigations->total() }} {{ __('operations') }}
            </div>
        </div>

        <!-- Timeline -->
        <div class="flex flex-col gap-0 relative">
            <!-- Line -->
            <div class="absolute right-[19px] top-2 bottom-4 w-0.5 bg-gray-100 dark:bg-gray-800 rtl:right-[19px] ltr:left-[19px]"></div>

            @forelse($irrigations as $irrigation)
            <!-- Irrigation Item -->
            <div class="relative flex gap-4 pb-8 last:pb-0">
                <div class="z-10 mt-1 size-10 rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center shrink-0 border-4 border-surface-light dark:border-surface-dark">
                    <span class="material-symbols-outlined text-[20px]">water_drop</span>
                </div>
                <div class="flex flex-col gap-1">
                    <p class="text-sm font-bold text-text-main dark:text-white">{{ __('crops.timeline.irrigation') }} - {{ $irrigation->amount }} {{ __('liter') }}</p>
                    <span class="text-xs text-text-muted">{{ \Carbon\Carbon::parse($irrigation->created_at)->isoFormat('LLLL') }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">description</span>
                <p class="text-text-muted">{{ __('crops.no_activities') }}</p>
            </div>
            @endforelse

            @if($irrigations->hasPages())
            <!-- Pagination -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    @if($irrigations->onFirstPage())
                        <span class="px-4 py-2 text-gray-400 cursor-not-allowed">{{ __('previous') }}</span>
                    @else
                        <a href="{{ $irrigations->previousPageUrl() }}" class="px-4 py-2 bg-primary text-background-dark rounded-lg hover:bg-primary-dark transition-colors font-medium">{{ __('previous') }}</a>
                    @endif

                    <span class="text-sm text-text-muted">
                        {{ __('page') }} {{ $irrigations->currentPage() }} {{ __('of') }} {{ $irrigations->lastPage() }}
                    </span>

                    @if($irrigations->hasMorePages())
                        <a href="{{ $irrigations->nextPageUrl() }}" class="px-4 py-2 bg-primary text-background-dark rounded-lg hover:bg-primary-dark transition-colors font-medium">{{ __('next') }}</a>
                    @else
                        <span class="px-4 py-2 text-gray-400 cursor-not-allowed">{{ __('next') }}</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</main>
</body>
</html>
