@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-32">
    <!-- Top Bar -->
    <header class="bg-white dark:bg-slate-900 sticky top-0 w-full z-30 shadow-sm border-b border-slate-100 dark:border-slate-800">
        <div class="px-5 py-4 flex justify-between items-center h-20">
            <h1 class="text-xl font-bold font-display text-slate-900 dark:text-white">{{ __('notifications.title') }}</h1>
            
            @if($notifications->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-bold text-primary hover:text-primary-dark transition-colors">
                    {{ __('notifications.mark_all_read') }}
                </button>
            </form>
            @endif
        </div>
    </header>

    <div class="p-5 md:max-w-3xl md:mx-auto pt-6">
        @if($notifications->count() > 0)
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border {{ $notification->read_at ? 'border-slate-100 dark:border-slate-700' : 'border-primary/30 bg-primary/5' }} transition-all relative group">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-{{ $notification->data['color'] ?? 'primary' }}/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-{{ $notification->data['color'] ?? 'primary' }}">{{ $notification->data['icon'] ?? 'notifications' }}</span>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h3>
                                    <span class="text-[10px] text-slate-400 whitespace-nowrap ml-2">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed mb-3">
                                    {{ $notification->data['body'] ?? '' }}
                                </p>
                                
                                <div class="flex items-center gap-3">
                                    @if(isset($notification->data['url']))
                                        <a href="{{ $notification->data['url'] }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:text-primary-dark transition-colors">
                                            {{ __('notifications.view_details') }}
                                            <span class="material-symbols-outlined text-[14px] rtl:rotate-180">arrow_forward</span>
                                        </a>
                                    @endif

                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-[10px] font-medium text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                                                {{ __('notifications.mark_read') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-4xl text-slate-300">notifications_off</span>
                </div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-1">{{ __('notifications.empty_title') }}</h3>
                <p class="text-xs text-slate-500">{{ __('notifications.empty_desc') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
