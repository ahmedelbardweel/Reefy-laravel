@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-24 pt-20" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Header -->
    <header class="px-5 py-4 bg-white dark:bg-slate-900 fixed top-0 w-full max-w-md mx-auto z-30 border-b border-slate-100 dark:border-slate-800 text-center transition-all bg-opacity-95 backdrop-blur-sm">
        <h1 class="text-lg font-bold font-display text-slate-900 dark:text-white">{{ __('settings.title') }}</h1>
    </header>

    <div class="p-5 space-y-6">
        
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-3 rounded-xl text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            {{ session('success') }}
        </div>
        @endif

        <!-- Profile Card -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div class="flex items-center gap-4">
                 <div class="w-14 h-14 rounded-full bg-slate-200 overflow-hidden border-2 border-white dark:border-slate-700">
                    <img src="https://i.pravatar.cc/150?u={{ $user->email }}" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <div>
                    <h2 class="font-bold text-slate-900 dark:text-white text-lg">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('settings.farm_name') }} - {{ __('farm') }}</p>
                    <a href="{{ route('settings.profile.edit') }}" class="text-[10px] text-green-600 font-bold mt-1 flex items-center gap-1 hover:underline">
                        <span class="material-symbols-outlined text-[12px]">edit</span>
                        {{ __('settings.edit_profile') }}
                    </a>
                </div>
            </div>
            <a href="{{ route('settings.profile.edit') }}" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined {{ app()->getLocale() == 'ar' ? 'rtl:rotate-180' : '' }}">chevron_right</span>
            </a>
        </div>

        <!-- Section: Account -->
        <div>
            <h3 class="text-xs font-bold text-slate-500 mb-3 px-2">{{ __('settings.account') }}</h3>
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <a href="{{ route('settings.password.edit') }}" class="flex px-4 py-4 items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors border-b border-slate-50 dark:border-slate-700 last:border-0">
                    <div class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">lock</span>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('settings.change_password') }}</span>
                    </div>
                     <span class="material-symbols-outlined text-slate-400 text-lg {{ app()->getLocale() == 'ar' ? 'rtl:rotate-180' : '' }}">chevron_right</span>
                </a>
                 <a href="#" class="flex px-4 py-4 items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <div class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">link</span>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('settings.linked_accounts') }}</span>
                    </div>
                     <span class="material-symbols-outlined text-slate-400 text-lg {{ app()->getLocale() == 'ar' ? 'rtl:rotate-180' : '' }}">chevron_right</span>
                </a>
            </div>
        </div>

        <!-- Section: App Preferences -->
        <div>
            <h3 class="text-xs font-bold text-slate-500 mb-3 px-2">{{ __('settings.preferences') }}</h3>
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <!-- Language -->
                <button onclick="showLanguageModal()" class="flex px-4 py-4 items-center justify-between border-b border-slate-50 dark:border-slate-700 w-full hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                     <div class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">language</span>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('settings.language') }}</span>
                    </div>
                    <div class="flex items-center gap-2 cursor-pointer">
                        <span class="text-xs text-slate-500 font-bold">{{ $settings->language == 'ar' ? 'العربية' : 'English' }}</span>
                        <span class="material-symbols-outlined text-slate-400 text-lg {{ app()->getLocale() == 'ar' ? 'rtl:rotate-180' : '' }}">chevron_right</span>
                    </div>
                </button>
                <!-- Units -->
                 <div class="flex px-4 py-4 items-center justify-between">
                     <div class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">straighten</span>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('settings.units') }}</span>
                    </div>
                    <div class="flex items-center gap-2 cursor-pointer">
                        <span class="text-xs text-slate-500 font-bold">{{ __('settings.units_' . $settings->units) }}</span>
                        <span class="material-symbols-outlined text-slate-400 text-lg {{ app()->getLocale() == 'ar' ? 'rtl:rotate-180' : '' }}">chevron_right</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Notifications -->
        <div>
            <h3 class="text-xs font-bold text-slate-500 mb-3 px-2">{{ __('settings.notifications') }}</h3>
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="flex px-4 py-4 items-center justify-between border-b border-slate-50 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">cloud</span>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('settings.weather_alerts') }}</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer notification-toggle" data-type="weather_alerts" {{ $settings->weather_alerts ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
                 <div class="flex px-4 py-4 items-center justify-between border-b border-slate-50 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">water_drop</span>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('settings.irrigation_reminders') }}</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer notification-toggle" data-type="irrigation_reminders" {{ $settings->irrigation_reminders ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
                  <div class="flex px-4 py-4 items-center justify-between">
                    <div class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">potted_plant</span>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('settings.crop_updates') }}</span>
                    </div>
                     <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer notification-toggle" data-type="crop_updates" {{ $settings->crop_updates ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Section: Support -->
        <div>
            <h3 class="text-xs font-bold text-slate-500 mb-3 px-2">{{ __('settings.support') }}</h3>
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <a href="#" class="flex px-4 py-4 items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors border-b border-slate-50 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">help</span>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('settings.help_center') }}</span>
                    </div>
                     <span class="material-symbols-outlined text-slate-400 text-lg {{ app()->getLocale() == 'ar' ? 'rtl:rotate-180' : '' }}">chevron_right</span>
                </a>
                 <a href="#" class="flex px-4 py-4 items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <div class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">security</span>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('settings.privacy_policy') }}</span>
                    </div>
                     <span class="material-symbols-outlined text-slate-400 text-lg {{ app()->getLocale() == 'ar' ? 'rtl:rotate-180' : '' }}">chevron_right</span>
                </a>
                <button onclick="resetOnboarding()" class="flex w-full px-4 py-4 items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <div class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg">slideshow</span>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('settings.view_intro') }}</span>
                    </div>
                     <span class="material-symbols-outlined text-slate-400 text-lg {{ app()->getLocale() == 'ar' ? 'rtl:rotate-180' : '' }}">chevron_right</span>
                </button>
            </div>
        </div>

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST" class="pt-4">
            @csrf
            <button type="submit" class="w-full bg-white dark:bg-slate-800 border border-red-100 dark:border-red-900/30 text-red-600 font-bold py-4 rounded-3xl flex items-center justify-center gap-2 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-xl">logout</span>
                {{ __('logout') }}
            </button>
        </form>
        
        <div class="text-center pb-6">
            <p class="text-[10px] text-slate-400">{{ __('settings.version') }} 1.0.4</p>
        </div>

    </div>
