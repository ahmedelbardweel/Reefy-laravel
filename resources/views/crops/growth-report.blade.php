<!DOCTYPE html>
<html class="light" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ __('reports.growth_report') }} - {{ $crop->name }}</title>
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
        @media print {
            .no-print {display: none;}
            body {background: white; color: black;}
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-text-main dark:text-white min-h-screen">
<!-- Header -->
<header class="sticky top-0 z-50 bg-surface-light dark:bg-surface-dark border-b border-[#e5e7eb] dark:border-[#2a3e2a] px-6 py-3 shadow-sm no-print">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('crops.show', $crop->id) }}" class="flex items-center gap-2 text-text-muted hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
                <span class="text-sm font-medium">{{ __('back') }}</span>
            </a>
            <div class="h-6 w-px bg-gray-200 dark:bg-gray-700"></div>
            <h1 class="text-lg font-bold">{{ __('reports.growth_report') }}</h1>
        </div>
        <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-primary text-background-dark rounded-lg hover:bg-primary-dark transition-colors">
            <span class="material-symbols-outlined text-[20px]">print</span>
            <span class="font-bold text-sm">{{ __('print') }}</span>
        </button>
    </div>
</header>

<!-- Main Content -->
<main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
    <!-- Report Header -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-8 shadow-sm border border-[#e5e7eb] dark:border-[#2a3e2a] mb-8">
        <div class="flex items-start gap-6">
            <div class="size-24 rounded-xl bg-cover bg-center shrink-0 shadow-md" style='background-image: url("{{ $crop->image_url ?? "https://placehold.co/200x200?text=Crop" }}");'></div>
            <div class="flex-1">
                <h2 class="text-3xl font-black mb-2">{{ $crop->name }}</h2>
                <div class="flex flex-wrap gap-4 text-sm text-text-muted">
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">category</span>
                        <span>{{ __('type') }}: {{ $crop->type }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">event</span>
                        <span>{{ __('crops.planting_date') }}: {{ \Carbon\Carbon::parse($crop->planting_date)->isoFormat('LL') }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                        <span>{{ __('duration') }}: {{ \Carbon\Carbon::parse($crop->planting_date)->diffInDays(now()) }} {{ __('days') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-[#e5e7eb] dark:border-[#2a3e2a]">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-text-muted">{{ __('reports.current_status') }}</p>
                <span class="material-symbols-outlined text-primary filled">eco</span>
            </div>
            <p class="text-2xl font-bold">{{ ucfirst($crop->status ?? 'good') }}</p>
        </div>
        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-[#e5e7eb] dark:border-[#2a3e2a]">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-text-muted">{{ __('reports.water_usage') }}</p>
                <span class="material-symbols-outlined text-blue-500">water_drop</span>
            </div>
            <p class="text-2xl font-bold">2,450{{ __('liter') }}</p>
        </div>
        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-[#e5e7eb] dark:border-[#2a3e2a]">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-text-muted">{{ __('reports.growth_rate') }}</p>
                <span class="material-symbols-outlined text-green-500">trending_up</span>
            </div>
            <p class="text-2xl font-bold">92%</p>
        </div>
        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-[#e5e7eb] dark:border-[#2a3e2a]">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-text-muted">{{ __('reports.expected_yield') }}</p>
                <span class="material-symbols-outlined text-yellow-500">inventory_2</span>
            </div>
            <p class="text-2xl font-bold">175 {{ __('kg') }}</p>
        </div>
    </div>

    <!-- Growth Chart Placeholder -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-8 shadow-sm border border-[#e5e7eb] dark:border-[#2a3e2a] mb-8">
        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">show_chart</span>
            {{ __('reports.growth_chart') }}
        </h3>
        <div class="h-64 bg-background-light dark:bg-background-dark rounded-xl flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600">
            <div class="text-center">
                <span class="material-symbols-outlined text-6xl text-gray-400 mb-2">analytics</span>
                <p class="text-text-muted">{{ __('reports.chart_placeholder') }}</p>
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-8 shadow-sm border border-[#e5e7eb] dark:border-[#2a3e2a]">
        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">history</span>
            {{ __('reports.activity_timeline') }}
        </h3>
        <div class="space-y-4">
            @php
            $activities = [
                ['icon' => 'sprout', 'color' => 'green', 'title' => 'بداية الزراعة', 'date' => $crop->planting_date],
                ['icon' => 'water_drop', 'color' => 'blue', 'title' => 'جدولة الري الأولى', 'date' => \Carbon\Carbon::parse($crop->planting_date)->addDays(3)->toDateString()],
                ['icon' => 'science', 'color' => 'yellow', 'title' => 'إضافة سماد عضوي', 'date' => \Carbon\Carbon::parse($crop->planting_date)->addDays(14)->toDateString()],
                ['icon' => 'nutrition', 'color' => 'orange', 'title' => 'مرحلة النضج', 'date' => \Carbon\Carbon::parse($crop->planting_date)->addDays(60)->toDateString()],
            ];
            @endphp
            @foreach($activities as $activity)
            <div class="flex gap-4 pb-4 border-b border-gray-100 dark:border-gray-800 last:border-0">
                <div class="size-12 rounded-full bg-{{ $activity['color'] }}-100 dark:bg-{{ $activity['color'] }}-900/20 text-{{ $activity['color'] }}-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">{{ $activity['icon'] }}</span>
                </div>
                <div class="flex-1">
                    <p class="font-bold">{{ $activity['title'] }}</p>
                    <p class="text-sm text-text-muted">{{ \Carbon\Carbon::parse($activity['date'])->isoFormat('LL') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-text-muted">
        <p>{{ __('reports.generated_on') }}: {{ \Carbon\Carbon::now()->isoFormat('LLLL') }}</p>
        <p class="mt-1">AgriConnect - {{ __('reports.smart_farming_platform') }}</p>
    </div>
</main>
</body>
</html>
