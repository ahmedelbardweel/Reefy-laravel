<!DOCTYPE html>
<html class="light" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{ $title ?? 'Market' }} - Reefy</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@100..900&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet"/>
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec13",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102210",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "Noto Sans Arabic", "sans-serif"],
                        "body": ["Noto Sans Arabic", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Noto Sans Arabic', 'Space Grotesk', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .icon-filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#111811] dark:text-white min-h-screen flex flex-col overflow-x-hidden">
    <!-- TopNavBar -->
    <header class="sticky top-0 z-50 bg-white dark:bg-[#1a2e1a] border-b border-[#f0f4f0] dark:border-[#2a3e2a] px-4 lg:px-10 py-3 shadow-sm">
        <div class="max-w-7xl mx-auto w-full flex items-center justify-between whitespace-nowrap">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-[#111811] dark:text-white">
                    <div class="size-8 text-primary">
                        <span class="material-symbols-outlined text-4xl">agriculture</span>
                    </div>
                    <h2 class="text-[#111811] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] font-display">{{ __('market.header_title') }}</h2>
                </a>
                <nav class="hidden md:flex items-center gap-9">
                    <a class="text-[#111811] dark:text-gray-200 text-sm font-medium hover:text-primary transition-colors" href="{{ route('dashboard') }}">{{ __('market.nav.home') }}</a>
                    <a class="text-[#111811] dark:text-gray-200 text-sm font-medium hover:text-primary transition-colors" href="{{ route('inventory.index', ['mode' => 'market']) }}">{{ __('market.nav.market') }}</a>
                    <a class="text-[#111811] dark:text-gray-200 text-sm font-medium hover:text-primary transition-colors" href="#">{{ __('market.nav.about') }}</a>
                    <a class="text-[#111811] dark:text-gray-200 text-sm font-medium hover:text-primary transition-colors" href="#">{{ __('market.nav.contact') }}</a>
                </nav>
            </div>
            <div class="flex flex-1 justify-end gap-4 lg:gap-8 items-center">
                 <!-- Language Switcher -->
                 <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" class="font-bold text-sm text-[#111811] dark:text-white hover:text-primary transition-colors">
                    {{ app()->getLocale() == 'ar' ? 'EN' : 'عربي' }}
                 </a>

                 <!-- Search -->
                <form action="{{ route('inventory.index') }}" method="GET" class="hidden md:flex flex-col min-w-40 !h-10 max-w-64">
                     <input type="hidden" name="mode" value="market">
                    <div class="flex w-full flex-1 items-stretch rounded-lg h-full bg-[#f0f4f0] dark:bg-[#253825]">
                        <div class="text-[#618961] flex border-none items-center justify-center pe-4 ps-2 rounded-e-lg">
                            <span class="material-symbols-outlined">search</span>
                        </div>
                        <input name="search" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg bg-transparent text-[#111811] dark:text-white focus:outline-0 focus:ring-0 border-none h-full placeholder:text-[#618961] px-2 text-base font-normal leading-normal" placeholder="{{ __('market.search_placeholder') }}" value="{{ request('search') }}"/>
                    </div>
                </form>
                
                <div class="flex gap-2">
                    @if(auth()->check() && auth()->user()->role === 'client')
                    <!-- Orders -->
                    <a href="{{ route('orders.index') }}" class="flex items-center justify-center rounded-lg size-10 bg-[#f0f4f0] dark:bg-[#253825] hover:bg-primary hover:text-black transition-colors" title="{{ __('orders.my_orders') }}">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </a>
                    <!-- Chat -->

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="flex items-center justify-center rounded-lg size-10 bg-[#f0f4f0] dark:bg-[#253825] hover:bg-primary hover:text-black transition-colors relative">
                        <span class="material-symbols-outlined">shopping_cart</span>
                         <!-- Cart Count Badge Mockup -->
                         <!-- <span class="absolute top-0 right-0 size-3 bg-red-500 rounded-full border-2 border-white"></span> -->
                    </a>
                    @endif
                    <button class="flex items-center justify-center rounded-lg size-10 bg-[#f0f4f0] dark:bg-[#253825] hover:bg-primary hover:text-black transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-center rounded-lg size-10 bg-[#f0f4f0] dark:bg-[#253825] hover:bg-primary hover:text-black transition-colors">
                        <span class="material-symbols-outlined">person</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Layout Container -->
    <main class="flex-grow w-full max-w-7xl mx-auto px-4 lg:px-10 py-6 lg:py-10">
        @yield('content')
    </main>

    <footer class="bg-white dark:bg-[#1a2e1a] border-t border-[#f0f4f0] dark:border-[#2a3e2a] py-8 mt-10">
        <div class="max-w-7xl mx-auto px-10 text-center text-gray-500 text-sm">
            <p>© 2024 {{ __('market.footer_rights') }} (Reefy).</p>
        </div>
    </footer>
</body>
</html>