</div>

<!-- Language Selection Modal -->
<div id="languageModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-end justify-center">
    <div class="bg-white dark:bg-slate-800 rounded-t-3xl w-full max-w-md p-6 space-y-4 animate-slide-up">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('settings.language') }}</h3>
        
        <button onclick="selectLanguage('ar')" class="w-full flex items-center justify-between p-4 rounded-xl border-2 {{ $settings->language == 'ar' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-slate-200 dark:border-slate-700' }} hover:border-green-500 transition-all">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🇸🇦</span>
                <div class="text-right">
                    <p class="font-bold text-slate-900 dark:text-white">العربية</p>
                    <p class="text-xs text-slate-500">Arabic</p>
                </div>
            </div>
            @if($settings->language == 'ar')
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            @endif
        </button>

        <button onclick="selectLanguage('en')" class="w-full flex items-center justify-between p-4 rounded-xl border-2 {{ $settings->language == 'en' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-slate-200 dark:border-slate-700' }} hover:border-green-500 transition-all">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🇬🇧</span>
                <div class="text-left">
                    <p class="font-bold text-slate-900 dark:text-white">English</p>
                    <p class="text-xs text-slate-500">إنجليزي</p>
                </div>
            </div>
            @if($settings->language == 'en')
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            @endif
        </button>

        <button onclick="hideLanguageModal()" class="w-full p-3 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl">
            {{ __('cancel') }}
        </button>
    </div>
</div>

<script>
// Notification Toggle Handler
document.querySelectorAll('.notification-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const type = this.dataset.type;
        const value = this.checked;
        
        fetch('{{ route("settings.notifications.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ type: type, value: value })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                this.checked = !value;
                alert('{{ __("messages.error") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.checked = !value;
            alert('{{ __("messages.error") }}');
        });
    });
});

// Language Modal Functions
function showLanguageModal() {
    document.getElementById('languageModal').classList.remove('hidden');
}

function hideLanguageModal() {
    document.getElementById('languageModal').classList.add('hidden');
}

function selectLanguage(lang) {
    fetch('{{ route("settings.preferences.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type: 'language', value: lang })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.reload) {
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __("messages.error") }}');
    });
}

// Reset onboarding
function resetOnboarding() {
    localStorage.removeItem('onboardingCompleted');
    window.location.href = '/';
}
</script>
@endsection
