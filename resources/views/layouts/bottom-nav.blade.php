<nav id="bottom-nav" class="fixed bottom-0 left-0 w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 pb-safe-bottom z-40 transition-transform duration-300 ease-in-out">
    <div class="flex justify-around items-center h-16 max-w-md md:max-w-2xl mx-auto">
        @if(auth()->user() && auth()->user()->role === 'client')
            <!-- CLIENT NAVIGATION -->
            
            <!-- Home (Dashboard) -->
            <a class="flex flex-col items-center gap-1 w-16 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }} transition-colors" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'fill-current' : '' }}">home</span>
                <span class="text-[10px] {{ request()->routeIs('dashboard') ? 'font-bold' : 'font-medium' }}">{{ __('home') }}</span>
            </a>

            <!-- Shop (Market) -->
            <a class="flex flex-col items-center gap-1 w-16 {{ request()->routeIs('market.*') ? 'text-primary' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }} transition-colors" href="{{ route('market.index') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('market.*') ? 'fill-current' : '' }}">storefront</span>
                <span class="text-[10px] {{ request()->routeIs('market.*') ? 'font-bold' : 'font-medium' }}">{{ __('market') }}</span>
            </a>

            <!-- Cart (Center) -->
            <div class="-mt-8 relative z-50">
                <a href="{{ route('cart.index') }}" 
                   class="w-14 h-14 rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-105 border-4 border-white dark:border-slate-900 
                   {{ request()->routeIs('cart.*') ? 'bg-primary text-slate-900 shadow-primary/40' : 'bg-slate-800 text-white shadow-slate-900/30' }}">
                    <span class="material-symbols-outlined text-[28px]">shopping_cart</span>
                </a>
                <span class="block text-center text-[10px] font-bold mt-1 {{ request()->routeIs('cart.*') ? 'text-primary' : 'text-slate-900 dark:text-slate-400' }}">{{ __('cart') }}</span>
            </div>

            <!-- Chat / Negotiations -->
            <a class="flex flex-col items-center gap-1 w-16 {{ request()->routeIs('chat.*') ? 'text-primary' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }} transition-colors" href="{{ route('chat.index') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('chat.*') ? 'fill-current' : '' }}">chat</span>
                <span class="text-[10px] {{ request()->routeIs('chat.*') ? 'font-bold' : 'font-medium' }}">{{ __('chats') }}</span>
            </a>

             <!-- Settings -->
            <a class="flex flex-col items-center gap-1 w-16 {{ request()->routeIs('settings.*') ? 'text-primary' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }} transition-colors" href="{{ route('settings.index') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('settings.*') ? 'fill-current' : '' }} text-[24px]">settings</span>
                <span class="text-[10px] font-bold">{{ __('settings') }}</span>
            </a>

        @else
            <!-- FARMER NAVIGATION (Existing) -->
            
            <!-- Home -->
            <a class="flex flex-col items-center gap-1 w-16 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }} transition-colors" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'fill-current' : '' }}">home</span>
                <span class="text-[10px] {{ request()->routeIs('dashboard') ? 'font-bold' : 'font-medium' }}">{{ __('home') }}</span>
            </a>

            <!-- Community -->
            <a class="flex flex-col items-center gap-1 w-16 {{ request()->routeIs('community.*') ? 'text-primary' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }} transition-colors" href="{{ route('community.index') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('community.*') ? 'fill-current' : '' }}">forum</span>
                <span class="text-[10px] {{ request()->routeIs('community.*') ? 'font-bold' : 'font-medium' }}">{{ __('community') }}</span>
            </a>

            <!-- Market / Inventory (Center Highlighted) -->
            <div class="-mt-8 relative z-50">
                <a href="{{ route('inventory.index', ['mode' => 'market']) }}" 
                class="w-14 h-14 rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-105 border-4 border-white dark:border-slate-900
                {{ request()->routeIs('inventory.*') ? 'bg-primary text-slate-900 shadow-primary/40' : 'bg-slate-800 text-white shadow-slate-900/30' }}">
                    <span class="material-symbols-outlined text-[28px]">storefront</span>
                </a>
                <span class="block text-center text-[10px] font-bold mt-1 {{ request()->routeIs('inventory.*') ? 'text-primary' : 'text-slate-900 dark:text-slate-400' }}">{{ __('market') }}</span>
            </div>

            <!-- Reports -->
            <a class="flex flex-col items-center gap-1 min-w-[3rem] {{ request()->routeIs('reports.*') ? 'text-primary' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }} transition-colors" href="{{ route('reports.index') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('reports.*') ? 'fill-current' : '' }} text-[24px]">analytics</span>
                <span class="text-[10px] font-bold">{{ __('reports') }}</span>
            </a>

            <!-- Settings -->
            <a class="flex flex-col items-center gap-1 min-w-[3rem] {{ request()->routeIs('settings.*') ? 'text-primary' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }} transition-colors" href="{{ route('settings.index') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('settings.*') ? 'fill-current' : '' }} text-[24px]">settings</span>
                <span class="text-[10px] font-bold">{{ __('settings') }}</span>
            </a>
        @endif
    </div>
</nav>

<script>
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            const bottomNav = document.getElementById('bottom-nav');
            if (!bottomNav) return;

            let lastScrollY = window.scrollY || 0;
            let ticking = false;

            const updateNav = (currentScrollY) => {
                // Avoid jitter: only act if moved more than 10px
                if (Math.abs(currentScrollY - lastScrollY) > 10) {
                    if (currentScrollY > lastScrollY && currentScrollY > 60) {
                        // Scrolling Down - Hide
                        bottomNav.style.transform = 'translateY(100%)';
                    } else {
                        // Scrolling Up - Show
                        bottomNav.style.transform = 'translateY(0)';
                    }
                    lastScrollY = currentScrollY;
                }
                ticking = false;
            };

            const onScroll = (e) => {
                const scrollPos = e.target === document ? window.scrollY : e.target.scrollTop;
                if (!ticking) {
                    window.requestAnimationFrame(() => updateNav(scrollPos));
                    ticking = true;
                }
            };

            // Global window scroll
            window.addEventListener('scroll', onScroll, { passive: true });
            
            // Nested container scrolls (for layouts with overflow-y-auto on main/div)
            document.querySelectorAll('.overflow-y-auto, main, .contents').forEach(el => {
                el.addEventListener('scroll', onScroll, { passive: true });
            });
        });
    })();
</script>
