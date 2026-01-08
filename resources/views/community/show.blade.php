@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-32">
    
    <!-- Top Bar -->
    <header class="bg-white dark:bg-slate-900 fixed top-0 w-full max-w-md md:max-w-full mx-auto z-30 shadow-sm border-b border-slate-100 dark:border-slate-800 transition-all duration-300 left-0 right-0">
        <div class="px-5 py-4 flex justify-between items-center h-20">
            <div class="flex items-center gap-3">
                <a href="{{ route('community.index') }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </a>
                <h1 class="text-xl font-bold font-display text-slate-900 dark:text-white">{{ __('community.post_title') }}</h1>
            </div>
        </div>
    </header>

    <div class="px-5 md:px-0 md:max-w-3xl md:mx-auto pt-24">
        <!-- Original Post -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 mb-6">
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
                <button class="flex items-center gap-1.5 text-slate-400 hover:text-red-500 transition-colors group" onclick="likePost({{ $post->id }}, this)">
                    <span class="material-symbols-outlined text-[20px] group-hover:fill-current">favorite</span>
                    <span class="text-xs font-bold like-count">{{ $post->likes_count }}</span>
                </button>
                
                <div class="flex items-center gap-1.5 text-blue-500">
                    <span class="material-symbols-outlined text-[20px]">chat_bubble</span>
                    <span class="text-xs font-bold">{{ $post->comments_count }}</span>
                    <span class="text-[10px]">{{ __('community.comment') }}</span>
                </div>
                
                <button class="flex items-center gap-1.5 text-slate-400 hover:text-green-500 transition-colors" onclick="sharePost('{{ route('community.show', $post) }}')">
                    <span class="material-symbols-outlined text-[20px]">share</span>
                </button>
            </div>
        </div>

        <!-- Comments Section -->
        <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-4 px-2">{{ __('community.comments_title') }} ({{ $post->comments->where('parent_id', null)->count() }})</h3>
        
        <div class="space-y-3 mb-20">
            @forelse($post->comments->where('parent_id', null) as $comment)
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-100 overflow-hidden shrink-0 border-2 border-primary/30">
                         <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAseTHkQVGEWevD9ODCBjnX5J3m5B3oRlqcNeL7eR9DhcI9kWSXdDVea84ROc3OIItDRsGov4m_Ci4bA8K53kcQsDrOR611uMr7Jq0gx1qkPHm2qw8sNPfEn2u0MftWHUGS5JXv86gd-oQsB8vh-U_xRBfg63xxGPzGl0B63BNlFWF_yjpurTmgqnq92gi_03CD3bHHnE6yWCaNQIdfgdCEMsLAGw26QA8iSXLn5mgXuFB4pZ2iub2WjYNpHOeklng7HHN6RLqWHzw');"></div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <h5 class="font-bold text-slate-900 dark:text-white text-sm">{{ $comment->user->name ?? __('market.farmer') }}</h5>
                            <span class="text-[10px] text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed mb-2">
                            {{ $comment->content }}
                        </p>
                        <div class="flex items-center gap-4">
                            <button class="flex items-center gap-1 text-slate-400 hover:text-red-500 transition-colors text-xs" onclick="likeComment({{ $comment->id }}, this)">
                                <span class="material-symbols-outlined text-[16px]">favorite</span>
                                <span class="like-count">{{ $comment->likes_count }}</span>
                            </button>
                            <button class="text-xs text-slate-400 hover:text-blue-600 font-medium transition-colors" onclick="toggleReply({{ $comment->id }})">
                                {{ __('community.reply') }}
                            </button>
                        </div>
                        
                        <!-- Nested Replies -->
                        @if($comment->replies->count() > 0)
                        <div class="mt-3 space-y-2 pr-6 border-r-2 border-slate-100 dark:border-slate-700">
                            @foreach($comment->replies as $reply)
                            <div class="flex items-start gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-100 overflow-hidden shrink-0 border-2 border-primary/30">
                                     <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAseTHkQVGEWevD9ODCBjnX5J3m5B3oRlqcNeL7eR9DhcI9kWSXdDVea84ROc3OIItDRsGov4m_Ci4bA8K53kcQsDrOR611uMr7Jq0gx1qkPHm2qw8sNPfEn2u0MftWHUGS5JXv86gd-oQsB8vh-U_xRBfg63xxGPzGl0B63BNlFWF_yjpurTmgqnq92gi_03CD3bHHnE6yWCaNQIdfgdCEMsLAGw26QA8iSXLn5mgXuFB4pZ2iub2WjYNpHOeklng7HHN6RLqWHzw');"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <h6 class="font-bold text-slate-900 dark:text-white text-xs">{{ $reply->user->name ?? __('market.farmer') }}</h6>
                                        <span class="text-[9px] text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-slate-700 dark:text-slate-300 text-xs leading-relaxed mb-1">
                                        {{ $reply->content }}
                                    </p>
                                    <button class="flex items-center gap-1 text-slate-400 hover:text-red-500 transition-colors text-xs" onclick="likeComment({{ $reply->id }}, this)">
                                        <span class="material-symbols-outlined text-[14px]">favorite</span>
                                        <span class="like-count">{{ $reply->likes_count }}</span>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        
                        <!-- Reply Form (Hidden by default) -->
                        <form action="{{ route('community.comment', $post) }}" method="POST" class="mt-3 hidden reply-form" id="reply-form-{{ $comment->id }}">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <div class="flex items-center gap-2">
                                <input type="text" name="content" placeholder="{{ __('community.write_reply') }}" class="flex-1 h-8 px-3 rounded-full bg-slate-50 dark:bg-slate-700 border-none focus:ring-2 focus:ring-primary/50 text-xs" required>
                                <button type="submit" class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shrink-0">
                                    <span class="material-symbols-outlined text-[16px] rtl:-rotate-180">send</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-10">
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="material-symbols-outlined text-2xl text-slate-400">chat_bubble</span>
                </div>
                <p class="text-slate-500 text-sm">{{ __('community.no_comments') }}</p>
                <p class="text-slate-400 text-xs mt-1">{{ __('community.first_comment') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Fixed Comment Input (Hidden from post author) -->
    @if(Auth::id() != $post->user_id)
    <div class="fixed bottom-20 left-0 right-0 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 z-50 shadow-lg">
        <div class="max-w-md md:max-w-3xl mx-auto px-5 py-4">
            <form action="{{ route('community.comment', $post) }}" method="POST" class="flex items-center gap-3">
                @csrf
                <div class="w-8 h-8 rounded-full bg-slate-100 overflow-hidden shrink-0 border-2 border-primary/30">
                     <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAseTHkQVGEWevD9ODCBjnX5J3m5B3oRlqcNeL7eR9DhcI9kWSXdDVea84ROc3OIItDRsGov4m_Ci4bA8K53kcQsDrOR611uMr7Jq0gx1qkPHm2qw8sNPfEn2u0MftWHUGS5JXv86gd-oQsB8vh-U_xRBfg63xxGPzGl0B63BNlFWF_yjpurTmgqnq92gi_03CD3bHHnE6yWCaNQIdfgdCEMsLAGw26QA8iSXLn5mgXuFB4pZ2iub2WjYNpHOeklng7HHN6RLqWHzw');"></div>
                </div>
                <input type="text" name="content" placeholder="{{ __('community.write_comment') }}" class="flex-1 h-10 px-4 rounded-full bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary/50 text-sm" required>
                <button type="submit" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20 shrink-0">
                    <span class="material-symbols-outlined text-[20px] rtl:-rotate-180">send</span>
                </button>
            </form>
        </div>
    </div>
    @endif
</div>

<script>
    function likePost(postId, btn) {
        fetch(`/community/${postId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                btn.querySelector('.like-count').textContent = data.likes;
                btn.classList.add('text-red-500');
                btn.querySelector('.material-symbols-outlined').classList.add('fill-current');
            }
        });
    }

    function likeComment(commentId, btn) {
        fetch(`/comments/${commentId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                btn.querySelector('.like-count').textContent = data.likes;
                btn.classList.add('text-red-500');
                btn.querySelector('.material-symbols-outlined').classList.add('fill-current');
            }
        });
    }

    function toggleReply(commentId) {
        const form = document.getElementById(`reply-form-${commentId}`);
        // Hide all other reply forms
        document.querySelectorAll('.reply-form').forEach(f => {
            if (f.id !== `reply-form-${commentId}`) {
                f.classList.add('hidden');
            }
        });
        // Toggle current form
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            form.querySelector('input[name="content"]').focus();
        }
    }

    function sharePost(url) {
        if (navigator.share) {
            navigator.share({
                title: '{{ __('community.share_title') }}',
                text: '{{ __('community.share_text') }}',
                url: url
            }).catch(err => console.log('Error sharing:', err));
        } else {
            navigator.clipboard.writeText(url).then(() => {
                alert('{{ __('community.link_copied') }}');
            });
        }
    }
</script>
@endsection
