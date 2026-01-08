@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-24 pt-24">
    
    <!-- Top Bar -->
    <header class="bg-white dark:bg-slate-900 fixed top-0 w-full max-w-md md:max-w-full mx-auto z-30 shadow-sm border-b border-slate-100 dark:border-slate-800 transition-all duration-300 left-0 right-0">
        <div class="px-5 py-4 flex justify-between items-center h-20">
            <div class="flex items-center gap-3">
                <a href="{{ route('community.index') }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </a>
                <h1 class="text-xl font-bold font-display text-slate-900 dark:text-white">{{ __('community.my_profile') }}</h1>
            </div>
            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                <span class="material-symbols-outlined">person</span>
            </div>
        </div>
    </header>

    <div class="px-5 md:px-0 md:max-w-3xl md:mx-auto">
        <!-- Profile Header -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 mb-6 text-center">
            <div class="w-24 h-24 rounded-full bg-slate-100 mx-auto mb-4 overflow-hidden border-4 border-primary/50 shadow-lg">
                <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAseTHkQVGEWevD9ODCBjnX5J3m5B3oRlqcNeL7eR9DhcI9kWSXdDVea84ROc3OIItDRsGov4m_Ci4bA8K53kcQsDrOR611uMr7Jq0gx1qkPHm2qw8sNPfEn2u0MftWHUGS5JXv86gd-oQsB8vh-U_xRBfg63xxGPzGl0B63BNlFWF_yjpurTmgqnq92gi_03CD3bHHnE6yWCaNQIdfgdCEMsLAGw26QA8iSXLn5mgXuFB4pZ2iub2WjYNpHOeklng7HHN6RLqWHzw');"></div>
            </div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $user->name }}</h2>
            <p class="text-xs text-slate-500 mb-6">{{ __('profile.member_since') }} {{ $user->created_at->format('M Y') }}</p>
            
            <div class="flex justify-center gap-8 border-t border-slate-100 dark:border-slate-700 pt-6">
                <div class="text-center">
                    <span class="block text-2xl font-bold text-slate-900 dark:text-white">{{ $posts->count() }}</span>
                    <span class="text-xs text-slate-500">{{ __('profile.posts_count') }}</span>
                </div>
                <div class="text-center">
                    <span class="block text-2xl font-bold text-slate-900 dark:text-white">{{ $posts->where('type', 'question')->count() }}</span>
                    <span class="text-xs text-slate-500">{{ __('profile.questions_count') }}</span>
                </div>
                <div class="text-center">
                    <span class="block text-2xl font-bold text-slate-900 dark:text-white">{{ $posts->sum('likes_count') }}</span>
                    <span class="text-xs text-slate-500">{{ __('profile.likes_count') }}</span>
                </div>
            </div>
        </div>

        <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-4 px-2">{{ __('profile.recent_activity') }}</h3>

        <!-- Feed -->
        <div class="space-y-4">
            @forelse($posts as $post)
            <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-100 overflow-hidden border-2 border-primary/30">
                             <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAseTHkQVGEWevD9ODCBjnX5J3m5B3oRlqcNeL7eR9DhcI9kWSXdDVea84ROc3OIItDRsGov4m_Ci4bA8K53kcQsDrOR611uMr7Jq0gx1qkPHm2qw8sNPfEn2u0MftWHUGS5JXv86gd-oQsB8vh-U_xRBfg63xxGPzGl0B63BNlFWF_yjpurTmgqnq92gi_03CD3bHHnE6yWCaNQIdfgdCEMsLAGw26QA8iSXLn5mgXuFB4pZ2iub2WjYNpHOeklng7HHN6RLqWHzw');"></div>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $post->user->name ?? __('market.farmer') }}</h4>
                            <p class="text-[10px] text-slate-400">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-lg text-[10px] font-bold 
                        {{ $post->type == 'question' ? 'bg-blue-50 text-blue-600' : ($post->type == 'tip' ? 'bg-amber-50 text-amber-600' : 'bg-slate-50 text-slate-500') }}">
                        @if($post->type == 'question') {{ __('community.type.question') }}
                        @elseif($post->type == 'tip') {{ __('community.type.tip') }}
                        @else {{ __('community.type.general') }}
                        @endif
                    </span>
                </div>
                
                <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed mb-4">
                    {{ $post->content }}
                </p>
                
                <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700">
                    <div class="flex items-center gap-1.5 text-slate-400">
                         <span class="material-symbols-outlined text-[20px] {{ $post->likes_count > 0 ? 'fill-current text-red-500' : '' }}">favorite</span>
                         <span class="text-xs font-bold">{{ $post->likes_count }}</span>
                    </div>
                    
                    <div class="flex items-center gap-1.5 text-slate-400">
                        <span class="material-symbols-outlined text-[20px]">chat_bubble</span>
                        <span class="text-xs font-bold">{{ $post->comments_count }}</span>
                        <span class="text-[10px]">{{ __('community.comment') }}</span>
                    </div>
                    
                    <button class="flex items-center gap-1.5 text-slate-400 hover:text-green-500 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">share</span>
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-10">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-3xl text-slate-400">post_add</span>
                </div>
                <h3 class="text-slate-900 dark:text-white font-bold">{{ __('profile.no_posts') }}</h3>
                <p class="text-slate-500 text-xs mt-1">{{ __('profile.no_posts_desc') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
