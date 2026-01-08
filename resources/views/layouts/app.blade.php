<!DOCTYPE html>
<html class="light" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{ __('dashboard') }} - Reefy</title>
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;700;800&family=Manrope:wght@300;400;500;700;800&display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
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
                        "border-light": "#dbe6db",
                        "border-dark": "#2a422a",
                        "text-secondary-light": "#618961",
                        "text-secondary-dark": "#8ba88b",
                    },
                    fontFamily: {
                        "display": ["Cairo", "Manrope", "sans-serif"],
                        "body": ["Cairo", "Manrope", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Cairo', 'Manrope', sans-serif;
        }
        /* Custom Scrollbar for cleaner look */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent; 
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24
        }
        .material-symbols-outlined.filled {
            font-variation-settings:
            'FILL' 1,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-text-main dark:text-white transition-colors duration-200 min-h-screen flex flex-col overflow-hidden">

<!-- Mobile Sidebar Overlay -->
<div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden" onclick="toggleMobileSidebar()"></div>

<div class="flex h-screen overflow-hidden">
<!-- Sidebar Navigation -->
<aside id="main-sidebar" class="w-72 bg-surface-light dark:bg-surface-dark border-l border-border-light dark:border-border-dark flex-col hidden lg:flex transition-all duration-300 z-40 fixed lg:relative h-full {{ app()->getLocale() == 'ar' ? '-right-72 lg:right-0' : '-left-72 lg:left-0' }} shadow-2xl lg:shadow-sm">
<div class="h-16 flex items-center px-6 border-b border-border-light dark:border-border-dark gap-3">
<div class="size-8 text-primary flex items-center justify-center">
<span class="material-symbols-outlined text-4xl">eco</span>
</div>
<h1 class="text-xl font-bold tracking-tight">{{ app()->getLocale() == 'ar' ? 'ريفي' : 'Reefy' }}</h1>
</div>
<div class="flex flex-col flex-1 overflow-y-auto p-4 gap-6">
<!-- User Profile Summary -->
<div class="flex items-center gap-3 p-3 bg-background-light dark:bg-background-dark rounded-xl border border-border-light dark:border-border-dark">
<div class="bg-center bg-no-repeat bg-cover rounded-full size-10 shrink-0 border-2 border-primary" style='background-image: url("https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=13ec13&color=000");'></div>
<div class="flex flex-col overflow-hidden">
<h2 class="text-sm font-bold truncate">{{ auth()->user()->name }}</h2>
<p class="text-xs text-text-secondary-light dark:text-text-secondary-dark truncate">{{ auth()->user()->email }}</p>
</div>
</div>
<!-- Navigation Links -->
<nav class="flex flex-col gap-1">
<a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-background-light dark:hover:bg-background-dark' }} rounded-lg transition-colors group" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'filled' : '' }} group-hover:text-primary transition-colors">dashboard</span>
<span class="text-sm {{ request()->routeIs('dashboard') ? 'font-bold' : 'font-medium' }}">{{ __('nav.dashboard') }}</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('crops.*') ? 'bg-primary/10 text-primary' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-background-light dark:hover:bg-background-dark' }} rounded-lg transition-colors group" href="{{ route('crops.index') }}">
<span class="material-symbols-outlined {{ request()->routeIs('crops.*') ? 'filled' : '' }} group-hover:text-primary transition-colors">potted_plant</span>
<span class="text-sm {{ request()->routeIs('crops.*') ? 'font-bold' : 'font-medium' }}">{{ __('nav.crops') }}</span>
</a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('inventory.index') && request('mode') == 'market' ? 'bg-primary/10 text-primary' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-background-light dark:hover:bg-background-dark' }} rounded-lg transition-colors group" href="{{ route('inventory.index', ['mode' => 'market']) }}">
                <span class="material-symbols-outlined {{ request()->routeIs('inventory.index') && request('mode') == 'market' ? 'filled' : '' }} group-hover:text-primary transition-colors">storefront</span>
                <span class="text-sm {{ request()->routeIs('inventory.index') && request('mode') == 'market' ? 'font-bold' : 'font-medium' }}">{{ __('nav.market') }}</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('chat.*') ? 'bg-primary/10 text-primary' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-background-light dark:hover:bg-background-dark' }} rounded-lg transition-colors group" href="{{ route('chat.index') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('chat.*') ? 'filled' : '' }} group-hover:text-primary transition-colors">chat</span>
                <span class="text-sm {{ request()->routeIs('chat.*') ? 'font-bold' : 'font-medium' }}">{{ __('nav.chat') }}</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('tasks.*') ? 'bg-primary/10 text-primary' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-background-light dark:hover:bg-background-dark' }} rounded-lg transition-colors group" href="{{ route('tasks.index') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('tasks.*') ? 'filled' : '' }} group-hover:text-primary transition-colors">task_alt</span>
                <span class="text-sm {{ request()->routeIs('tasks.*') ? 'font-bold' : 'font-medium' }}">{{ __('nav.tasks') }}</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('inventory.*') && request('mode') != 'market' ? 'bg-primary/10 text-primary' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-background-light dark:hover:bg-background-dark' }} rounded-lg transition-colors group" href="{{ route('inventory.index') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('inventory.*') && request('mode') != 'market' ? 'filled' : '' }} group-hover:text-primary transition-colors">inventory_2</span>
                <span class="text-sm {{ request()->routeIs('inventory.*') && request('mode') != 'market' ? 'font-bold' : 'font-medium' }}">{{ __('nav.inventory') }}</span>
            </a>
</nav>
<div class="mt-auto">
<div class="h-px bg-border-light dark:bg-border-dark my-2"></div>
<a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('settings.*') ? 'bg-primary/10 text-primary' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-background-light dark:hover:bg-background-dark' }} rounded-lg transition-colors group" href="{{ route('settings.index') }}">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">settings</span>
<span class="text-sm font-medium">{{ __('nav.settings') }}</span>
</a>
<form action="{{ route('logout') }}" method="POST">
@csrf
<button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-text-secondary-light dark:text-text-secondary-dark hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 rounded-lg transition-colors group">
<span class="material-symbols-outlined transition-colors">logout</span>
<span class="text-sm font-medium">{{ __('nav.logout') }}</span>
</button>
</form>
</div>
</div>
</aside>

<!-- Main Content Area -->
<main class="flex-1 flex flex-col h-full overflow-hidden relative">
@yield('content')
</main>
</div>

<script>
function toggleMobileSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const overlay = document.getElementById('mobile-overlay');
    const isRTL = document.documentElement.dir === 'rtl';
    const isOpen = sidebar.classList.contains('flex');
    
    if (isOpen) {
        // Close
        sidebar.style[isRTL ? 'right' : 'left'] = '-18rem';
        setTimeout(() => {
            sidebar.classList.remove('flex');
            sidebar.classList.add('hidden');
            overlay.classList.add('hidden');
        }, 300);
    } else {
        // Open
        sidebar.classList.remove('hidden');
        sidebar.classList.add('flex');
        overlay.classList.remove('hidden');
        setTimeout(() => sidebar.style[isRTL ? 'right' : 'left'] = '0', 10);
    }
}
</script>
</body>
</html>
