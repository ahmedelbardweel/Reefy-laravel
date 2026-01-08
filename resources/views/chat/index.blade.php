@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-24 pt-6 px-5" x-data="{ tab: 'chats' }">
    <h1 class="text-2xl font-bold font-display text-slate-900 dark:text-white mb-6">{{ __('chat.title') }}</h1>

    <!-- Tabs -->
    <div class="flex p-1 bg-white dark:bg-slate-800 rounded-xl mb-6 shadow-sm border border-slate-100 dark:border-slate-700">
        <button @click="tab = 'chats'" 
            :class="{ 'bg-primary text-slate-900 shadow-sm': tab === 'chats', 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700': tab !== 'chats' }"
            class="flex-1 py-2 rounded-lg text-sm font-bold transition-all">
            {{ __('chat.tab.chats') }}
        </button>
        <button @click="tab = 'negotiations'" 
            :class="{ 'bg-primary text-slate-900 shadow-sm': tab === 'negotiations', 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700': tab !== 'negotiations' }"
            class="flex-1 py-2 rounded-lg text-sm font-bold transition-all">
            {{ __('chat.tab.negotiations') }}
        </button>
    </div>

    <!-- Chats List -->
    <div x-show="tab === 'chats'" class="space-y-3" style="display: none;">
        @if($chats->isEmpty())
             <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">chat_bubble_outline</span>
                </div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">{{ __('chat.no_chats_title') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm">{{ __('chat.no_chats_desc') }}</p>
            </div>
        @else
            @foreach($chats as $conversation)
                @include('chat.partials.conversation-item', ['conversation' => $conversation])
            @endforeach
        @endif
    </div>

    <!-- Negotiations List -->
    <div x-show="tab === 'negotiations'" class="space-y-3">
        @if($negotiations->isEmpty())
             <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">handshake</span>
                </div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">{{ __('chat.no_negotiations_title') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm">{{ __('chat.no_negotiations_desc') }}</p>
            </div>
        @else
            @foreach($negotiations as $conversation)
                @include('chat.partials.conversation-item', ['conversation' => $conversation])
            @endforeach
        @endif
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
