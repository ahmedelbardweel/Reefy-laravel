<!DOCTYPE html>
<html class="light" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ __('auth.login') }} - Smart Farm Manager</title>
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

<div class="fixed inset-0 z-50 bg-background-light dark:bg-background-dark flex flex-row overflow-hidden">
    <!-- Right Column: Form Container -->
    <div class="flex flex-col w-full lg:w-1/2 h-full overflow-y-auto bg-white dark:bg-background-dark scrollbar-hide relative z-10">
        <!-- Top Header -->
        <header class="flex items-center justify-between px-6 py-5 lg:px-12 lg:py-8">
            <div class="flex items-center gap-3">
                <div class="size-10">
                     <x-application-logo class="w-full h-full" />
                </div>
                <h2 class="text-xl font-bold leading-tight tracking-[-0.015em] text-text-main dark:text-white">{{ __('app.name') }}</h2>
            </div>
            <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-background-light dark:bg-white/5 hover:bg-border-light dark:hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined text-lg text-text-main dark:text-white">language</span>
                <span class="text-sm font-bold text-text-main dark:text-white">{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}</span>
            </a>
        </header>
        
        <!-- Form Content -->
        <div class="flex flex-1 flex-col justify-center px-6 lg:px-12 max-w-[600px] w-full mx-auto pb-10">
            <!-- Headings -->
            <div class="flex flex-col gap-3 mb-8">
                <h1 class="text-3xl lg:text-4xl font-black leading-tight tracking-tight text-text-main dark:text-white">
                    {{ __('auth.login') }}
                </h1>
                <p class="text-text-muted dark:text-gray-400 text-base lg:text-lg">
                    {{ __('auth.welcome_desc') }}
                </p>
            </div>

            @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-semibold">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Actual Form -->
            <form action="{{ route('login.post') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                
                <!-- Email -->
                <label class="flex flex-col gap-2">
                    <span class="text-sm font-bold text-text-main dark:text-gray-200">{{ __('auth.email') }}</span>
                    <input name="email" class="form-input w-full h-12 px-4 rounded-lg border border-border-light bg-white dark:bg-white/5 dark:border-white/10 text-text-main dark:text-white placeholder:text-text-muted/70 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="name@example.com" type="email" value="{{ old('email') }}" required/>
                </label>
                
                <!-- Password -->
                <label class="flex flex-col gap-2 relative group">
                    <span class="text-sm font-bold text-text-main dark:text-gray-200">{{ __('auth.password') }}</span>
                    <div class="relative">
                        <input name="password" class="form-input w-full h-12 px-4 rounded-lg border border-border-light bg-white dark:bg-white/5 dark:border-white/10 text-text-main dark:text-white placeholder:text-text-muted/70 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="•••••••••" type="password" required/>
                    </div>
                </label>

                <!-- Submit Button -->
                <button type="submit" class="flex items-center justify-center w-full h-12 mt-2 rounded-lg bg-primary hover:bg-primary-hover text-[#111811] font-bold text-base transition-all transform active:scale-[0.98]">
                    {{ __('auth.submit_login') }}
                </button>

                <!-- Social Divider -->
                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-border-light dark:border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-white dark:bg-background-dark px-4 text-text-muted dark:text-gray-400 font-medium">{{ __('auth.or_social') }}</span>
                    </div>
                </div>

                <!-- Social Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" class="flex h-12 items-center justify-center gap-3 rounded-xl border border-border-light dark:border-white/10 bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 transition-all">
                        <span class="text-sm font-bold text-text-main dark:text-white">Google</span>
                    </button>
                    <button type="button" class="flex h-12 items-center justify-center gap-3 rounded-xl border border-border-light dark:border-white/10 bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 transition-all">
                        <span class="text-sm font-bold text-text-main dark:text-white">Apple</span>
                    </button>
                </div>

                <!-- Register Link -->
                <div class="flex justify-center gap-2 mt-4 text-sm">
                    <span class="text-text-muted dark:text-gray-400">{{ __('onboarding.welcome.desc') }}?</span>
                    <a class="text-primary-hover dark:text-primary font-bold hover:underline" href="{{ route('register') }}">{{ __('auth.register') }}</a>
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
        <div class="absolute inset-0 bg-cover bg-center transition-all duration-700 ease-in-out" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCt263V-OWTeWvg7BOVJDh61LIzSGihJbwhw1788SUn4baheCkyPp6y6zahXF_z4Xe67d7LuhEjXcv9SVlpxcMtlGP6MDBeHwVpBRc555tKSGyfoMi6ZUN9u87Pzm5NwiuSjSQ1knp3wWitQNgEbcrYzBvknz-d-ZILCptr4xLy9Hlm91qfZOK-hDxV6EfPQryOmohPXFy-IScrFGC1_vafpwcR1_Vq1RoMYGdvU14sW-gAE7h1TU1KyRM9VicJsq2Ob-uu0_puKg");'></div>
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
        <!-- Content -->
        <div class="absolute bottom-0 right-0 p-12 w-full text-white">
            <div class="flex items-center gap-2 mb-4">
                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-sm font-medium flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">eco</span>
                    <span>{{ __('auth.smart_farming') }}</span>
                </span>
            </div>
            <blockquote class="text-2xl font-bold leading-relaxed mb-4">
                "{{ __('auth.testimonial_quote') }}"
            </blockquote>
            <div class="flex items-center gap-4">
                <div class="size-12 rounded-full bg-cover bg-center border-2 border-white/50" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAsn_eBA26GN3jV-DzwLhRwkcCIril5uZ-0c59ZvpTQfTooiUDkf6yEk5rMYn34tAMdnwANsMLcLwmI_jQ59wBcb-8RXTgTPYxW4fMn3UuYGhZAGL8gUZFgChHT9i-cCdVgMCiEIOrm3EzV--lORSE4kA6syt-4bMvtFRGItaHD03rEUbW-uVJmcetPZFxtHsHpEuGqAwjbcP0nzjVQ9I2V_oeSP20bbLWZYlCZmT3W4jZ_RQ5OWwKFhj-jrmAHSZNt0LavUqCxwQ");'></div>
                <div class="flex flex-col">
                    <span class="font-bold text-lg">{{ __('auth.testimonial_author') }}</span>
                    <span class="text-white/80 text-sm">{{ __('auth.testimonial_role') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
