<!DOCTYPE html>
<html class="light" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{ __('dashboard') }} - {{ __('app.name') }}</title>
    
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
        
        /* Force Sharp Corners Globally */
        *:not(.rounded-full):not(#mobile-overlay) {
            border-radius: 0px !important;
        }
        
        /* Ensure rounded-full keeps circles for avatars/buttons */
        .rounded-full {
            border-radius: 9999px !important;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-text-main dark:text-white transition-colors duration-200 min-h-screen flex flex-col overflow-hidden">

<!-- Mobile Sidebar Overlay -->
<div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden" onclick="toggleMobileSidebar()"></div>

<div class="flex h-screen overflow-hidden">
<!-- Sidebar Navigation -->
<aside id="main-sidebar" class="w-72 bg-gradient-to-b from-surface-light to-background-light dark:from-surface-dark dark:to-background-dark border-l border-border-light dark:border-border-dark flex-col hidden lg:flex transition-all duration-300 z-40 fixed lg:relative h-full {{ app()->getLocale() == 'ar' ? '-right-72 lg:right-0' : '-left-72 lg:left-0' }} shadow-2xl lg:shadow-none backdrop-blur-xl">
    <div class="h-20 flex items-center px-6 border-b border-white/10 gap-3 bg-white/5 backdrop-blur-sm">
        <div class="size-10 shadow-lg shadow-primary/20">
            <x-application-logo class="w-full h-full" />
        </div>
        <div class="flex flex-col">
           <h1 class="text-2xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-primary-dark to-primary">{{ __('app.name') }}</h1>
           <span class="text-[10px] uppercase tracking-wider font-bold text-text-secondary-light dark:text-text-secondary-dark opacity-70">{{ __('app.professional') }}</span>
        </div>
        
        <!-- Notification Bell -->
        <a href="{{ route('notifications.index') }}" class="ml-auto relative w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition-colors group">
            <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">notifications</span>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-[#1a2e1a]"></span>
            @endif
        </a>
    </div>

    <div class="flex flex-col flex-1 overflow-y-auto p-4 gap-6 scrollbar-hide">
        <!-- User Profile Summary -->
        <div class="group relative overflow-hidden rounded-2xl p-4 transition-all duration-300 hover:shadow-lg border border-border-light dark:border-border-dark bg-white/50 dark:bg-black/20 hover:bg-white/80 dark:hover:bg-white/5">
            <div class="flex items-center gap-4 relative z-10">
                <div class="relative">
                    <div class="bg-center bg-no-repeat bg-cover rounded-full size-12 shrink-0 border-2 border-white dark:border-white/10 shadow-md" style='background-image: url("https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=13ec13&color=fff");'></div>
                    <div class="absolute bottom-0 right-0 size-3 bg-green-500 rounded-full border-2 border-white dark:border-black"></div>
                </div>
                <div class="flex flex-col overflow-hidden">
                    <h2 class="text-base font-bold truncate text-text-main dark:text-white">{{ auth()->user()->name }}</h2>
                    <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark truncate font-medium">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex flex-col gap-2">
            <p class="px-4 text-xs font-bold text-text-secondary-light dark:text-text-secondary-dark uppercase tracking-wider opacity-60 mb-1">{{ __('nav.menu') }}</p>
            
            @if(auth()->user()->role === 'admin')
                <a class="flex items-center gap-4 px-4 py-3.5 {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-lg shadow-primary/25' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-white/60 dark:hover:bg-white/5' }} rounded-xl transition-all duration-300 group font-medium" href="{{ route('admin.dashboard') }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('admin.dashboard') ? 'filled' : '' }} group-hover:scale-110 transition-transform duration-300">admin_panel_settings</span>
                    <span class="{{ request()->routeIs('admin.dashboard') ? 'font-bold' : '' }}">{{ __('nav.dashboard') }}</span>
                    @if(request()->routeIs('admin.dashboard')) <span class="ml-auto size-1.5 rounded-full bg-white/50"></span> @endif
                </a>
            @else
                <a class="flex items-center gap-4 px-4 py-3.5 {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-lg shadow-primary/25' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-white/60 dark:hover:bg-white/5' }} rounded-xl transition-all duration-300 group font-medium" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'filled' : '' }} group-hover:scale-110 transition-transform duration-300">dashboard</span>
                    <span class="{{ request()->routeIs('dashboard') ? 'font-bold' : '' }}">{{ __('nav.dashboard') }}</span>
                    @if(request()->routeIs('dashboard')) <span class="ml-auto size-1.5 rounded-full bg-white/50"></span> @endif
                </a>

                @if(auth()->user()->role !== 'admin')
                <a class="flex items-center gap-4 px-4 py-3.5 {{ request()->routeIs('crops.*') ? 'bg-primary text-white shadow-lg shadow-primary/25' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-white/60 dark:hover:bg-white/5' }} rounded-xl transition-all duration-300 group font-medium" href="{{ route('crops.index') }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('crops.*') ? 'filled' : '' }} group-hover:scale-110 transition-transform duration-300">potted_plant</span>
                    <span class="{{ request()->routeIs('crops.*') ? 'font-bold' : '' }}">{{ __('nav.crops') }}</span>
                    @if(request()->routeIs('crops.*')) <span class="ml-auto size-1.5 rounded-full bg-white/50"></span> @endif
                </a>

                <a class="flex items-center gap-4 px-4 py-3.5 {{ request()->routeIs('tasks.*') ? 'bg-primary text-white shadow-lg shadow-primary/25' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-white/60 dark:hover:bg-white/5' }} rounded-xl transition-all duration-300 group font-medium" href="{{ route('tasks.index') }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('tasks.*') ? 'filled' : '' }} group-hover:scale-110 transition-transform duration-300">task_alt</span>
                    <span class="{{ request()->routeIs('tasks.*') ? 'font-bold' : '' }}">{{ __('nav.tasks') }}</span>
                    @if(request()->routeIs('tasks.*')) <span class="ml-auto size-1.5 rounded-full bg-white/50"></span> @endif
                </a>
                @endif

                <a class="flex items-center gap-4 px-4 py-3.5 {{ request()->routeIs('community.*') ? 'bg-primary text-white shadow-lg shadow-primary/25' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-white/60 dark:hover:bg-white/5' }} rounded-xl transition-all duration-300 group font-medium" href="{{ route('community.index') }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('community.*') ? 'filled' : '' }} group-hover:scale-110 transition-transform duration-300">forum</span>
                    <span class="{{ request()->routeIs('community.*') ? 'font-bold' : '' }}">{{ __('community') }}</span>
                    @if(request()->routeIs('community.*')) <span class="ml-auto size-1.5 rounded-full bg-white/50"></span> @endif
                </a>
            @endif



        </nav>

        <div class="mt-auto">
            <div class="h-px bg-gradient-to-r from-transparent via-border-light dark:via-border-dark to-transparent my-4"></div>
            
            <a class="flex items-center gap-4 px-4 py-3.5 {{ request()->routeIs('settings.*') ? 'bg-white/10 text-text-main dark:text-white' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-white/60 dark:hover:bg-white/5' }} rounded-xl transition-all duration-300 group font-medium" href="{{ route('settings.index') }}">
                <span class="material-symbols-outlined group-hover:rotate-45 transition-transform duration-500">settings</span>
                <span class="text-sm">{{ __('nav.settings') }}</span>
            </a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 px-4 py-3.5 text-red-500 hover:bg-red-500/10 rounded-xl transition-all duration-300 group font-bold mt-2">
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform duration-300 rtl:group-hover:-translate-x-1">logout</span>
                    <span class="text-sm">{{ __('nav.logout') }}</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Main Content Area -->
<main class="flex-1 flex flex-col h-full overflow-y-auto relative scroll-smooth">
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
