@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-24 pt-24">
    
    <!-- Top Bar -->
    <header class="bg-white dark:bg-slate-900 fixed top-0 w-full max-w-md md:max-w-full mx-auto z-30 shadow-sm border-b border-slate-100 dark:border-slate-800 transition-all duration-300 left-0 right-0">
        <div class="px-5 py-4 flex justify-between items-center h-20">
            <div>
                <h1 class="text-xl font-bold font-display text-slate-900 dark:text-white">{{ __('community.title') }}</h1>
                <p class="text-xs text-slate-500">{{ __('community.subtitle') }}</p>
            </div>
            <a href="{{ route('community.profile') }}" class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors" title="{{ __('community.my_profile') }}">
                <span class="material-symbols-outlined">person</span>
            </a>
        </div>
    </header>

    <div class="px-5 md:px-0 md:max-w-3xl md:mx-auto">
        <!-- Create Post Card -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 mb-6">
            <form action="{{ route('community.store') }}" method="POST">
                @csrf
                <div class="flex gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-100 overflow-hidden shrink-0 border-2 border-primary/30">
                       <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAseTHkQVGEWevD9ODCBjnX5J3m5B3oRlqcNeL7eR9DhcI9kWSXdDVea84ROc3OIItDRsGov4m_Ci4bA8K53kcQsDrOR611uMr7Jq0gx1qkPHm2qw8sNPfEn2u0MftWHUGS5JXv86gd-oQsB8vh-U_xRBfg63xxGPzGl0B63BNlFWF_yjpurTmgqnq92gi_03CD3bHHnE6yWCaNQIdfgdCEMsLAGw26QA8iSXLn5mgXuFB4pZ2iub2WjYNpHOeklng7HHN6RLqWHzw');"></div>
                    </div>
                    <div class="flex-1">
                        <textarea name="content" rows="2" class="w-full bg-slate-50 dark:bg-slate-700/50 border-none rounded-xl p-3 text-sm focus:ring-0 resize-none placeholder-slate-400" placeholder="{{ __('community.create_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-3 px-1">
                    <div class="flex gap-2 flex-wrap">
                        <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-green-50 text-green-600 text-xs font-bold hover:bg-green-100 transition-colors">
                            <input type="radio" name="type" value="general" class="hidden" checked onchange="updateType(this)">
                            {{ __('community.type.general') }}
                        </label>
                         <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-50 text-slate-500 text-xs font-bold hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <input type="radio" name="type" value="question" class="hidden" onchange="updateType(this)">
                            {{ __('community.type.question') }}
                        </label>
                         <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-50 text-slate-500 text-xs font-bold hover:bg-amber-50 hover:text-amber-600 transition-colors">
                            <input type="radio" name="type" value="tip" class="hidden" onchange="updateType(this)">
                            {{ __('community.type.tip') }}
                        </label>
                         <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-50 text-slate-500 text-xs font-bold hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <input type="radio" name="type" value="marketplace" class="hidden" onchange="updateType(this)">
                            {{ __('community.type.marketplace') }}
                        </label>
                    </div>
                    <button type="submit" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
                        <span class="material-symbols-outlined text-[20px] rtl:-rotate-180">send</span>
                    </button>
                </div>
                
                <!-- Product Selector (shown when marketplace is selected) -->
                <div id="product-selector" class="mt-3 hidden">
                    <p class="text-xs text-slate-500 mb-2 font-medium">{{ __('community.select_product') }}</p>
                    <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto">
                        @forelse($products as $product)
                        <label class="cursor-pointer">
                            <input type="radio" name="product_id" value="{{ $product->id }}" class="hidden peer" required>
                            <div class="p-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 hover:border-emerald-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 transition-all">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-emerald-600 text-[18px]">inventory_2</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h6 class="font-bold text-slate-900 dark:text-white text-xs truncate">{{ $product->name }}</h6>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="text-slate-500">{{ $product->quantity }} {{ $product->unit }}</span>
                                    <span class="font-bold text-emerald-600">${{ $product->price }}</span>
                                </div>
                            </div>
                        </label>
                        @empty
                        <div class="col-span-2 text-center py-4">
                            <span class="material-symbols-outlined text-3xl text-slate-300">inventory_2</span>
                            <p class="text-xs text-slate-400 mt-1">{{ __('community.no_products_inventory') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </form>
        </div>

        <!-- Filter Tabs -->
        <div class="flex gap-3 overflow-x-auto no-scrollbar mb-4 pb-2">
            <a href="{{ route('community.index') }}" class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap border {{ $type == 'all' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 border-transparent' : 'bg-white dark:bg-slate-800 text-slate-500 border-slate-200 dark:border-slate-700' }}">
                {{ __('community.filter.all') }}
            </a>
            <a href="{{ route('community.index', ['type' => 'question']) }}" class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap border flex items-center gap-1 {{ $type == 'question' ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-white dark:bg-slate-800 text-slate-500 border-slate-200 dark:border-slate-700' }}">
                <span class="material-symbols-outlined text-[16px]">help</span>
                {{ __('community.filter.questions') }}
            </a>
            <a href="{{ route('community.index', ['type' => 'tip']) }}" class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap border flex items-center gap-1 {{ $type == 'tip' ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-white dark:bg-slate-800 text-slate-500 border-slate-200 dark:border-slate-700' }}">
                <span class="material-symbols-outlined text-[16px]">lightbulb</span>
                {{ __('community.filter.tips') }}
            </a>
        </div>

        <!-- Feed -->
        <div class="space-y-4">
            @forelse($posts as $post)
            <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl shadow-sm border {{ $post->type == 'marketplace' ? 'border-emerald-200 dark:border-emerald-800' : 'border-slate-100 dark:border-slate-700' }}">
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
                        {{ $post->type == 'question' ? 'bg-blue-50 text-blue-600' : ($post->type == 'tip' ? 'bg-amber-50 text-amber-600' : ($post->type == 'marketplace' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-500')) }}">
                        @if($post->type == 'question') {{ __('community.type.question') }}
                        @elseif($post->type == 'tip') {{ __('community.type.tip') }}
                        @elseif($post->type == 'marketplace') {{ __('community.type.marketplace') }}
                        @else {{ __('community.type.general') }}
                        @endif
                    </span>
                </div>
                
                @if($post->type == 'marketplace' && $post->product)
                <a href="{{ route('inventory.show', $post->product) }}" class="block mb-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border-2 border-emerald-100 dark:border-emerald-800 hover:border-emerald-300 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white dark:bg-slate-700 rounded-lg flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-emerald-600 text-2xl">inventory_2</span>
                        </div>
                        <div class="flex-1">
                            <h5 class="font-bold text-slate-900 dark:text-white text-sm">{{ $post->product->name }}</h5>
                            <p class="text-xs text-slate-500">{{ $post->product->quantity }} {{ $post->product->unit }}</p>
                        </div>
                        <div class="text-left">
                            <span class="block text-lg font-bold text-emerald-600">${{ $post->product->price }}</span>
                            <span class="text-[10px] text-slate-400">{{ __('community.per_unit') }}</span>
                        </div>
                    </div>
                    <div class="mt-2 pt-2 border-t border-emerald-200 dark:border-emerald-700 flex items-center justify-center gap-1 text-emerald-600">
                        <span class="material-symbols-outlined text-[16px]">storefront</span>
                        <span class="text-xs font-bold">{{ __('community.view_in_market') }}</span>
                    </div>
                </a>
                @endif
                
                <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed mb-4">
                    {{ $post->content }}
                </p>
                
                <div class="flex items-center justify-between pt-3 border-t {{ $post->type == 'marketplace' ? 'border-emerald-100 dark:border-emerald-800' : 'border-slate-100 dark:border-slate-700' }}">
                    <button class="flex items-center gap-1.5 text-slate-400 hover:text-red-500 transition-colors group" onclick="likePost({{ $post->id }}, this)">
                        <span class="material-symbols-outlined text-[20px] group-hover:fill-current">favorite</span>
                        <span class="text-xs font-bold like-count">{{ $post->likes_count }}</span>
                    </button>
                    
                    <a href="{{ route('community.show', $post) }}" class="flex items-center gap-1.5 text-slate-400 hover:text-blue-500 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">chat_bubble</span>
                        <span class="text-xs font-bold">{{ $post->comments_count }}</span>
                        <span class="text-[10px]">{{ __('community.comment') }}</span>
                    </a>
                    
                    <button class="flex items-center gap-1.5 text-slate-400 hover:text-green-500 transition-colors" onclick="sharePost('{{ route('community.show', $post) }}')">
                        <span class="material-symbols-outlined text-[20px]">share</span>
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-10">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-3xl text-slate-400">forum</span>
                </div>
                <h3 class="text-slate-900 dark:text-white font-bold">{{ __('community.no_posts') }}</h3>
                <p class="text-slate-500 text-xs mt-1">{{ __('community.no_posts_desc') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function updateType(radio) {
        // Reset all labels
        document.querySelectorAll('input[name="type"]').forEach(input => {
            const label = input.parentElement;
            if (input.value === 'question') label.className = 'cursor-pointer px-3 py-1.5 rounded-lg bg-slate-50 text-slate-500 text-xs font-bold hover:bg-blue-50 hover:text-blue-600 transition-colors';
            else if (input.value === 'tip') label.className = 'cursor-pointer px-3 py-1.5 rounded-lg bg-slate-50 text-slate-500 text-xs font-bold hover:bg-amber-50 hover:text-amber-600 transition-colors';
            else if (input.value === 'marketplace') label.className = 'cursor-pointer px-3 py-1.5 rounded-lg bg-slate-50 text-slate-500 text-xs font-bold hover:bg-emerald-50 hover:text-emerald-600 transition-colors';
            else label.className = 'cursor-pointer px-3 py-1.5 rounded-lg bg-slate-50 text-slate-500 text-xs font-bold hover:bg-green-50 hover:text-green-600 transition-colors';
        });

        // Set active styling
        const activeLabel = radio.parentElement;
        if (radio.value === 'question') activeLabel.className = 'cursor-pointer px-3 py-1.5 rounded-lg bg-blue-100 text-blue-600 text-xs font-bold transition-colors';
        else if (radio.value === 'tip') activeLabel.className = 'cursor-pointer px-3 py-1.5 rounded-lg bg-amber-100 text-amber-600 text-xs font-bold transition-colors';
        else if (radio.value === 'marketplace') activeLabel.className = 'cursor-pointer px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-600 text-xs font-bold transition-colors';
        else activeLabel.className = 'cursor-pointer px-3 py-1.5 rounded-lg bg-green-100 text-green-600 text-xs font-bold transition-colors';
        
        // Show/hide product selector
        const productSelector = document.getElementById('product-selector');
        if (radio.value === 'marketplace') {
            productSelector.classList.remove('hidden');
        } else {
            productSelector.classList.add('hidden');
        }
    }

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

    function sharePost(url) {
        if (navigator.share) {
            navigator.share({
                title: '{{ __('community.share_title') }}',
                text: '{{ __('community.share_text') }}',
                url: url
            }).catch(err => console.log('Error sharing:', err));
        } else {
            // Fallback: Copy to clipboard
            navigator.clipboard.writeText(url).then(() => {
                alert('{{ __('community.link_copied') }}');
            });
        }
    }
</script>
@endsection
