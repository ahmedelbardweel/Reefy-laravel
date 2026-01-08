<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('onboarding.welcome.title') }} - Smart Farm Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#13ec13',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap');
        body { font-family: 'Cairo', sans-serif; }
        .slide { display: none; }
        .slide.active { display: flex; }
    </style>
</head>
<body class="bg-white min-h-screen">
    <div class="max-w-md mx-auto min-h-screen flex flex-col justify-between p-6">
        <!-- Slides Container -->
        <div class="flex-1 flex items-center justify-center">
            <!-- Slide 1: Welcome -->
            <div class="slide active flex-col text-center" id="slide1">
                <div class="w-40 h-40 mx-auto mb-8 bg-gradient-to-br from-primary to-green-500 rounded-full flex items-center justify-center shadow-2xl">
                    <span class="text-8xl">🌾</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 mb-4">{{ __('onboarding.welcome.title') }}</h1>
                <h2 class="text-4xl font-black text-primary mb-6">Smart Farm Manager</h2>
                <p class="text-slate-600 text-lg leading-relaxed">
                    {!! __('onboarding.welcome.desc') !!}
                </p>
            </div>

            <!-- Slide 2: Community -->
            <div class="slide flex-col text-center" id="slide2">
                <div class="w-40 h-40 mx-auto mb-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-2xl">
                    <span class="material-symbols-outlined text-white" style="font-size: 80px;">groups</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 mb-4">{{ __('onboarding.community.title') }}</h1>
                <p class="text-slate-600 text-lg leading-relaxed px-6">
                    {!! __('onboarding.community.desc') !!}
                </p>
            </div>

            <!-- Slide 3: Market -->
            <div class="slide flex-col text-center" id="slide3">
                <div class="w-40 h-40 mx-auto mb-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-full flex items-center justify-center shadow-2xl">
                    <span class="material-symbols-outlined text-white" style="font-size: 80px;">storefront</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 mb-4">{{ __('onboarding.market.title') }}</h1>
                <p class="text-slate-600 text-lg leading-relaxed px-6">
                    {!! __('onboarding.market.desc') !!}
                </p>
            </div>

            <!-- Slide 4: Weather -->
            <div class="slide flex-col text-center" id="slide4">
                <div class="w-40 h-40 mx-auto mb-8 bg-gradient-to-br from-slate-500 to-slate-600 rounded-full flex items-center justify-center shadow-2xl">
                    <span class="material-symbols-outlined text-white" style="font-size: 80px;">partly_cloudy_day</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 mb-4">{{ __('onboarding.weather.title') }}</h1>
                <p class="text-slate-600 text-lg leading-relaxed px-6">
                    {!! __('onboarding.weather.desc') !!}
                </p>
            </div>

            <!-- Slide 5: Tasks & Irrigation -->
            <div class="slide flex-col text-center" id="slide5">
                <div class="w-40 h-40 mx-auto mb-8 bg-gradient-to-br from-primary to-green-600 rounded-full flex items-center justify-center shadow-2xl">
                    <span class="material-symbols-outlined text-white" style="font-size: 80px;">task_alt</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 mb-4">{{ __('onboarding.tasks.title') }}</h1>
                <p class="text-slate-600 text-lg leading-relaxed px-6">
                    {!! __('onboarding.tasks.desc') !!}
                </p>
            </div>

            <!-- Slide 6: Analytics -->
            <div class="slide flex-col text-center" id="slide6">
                <div class="w-40 h-40 mx-auto mb-8 bg-gradient-to-br from-blue-500 to-primary rounded-full flex items-center justify-center shadow-2xl">
                    <span class="material-symbols-outlined text-white" style="font-size: 80px;">analytics</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 mb-4">{{ __('onboarding.reports.title') }}</h1>
                <p class="text-slate-600 text-lg leading-relaxed px-6">
                    {!! __('onboarding.reports.desc') !!}
                </p>
            </div>
        </div>

        <!-- Dots Indicator -->
        <div class="flex justify-center gap-2 mb-6">
            <span class="dot w-2 h-2 rounded-full bg-primary transition-all" data-slide="1"></span>
            <span class="dot w-2 h-2 rounded-full bg-slate-300 transition-all" data-slide="2"></span>
            <span class="dot w-2 h-2 rounded-full bg-slate-300 transition-all" data-slide="3"></span>
            <span class="dot w-2 h-2 rounded-full bg-slate-300 transition-all" data-slide="4"></span>
            <span class="dot w-2 h-2 rounded-full bg-slate-300 transition-all" data-slide="5"></span>
            <span class="dot w-2 h-2 rounded-full bg-slate-300 transition-all" data-slide="6"></span>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3">
            <button id="skipBtn" onclick="skip()" class="px-6 py-3 rounded-xl text-slate-500 font-bold hover:bg-white/50 transition-all">
                {{ __('onboarding.skip') }}
            </button>
            <button id="nextBtn" onclick="next()" class="flex-1 px-6 py-4 rounded-xl bg-primary text-slate-900 font-bold shadow-lg shadow-primary/30 hover:bg-[#0fd60f] transition-all">
                {{ __('onboarding.next') }}
            </button>
        </div>
    </div>

    <script>
        // Check if user has seen onboarding before
        if (localStorage.getItem('onboardingCompleted') === 'true') {
            window.location.href = '/login';
        }

        let currentSlide = 1;
        const totalSlides = 6;

        function showSlide(n) {
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.dot');
            
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('bg-primary', 'w-8'));
            dots.forEach(dot => dot.classList.add('bg-slate-300', 'w-2'));
            
            document.getElementById(`slide${n}`).classList.add('active');
            dots[n-1].classList.remove('bg-slate-300', 'w-2');
            dots[n-1].classList.add('bg-primary', 'w-8');
            
            // Update button text on last slide
            const nextBtn = document.getElementById('nextBtn');
            if (n === totalSlides) {
                nextBtn.textContent = "{{ __('onboarding.login') }}";
                nextBtn.onclick = login;
            } else {
                nextBtn.textContent = "{{ __('onboarding.next') }}";
                nextBtn.onclick = next;
            }
        }

        function next() {
            if (currentSlide < totalSlides) {
                currentSlide++;
                showSlide(currentSlide);
            }
        }

        function skip() {
            login();
        }

        function login() {
            // Mark onboarding as completed
            localStorage.setItem('onboardingCompleted', 'true');
            window.location.href = '/login';
        }

        // Swipe support
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            if (touchEndX < touchStartX - 50) next();
            if (touchEndX > touchStartX + 50 && currentSlide > 1) {
                currentSlide--;
                showSlide(currentSlide);
            }
        }
    </script>
</body>
</html>
