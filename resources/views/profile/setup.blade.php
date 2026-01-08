<!DOCTYPE html>
<html class="light" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ __('profile.setup.title') }} - Smart Farm Manager</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&amp;family=Noto+Sans+Arabic:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
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
                },
                fontFamily: {
                    "display": ["Manrope", "Noto Sans Arabic", "sans-serif"],
                    "body": ["Manrope", "Noto Sans Arabic", "sans-serif"]
                },
                borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
            },
        },
    }
</script>
<style>
    body { font-family: 'Manrope', 'Noto Sans Arabic', sans-serif; }
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#111811] dark:text-white">
<div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">
    <!-- Top Navigation -->
    <header class="bg-white dark:bg-[#1a1a1a] border-b border-solid border-b-[#f0f4f0] dark:border-b-[#2a2a2a] px-4 lg:px-10 py-3 sticky top-0 z-50">
        <div class="flex items-center justify-between mx-auto max-w-7xl">
            <div class="flex items-center gap-4 text-[#111811] dark:text-white">
                <div class="size-8 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-3xl">agriculture</span>
                </div>
                <h2 class="text-[#111811] dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">AgriMarket</h2>
            </div>
            
            <div class="flex items-center gap-4">
                 <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                    <span class="material-symbols-outlined text-lg">language</span>
                    <span class="text-sm font-bold">{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="hidden md:flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-[#111811] dark:text-white text-sm font-bold transition-colors">
                        <span class="truncate">{{ __('logout') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="layout-container flex h-full grow flex-col py-8 px-4 md:px-10">
        <div class="flex flex-1 justify-center">
            <div class="layout-content-container flex flex-col max-w-[960px] flex-1 w-full gap-6">
                <!-- Page Heading -->
                <div class="flex flex-wrap items-end justify-between gap-4 p-4">
                    <div class="flex min-w-72 flex-col gap-2">
                        <h1 class="text-[#111811] dark:text-white tracking-light text-[32px] font-bold leading-tight">{{ __('profile.setup.title') }}</h1>
                        <p class="text-[#618961] dark:text-[#8ab88a] text-base font-normal leading-normal">{{ __('profile.setup.subtitle') }}</p>
                    </div>
                </div>

                @if ($errors->any())
                <div class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-4 rounded-xl font-bold">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('profile.complete') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
                    @csrf
                    
                    <!-- Step 1: Photos Upload -->
                    <div class="bg-white dark:bg-[#1e1e1e] rounded-xl shadow-sm border border-[#f0f4f0] dark:border-[#333] p-6 md:p-8">
                        <div class="flex items-center gap-2 mb-6 border-b border-[#f0f4f0] dark:border-[#333] pb-4">
                            <span class="material-symbols-outlined text-primary">image</span>
                            <h3 class="text-lg font-bold text-[#111811] dark:text-white">{{ __('profile.setup.photos') }}</h3>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                            <!-- Avatar Upload -->
                            <div class="lg:col-span-4 flex flex-col items-center gap-4">
                                <p class="text-sm font-medium text-[#111811] dark:text-gray-200 self-start w-full text-center lg:text-right">{{ __('profile.setup.avatar_label') }}</p>
                                <div class="group relative w-40 h-40 rounded-full border-2 border-dashed border-[#13ec13] bg-[#f6f8f6] dark:bg-[#2a2a2a] flex flex-col items-center justify-center cursor-pointer hover:bg-[#ebfbeb] transition-colors overflow-hidden">
                                     <!-- Preview or Placeholder -->
                                    <div class="z-10 flex flex-col items-center justify-center text-[#111811] dark:text-white bg-white/80 dark:bg-black/50 p-2 rounded-lg backdrop-blur-sm pointer-events-none">
                                        <span class="material-symbols-outlined text-3xl mb-1 text-primary">cloud_upload</span>
                                        <span class="text-xs font-bold">{{ __('profile.setup.change_photo') }}</span>
                                    </div>
                                    <input name="avatar" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-20" type="file" accept="image/*"/>
                                </div>
                            </div>
                            <!-- Farm Cover Upload -->
                            <div class="lg:col-span-8 flex flex-col gap-4">
                                <p class="text-sm font-medium text-[#111811] dark:text-gray-200">{{ __('profile.setup.cover_label') }}</p>
                                <div class="group relative w-full h-48 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-primary bg-[#f6f8f6] dark:bg-[#2a2a2a] flex flex-col items-center justify-center cursor-pointer transition-all overflow-hidden">
                                    <div class="flex flex-col items-center gap-2 group-hover:scale-105 transition-transform duration-200 pointer-events-none">
                                        <div class="bg-white dark:bg-[#333] p-3 rounded-full shadow-sm">
                                            <span class="material-symbols-outlined text-3xl text-gray-400 group-hover:text-primary">add_photo_alternate</span>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm font-bold text-[#111811] dark:text-white">{{ __('profile.setup.drag_drop') }}</p>
                                        </div>
                                    </div>
                                    <input name="cover" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-20" type="file" accept="image/*"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Information -->
                    <div class="bg-white dark:bg-[#1e1e1e] rounded-xl shadow-sm border border-[#f0f4f0] dark:border-[#333] p-6 md:p-8">
                         <div class="flex items-center gap-2 mb-6 border-b border-[#f0f4f0] dark:border-[#333] pb-4">
                            <span class="material-symbols-outlined text-primary">person</span>
                            <h3 class="text-lg font-bold text-[#111811] dark:text-white">{{ __('profile.setup.contact_info') }}</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Age -->
                            <label class="flex flex-col gap-2">
                                <span class="text-sm font-bold text-text-main dark:text-gray-200">{{ __('profile.age') }}</span>
                                <input name="age" class="w-full h-12 px-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2a2a2a] text-text-main dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none" type="number" min="18" placeholder="30" required/>
                            </label>

                            <!-- Phone -->
                            <label class="flex flex-col gap-2">
                                <span class="text-sm font-bold text-text-main dark:text-gray-200">{{ __('profile.phone') }}</span>
                                <input name="phone" class="w-full h-12 px-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2a2a2a] text-text-main dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none" type="tel" placeholder="05xxxxxxxx" required/>
                            </label>

                            <!-- Bio -->
                            <label class="flex flex-col gap-2 md:col-span-2">
                                <span class="text-sm font-bold text-text-main dark:text-gray-200">{{ __('profile.bio') }}</span>
                                <textarea name="bio" rows="3" class="w-full p-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2a2a2a] text-text-main dark:text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none resize-none" placeholder="{{ __('profile.bio_placeholder') }}"></textarea>
                            </label>
                            
                             <!-- Social Media -->
                            <div class="md:col-span-2 space-y-4">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">{{ __('profile.social_links') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                     <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-blue-600 font-bold text-xs">FB</span>
                                        </div>
                                        <input name="facebook" class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2a2a2a] text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none" type="text" placeholder="Facebook Username"/>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-sky-500 font-bold text-xs">TW</span>
                                        </div>
                                        <input name="twitter" class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2a2a2a] text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none" type="text" placeholder="Twitter Username"/>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-pink-600 font-bold text-xs">IG</span>
                                        </div>
                                        <input name="instagram" class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2a2a2a] text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none" type="text" placeholder="Instagram Username"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Location -->
                    <div class="bg-white dark:bg-[#1e1e1e] rounded-xl shadow-sm border border-[#f0f4f0] dark:border-[#333] p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6 border-b border-[#f0f4f0] dark:border-[#333] pb-4">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">location_on</span>
                                <h3 class="text-lg font-bold text-[#111811] dark:text-white">{{ __('profile.setup.location') }}</h3>
                            </div>
                        </div>
                        <div class="flex flex-col gap-6">
                            <!-- Search Input -->
                            <div class="relative w-full">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none material-symbols-outlined text-gray-400">search</span>
                                <input name="address" class="block w-full p-4 pr-10 text-sm text-[#111811] dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-[#2a2a2a] focus:ring-primary focus:border-primary placeholder-gray-400" placeholder="{{ __('profile.setup.location_placeholder') }}" required type="text"/>
                            </div>
                            <!-- Static Map Placeholder for Visual -->
                            <div class="relative w-full h-[200px] rounded-xl overflow-hidden shadow-inner border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-[#1a1a1a] flex items-center justify-center">
                                <span class="text-gray-400 text-sm">{{ __('profile.setup.map_placeholder') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="flex flex-col-reverse md:flex-row justify-end items-center gap-4 mt-4 pt-4 border-t border-transparent">
                        <button type="submit" class="w-full md:w-auto flex items-center justify-center gap-2 px-10 h-12 rounded-lg bg-primary hover:bg-[#11d611] text-[#111811] text-base font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-0.5">
                            <span class="material-symbols-outlined">save</span>
                            <span>{{ __('profile.setup.save_continue') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>
