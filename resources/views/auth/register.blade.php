<!DOCTYPE html>
<html class="light" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ __('auth.register') }} - Smart Farm Manager</title>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&amp;family=Noto+Sans+Arabic:wght@400;500;700;800&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#13ec13",
                    "primary-hover": "#0fb80f",
                    "background-light": "#f6f8f6",
                    "background-dark": "#102210",
                    "text-main": "#111811",
                    "text-muted": "#618961",
                    "border-light": "#dbe6db",
                },
                fontFamily: {
                    "display": ["Manrope", "Noto Sans Arabic", "sans-serif"],
                    "sans": ["Manrope", "Noto Sans Arabic", "sans-serif"],
                },
                borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1rem", "full": "9999px" },
            },
        },
    }
</script>
<style>
    body { font-family: 'Noto Sans Arabic', 'Manrope', sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .rtl-flip { @apply rtl:rotate-180; }
</style>
</head>
<body class="bg-background-light dark:bg-background-dark min-h-screen flex flex-col font-display text-text-main dark:text-white transition-colors duration-200 overflow-hidden">

<!-- Step 1: Role Selection -->
<div id="step-role" class="flex-1 flex flex-col min-h-screen">
    <!-- Navbar -->
    <div class="relative w-full bg-white dark:bg-[#1A2E1A] border-b border-[#f0f4f0] dark:border-[#2A3E2A]">
        <div class="layout-container flex justify-center">
            <div class="flex items-center justify-between w-full max-w-[960px] px-6 py-4">
                <!-- Logo Area -->
                <div class="flex items-center gap-3 text-text-main dark:text-white cursor-pointer">
                    <div class="size-8 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined !text-3xl">eco</span>
                    </div>
                    <h2 class="text-xl font-bold leading-tight tracking-[-0.015em]">AgriMarket</h2>
                </div>
                <!-- Language Toggle -->
                <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" class="flex items-center justify-center gap-2 rounded-lg h-10 px-4 bg-[#f0f4f0] dark:bg-[#2A3E2A] text-text-main dark:text-white text-sm font-bold hover:bg-gray-200 dark:hover:bg-[#3A4E3A] transition-colors">
                    <span class="material-symbols-outlined !text-[20px]">language</span>
                    <span>{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col items-center justify-center px-4 py-8 md:py-12 overflow-y-auto">
        <div class="layout-content-container flex flex-col max-w-[800px] w-full gap-8">
            <!-- Page Heading -->
            <div class="text-center space-y-3">
                <h1 class="text-text-main dark:text-white text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]">
                    {{ __('auth.role_selection.title') }}
                </h1>
                <p class="text-text-muted dark:text-[#9ABD9A] text-lg font-medium">
                    {{ __('auth.role_selection.subtitle') }}
                </p>
            </div>
            
            <!-- Error Message Display -->
            @if ($errors->any())
            <div class="w-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-4 rounded-xl text-center font-bold">
                {{ $errors->first() }}
            </div>
            @endif

            <!-- Role Selection Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full mt-4">
                <!-- Farmer Card -->
                <label class="group relative cursor-pointer">
                    <input class="peer sr-only" name="role_selector" type="radio" value="farmer" onchange="selectRole('farmer')"/>
                    <div id="card-farmer" class="relative flex flex-col items-center p-8 rounded-2xl border-2 border-transparent bg-white dark:bg-[#1A2E1A] shadow-md hover:shadow-xl transition-all duration-300 peer-checked:border-primary peer-checked:shadow-[0_0_20px_rgba(19,236,19,0.15)] h-full">
                        <div class="absolute top-4 left-4 size-6 rounded-full bg-primary text-black opacity-0 peer-checked:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="material-symbols-outlined !text-base font-bold">check</span>
                        </div>
                        <div class="size-24 rounded-full bg-primary/10 flex items-center justify-center mb-6 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined !text-5xl text-primary">agriculture</span>
                        </div>
                        <div class="text-center space-y-2">
                            <h3 class="text-2xl font-bold text-text-main dark:text-white">{{ __('auth.role.farmer') }}</h3>
                            <p class="text-text-muted dark:text-[#A0CBA0] text-sm leading-relaxed">
                                {{ __('auth.role.farmer_desc') }}
                            </p>
                        </div>
                        <div class="w-16 h-1 rounded-full bg-gray-100 dark:bg-gray-700 mt-6 peer-checked:bg-primary transition-colors"></div>
                    </div>
                </label>
                <!-- Buyer Card -->
                <label class="group relative cursor-pointer">
                    <input class="peer sr-only" name="role_selector" type="radio" value="client" onchange="selectRole('client')"/>
                    <div id="card-client" class="relative flex flex-col items-center p-8 rounded-2xl border-2 border-transparent bg-white dark:bg-[#1A2E1A] shadow-sm hover:shadow-lg transition-all duration-300 peer-checked:border-primary peer-checked:shadow-[0_0_20px_rgba(19,236,19,0.15)] h-full">
                        <div class="absolute top-4 left-4 size-6 rounded-full bg-primary text-black opacity-0 peer-checked:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="material-symbols-outlined !text-base font-bold">check</span>
                        </div>
                        <div class="size-24 rounded-full bg-gray-100 dark:bg-[#2A3E2A] flex items-center justify-center mb-6 group-hover:bg-gray-200 dark:group-hover:bg-[#354A35] transition-colors">
                            <span class="material-symbols-outlined !text-5xl text-gray-600 dark:text-gray-300">storefront</span>
                        </div>
                        <div class="text-center space-y-2">
                            <h3 class="text-2xl font-bold text-text-main dark:text-white">{{ __('auth.role.client') }}</h3>
                            <p class="text-text-muted dark:text-[#A0CBA0] text-sm leading-relaxed">
                                {{ __('auth.role.client_desc') }}
                            </p>
                        </div>
                        <div class="w-16 h-1 rounded-full bg-gray-100 dark:bg-gray-700 mt-6 peer-checked:bg-primary transition-colors"></div>
                    </div>
                </label>
            </div>
            <!-- Actions -->
            <div class="flex flex-col items-center gap-4 mt-4 w-full max-w-[480px] mx-auto">
                <button onclick="goToStep2()" id="btn-next" disabled class="w-full cursor-pointer flex items-center justify-center overflow-hidden rounded-xl h-14 px-5 bg-primary hover:bg-[#0fdc0f] text-text-main text-lg font-bold leading-normal tracking-[0.015em] transition-transform active:scale-[0.98] shadow-lg shadow-primary/20 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="truncate">{{ __('auth.continue') }}</span>
                    <span class="material-symbols-outlined mr-2 rtl:mr-0 rtl:ml-2">arrow_forward</span>
                </button>
                <a class="text-text-muted dark:text-[#9ABD9A] text-sm font-semibold hover:text-primary dark:hover:text-primary transition-colors underline decoration-2 underline-offset-4 decoration-transparent hover:decoration-current" href="{{ route('login') }}">
                    {{ __('auth.already_have_account') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Step 2: Registration Form -->
<div id="step-form" class="hidden fixed inset-0 z-50 bg-background-light dark:bg-background-dark flex-row overflow-hidden">
    <!-- Right Column: Form Container -->
    <div class="flex flex-col w-full lg:w-1/2 h-full overflow-y-auto bg-white dark:bg-background-dark scrollbar-hide relative z-10">
        <!-- Top Header -->
        <header class="flex items-center justify-between px-6 py-5 lg:px-12 lg:py-8">
            <button onclick="goBackToStep1()" class="flex items-center gap-2 text-text-muted hover:text-text-main dark:hover:text-white transition-colors">
                <span class="material-symbols-outlined rtl:rotate-180">arrow_back</span>
                <span class="text-sm font-bold">{{ __('auth.back') }}</span>
            </button>
            <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-background-light dark:bg-white/5 hover:bg-border-light dark:hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined text-lg">language</span>
                <span class="text-sm font-bold">{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}</span>
            </button>
        </header>
        
        <!-- Form Content -->
        <div class="flex flex-1 flex-col justify-center px-6 lg:px-12 max-w-[600px] w-full mx-auto pb-10">
            <!-- Headings -->
            <div class="flex flex-col gap-3 mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex items-center justify-center size-10 rounded-xl bg-primary/20 text-primary-hover">
                        <span id="role-icon-display" class="material-symbols-outlined text-3xl">agriculture</span>
                    </div>
                    <h2 id="role-title-display" class="text-xl font-bold tracking-tight text-text-main dark:text-white">{{ __('auth.role.farmer') }}</h2>
                </div>
                <h1 class="text-3xl lg:text-4xl font-black leading-tight tracking-tight text-text-main dark:text-white">
                    {{ __('auth.create_account') }}
                </h1>
                <p class="text-text-muted dark:text-gray-400 text-base lg:text-lg">
                    {{ __('auth.register_desc') }}
                </p>
            </div>

            <!-- Actual Form -->
            <form action="{{ route('register') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <input type="hidden" name="role" id="role-input" value="">
                
                <!-- Full Name -->
                <label class="flex flex-col gap-2">
                    <span class="text-sm font-bold text-text-main dark:text-gray-200">{{ __('auth.name') }}</span>
                    <input name="name" class="form-input w-full h-12 px-4 rounded-lg border border-border-light bg-white dark:bg-white/5 dark:border-white/10 text-text-main dark:text-white placeholder:text-text-muted/70 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="{{ __('auth.placeholder.name') }}" type="text" required/>
                </label>
                <!-- Farm/Business Name Placeholders if needed later, but sticking to standard fields for now -->
                
                <!-- Email -->
                <label class="flex flex-col gap-2">
                    <span class="text-sm font-bold text-text-main dark:text-gray-200">{{ __('auth.email') }}</span>
                    <input name="email" class="form-input w-full h-12 px-4 rounded-lg border border-border-light bg-white dark:bg-white/5 dark:border-white/10 text-text-main dark:text-white placeholder:text-text-muted/70 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="name@example.com" type="email" required/>
                </label>
                
                <!-- Password -->
                <label class="flex flex-col gap-2 relative group">
                    <span class="text-sm font-bold text-text-main dark:text-gray-200">{{ __('auth.password') }}</span>
                    <div class="relative">
                        <input name="password" class="form-input w-full h-12 px-4 rounded-lg border border-border-light bg-white dark:bg-white/5 dark:border-white/10 text-text-main dark:text-white placeholder:text-text-muted/70 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="•••••••••" type="password" required/>
                    </div>
                </label>

                <!-- Terms -->
                <label class="flex items-start gap-3 mt-2 cursor-pointer">
                    <input class="form-checkbox size-5 rounded border-border-light text-primary focus:ring-primary mt-0.5" type="checkbox" required/>
                    <span class="text-sm text-text-muted dark:text-gray-400 leading-normal">
                        {{ __('auth.terms_agreement') }} <a class="text-text-main dark:text-white underline decoration-primary font-bold hover:text-primary transition-colors" href="#">{{ __('auth.terms') }}</a>
                    </span>
                </label>

                <!-- Submit Button -->
                <button type="submit" class="flex items-center justify-center w-full h-12 mt-2 rounded-lg bg-primary hover:bg-primary-hover text-[#111811] font-bold text-base transition-all transform active:scale-[0.98]">
                    {{ __('auth.submit_register') }}
                </button>

                <!-- Login Link -->
                <div class="flex justify-center gap-2 mt-4 text-sm">
                    <span class="text-text-muted dark:text-gray-400">{{ __('auth.already_have_account') }}</span>
                    <a class="text-primary-hover dark:text-primary font-bold hover:underline" href="{{ route('login') }}">{{ __('auth.login') }}</a>
                </div>
            </form>
        </div>
        
        <!-- Trust Badge -->
        <div class="px-6 py-6 lg:px-12 border-t border-border-light dark:border-white/5 text-center mt-auto">
            <div class="flex items-center justify-center gap-2 text-text-muted dark:text-gray-500 text-xs font-medium">
                <span class="material-symbols-outlined text-base">verified_user</span>
                <span>{{ __('auth.secure_registration') }}</span>
            </div>
        </div>
    </div>

    <!-- Left Column: Visual Anchor (Hidden on Mobile) -->
    <div class="hidden lg:block w-1/2 h-full relative bg-gray-100 dark:bg-gray-900 border-l border-white/10">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center transition-all duration-700 ease-in-out" id="side-image" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCt263V-OWTeWvg7BOVJDh61LIzSGihJbwhw1788SUn4baheCkyPp6y6zahXF_z4Xe67d7LuhEjXcv9SVlpxcMtlGP6MDBeHwVpBRc555tKSGyfoMi6ZUN9u87Pzm5NwiuSjSQ1knp3wWitQNgEbcrYzBvknz-d-ZILCptr4xLy9Hlm91qfZOK-hDxV6EfPQryOmohPXFy-IScrFGC1_vafpwcR1_Vq1RoMYGdvU14sW-gAE7h1TU1KyRM9VicJsq2Ob-uu0_puKg");'></div>
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
        <!-- Content -->
        <div class="absolute bottom-0 right-0 p-12 w-full text-white">
            <div class="flex items-center gap-2 mb-4">
                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-sm font-medium flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">eco</span>
                    <span id="testimonial-tag">{{ __('auth.smart_farming') }}</span>
                </span>
            </div>
            <blockquote class="text-2xl font-bold leading-relaxed mb-4" id="testimonial-quote">
                "{{ __('auth.testimonial_quote') }}"
            </blockquote>
            <div class="flex items-center gap-4">
                <div class="size-12 rounded-full bg-cover bg-center border-2 border-white/50" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAsn_eBA26GN3jV-DzwLhRwkcCIril5uZ-0c59ZvpTQfTooiUDkf6yEk5rMYn34tAMdnwANsMLcLwmI_jQ59wBcb-8RXTgTPYxW4fMn3UuYGhZAGL8gUZFgChHT9i-cCdVgMCiEIOrm3EzV--lORSE4kA6syt-4bMvtFRGItaHD03rEUbW-uVJmcetPZFxtHsHpEuGqAwjbcP0nzjVQ9I2V_oeSP20bbLWZYlCZmT3W4jZ_RQ5OWwKFhj-jrmAHSZNt0LavUqCxwQ");'></div>
                <div class="flex flex-col">
                    <span class="font-bold text-lg" id="testimonial-author">{{ __('auth.testimonial_author') }}</span>
                    <span class="text-white/80 text-sm" id="testimonial-role">{{ __('auth.testimonial_role') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedRole = '';

    function selectRole(role) {
        selectedRole = role;
        document.getElementById('btn-next').disabled = false;
        
        // Update styling for active state if needed beyond peer-checked
        // This is handled nicely by Tailwind's peer-checked
    }

    function goToStep2() {
        if (!selectedRole) return;
        
        // Update Step 2 UI based on Role
        const roleIcon = document.getElementById('role-icon-display');
        const roleTitle = document.getElementById('role-title-display');
        const roleInput = document.getElementById('role-input');
        
        roleInput.value = selectedRole;

        if (selectedRole === 'farmer') {
            roleIcon.textContent = 'agriculture';
            roleTitle.textContent = "{{ __('auth.role.farmer') }}";
            // Could swap images/testimonials here too
        } else {
            roleIcon.textContent = 'storefront';
            roleTitle.textContent = "{{ __('auth.role.client') }}";
        }

        document.getElementById('step-role').classList.add('hidden');
        document.getElementById('step-form').classList.remove('hidden');
        document.getElementById('step-form').classList.add('flex');
    }

    function goBackToStep1() {
        document.getElementById('step-form').classList.add('hidden');
        document.getElementById('step-form').classList.remove('flex');
        document.getElementById('step-role').classList.remove('hidden');
    }
</script>

</body>
</html>
