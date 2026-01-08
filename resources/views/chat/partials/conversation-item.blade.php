@php
    $otherUser = $conversation->sender_id == auth()->id() ? $conversation->receiver : $conversation->sender;
    $lastMessage = $conversation->messages->last();
@endphp
<a href="{{ route('chat.show', $conversation->id) }}" class="block bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-md transition-all">
    <div class="flex gap-4">
        <div class="relative">
            <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-lg font-bold text-slate-500">
                {{ substr($otherUser->name, 0, 1) }}
            </div>
            <!-- Online Status Indicator (Mock) -->
            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-slate-800 rounded-full"></div>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start mb-1">
                <h3 class="font-bold text-slate-900 dark:text-white truncate">{{ $otherUser->name }}</h3>
                <span class="text-xs text-slate-400 whitespace-nowrap">
                    {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                </span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 truncate">
                @if($conversation->product)
                    <span class="bg-primary/10 text-primary text-[10px] px-1.5 py-0.5 rounded font-bold ml-1">
                        {{ $conversation->product->name }}
                    </span>
                    @if($conversation->negotiation && $conversation->negotiation->status)
                         <span class="bg-amber-100 text-amber-700 text-[10px] px-1.5 py-0.5 rounded font-bold ml-1">
                            {{ $conversation->negotiation->status == 'accepted' ? __('negotiation.status.accepted') : __('negotiation.status.negotiating') }}
                        </span>
                    @endif
                @endif
                {{ $lastMessage ? $lastMessage->content : __('chat.start_conversation') }}
            </p>
        </div>
    </div>
</a>
