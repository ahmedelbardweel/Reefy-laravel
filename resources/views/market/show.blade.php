@extends('layouts.market')

@section('content')
<!-- TopNavBar (Assuming already in layout, but user provided specific header in HTML. 
     If the layout has a header, we might suppress this one or use the layout's. 
     The provided HTML has a "Market" specific header. I'll stick to the layout's header 
     to maintain consistency across the app, or I can override it if the user wants this EXACT page.
     Given the "App Structure", I'll use the main content area of the provided HTML inside the layout.) 
-->

@section('content')
<!-- Breadcrumbs -->
<nav class="flex flex-wrap gap-2 pb-6 text-sm">
    <a class="text-[#618961] hover:text-primary transition-colors font-medium" href="{{ route('dashboard') }}">{{ __('market.nav.home') }}</a>
    <span class="text-[#618961] font-medium">/</span>
    <a class="text-[#618961] hover:text-primary transition-colors font-medium" href="{{ route('inventory.index', ['mode' => 'market']) }}">{{ __('market.title') }}</a>
    <span class="text-[#618961] font-medium">/</span>
    <span class="text-[#111811] dark:text-white font-medium">{{ $product->name }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
    <!-- Right Column: Images -->
    <div class="lg:col-span-7 flex flex-col gap-4">
                <!-- Main Image -->
                <div class="w-full aspect-[4/3] bg-gray-100 dark:bg-surface-dark rounded-xl overflow-hidden relative group shadow-sm">
                    <div class="absolute top-4 end-4 z-10 flex gap-2">
                        <span class="bg-primary text-[#102210] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ __('inventory.category.' . $product->category) }}</span>
                        @if($product->created_at->diffInDays() < 7)
                        <span class="bg-white/90 text-[#102210] text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px] text-orange-500 icon-filled">local_fire_department</span>
                            {{ __('inventory.new_arrival') }}
                        </span>
                        @endif
                    </div>
                    <div class="w-full h-full bg-center bg-cover hover:scale-105 transition-transform duration-500" 
                         style='background-image: url("{{ $product->image_url ?? "https://placehold.co/800x600?text=".urlencode($product->name) }}");'>
                    </div>
                </div>
                <!-- Thumbnails (Static for now as we only have one image usually, but keeping layout) -->
                <div class="grid grid-cols-4 gap-4">
                    <button class="aspect-square rounded-lg overflow-hidden border-2 border-primary cursor-pointer">
                         <div class="w-full h-full bg-center bg-cover" style='background-image: url("{{ $product->image_url ?? "https://placehold.co/200x200" }}");'></div>
                    </button>
                    <!-- Placeholders for "more images" simulation -->
                    <button class="aspect-square rounded-lg overflow-hidden border-2 border-transparent hover:border-primary/50 cursor-pointer">
                        <div class="w-full h-full bg-center bg-cover grayscale opacity-50" style='background-image: url("{{ $product->image_url ?? "https://placehold.co/200x200" }}");'></div>
                    </button>
                </div>
            </div>

            <!-- Left Column: Product Details & Actions -->
            <div class="lg:col-span-5 flex flex-col gap-6">
                <!-- Title & Price -->
                <div class="flex flex-col gap-2 border-b border-border-light dark:border-border-dark pb-6">
                    <h1 class="text-3xl lg:text-4xl font-display font-black text-text-main dark:text-white leading-tight">{{ $product->name }}</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-text-secondary-light text-sm">SKU: #{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</span>
                        <span class="w-1 h-1 bg-text-secondary-light rounded-full"></span>
                        <span class="text-text-secondary-light text-sm">{{ __('inventory.category_label') }}: {{ __('inventory.category.' . $product->category) }}</span>
                    </div>
                    <div class="flex items-baseline gap-3 mt-4">
                        <span class="text-4xl font-bold text-primary font-display">{{ $product->price }} {{ __('currency.sar') }}</span>
                        <!-- Mock discount for visual match -->
                        <!-- <span class="text-lg text-gray-400 line-through font-normal">45 ر.س</span> -->
                        <!-- <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded">-22%</span> -->
                    </div>
                    <p class="text-sm text-text-secondary-light">{{ __('market.price_note') }}</p>
                </div>

                <!-- Trust Chips -->
                <div class="flex flex-wrap gap-3">
                    <div class="flex items-center gap-2 bg-green-50 dark:bg-green-900/20 px-3 py-2 rounded-lg border border-green-100 dark:border-green-800">
                        <span class="material-symbols-outlined text-green-700 dark:text-primary text-[20px]">calendar_today</span>
                        <span class="text-sm font-medium text-green-900 dark:text-gray-200">{{ __('market.trust_harvest') }}</span>
                    </div>
                    <div class="flex items-center gap-2 bg-green-50 dark:bg-green-900/20 px-3 py-2 rounded-lg border border-green-100 dark:border-green-800">
                        <span class="material-symbols-outlined text-green-700 dark:text-primary text-[20px]">eco</span>
                        <span class="text-sm font-medium text-green-900 dark:text-gray-200">{{ __('market.trust_organic') }}</span>
                    </div>
                    <div class="flex items-center gap-2 bg-green-50 dark:bg-green-900/20 px-3 py-2 rounded-lg border border-green-100 dark:border-green-800">
                        <span class="material-symbols-outlined text-green-700 dark:text-primary text-[20px]">verified</span>
                        <span class="text-sm font-medium text-green-900 dark:text-gray-200">{{ __('market.trust_verified') }}</span>
                    </div>
                </div>

                <!-- Description Snippet -->
                <p class="text-text-secondary-light dark:text-gray-300 leading-relaxed">
                    {{ $product->description ?? __('inventory.no_description') }}
                </p>

                <!-- Quantity & Stock -->
                <div class="flex flex-col gap-3">
                    <div class="flex justify-between items-center text-sm font-medium">
                        <label class="text-text-main dark:text-white">{{ __('market.quantity_label') }}</label>
                        <span class="text-green-600 dark:text-primary">{{ __('market.available') }}: {{ $product->stock_quantity }}</span>
                    </div>
                    
                    @if(auth()->user()->role === 'client')
                    <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center border border-border-light dark:border-border-dark rounded-lg bg-white dark:bg-surface-dark h-12 w-32">
                                <button type="button" onclick="decrementQty()" class="w-10 h-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-background-dark text-xl font-bold rounded-e-lg text-text-main dark:text-white">-</button>
                                <input id="qtyInput" name="quantity" class="w-full text-center border-none focus:ring-0 bg-transparent font-bold p-0 text-text-main dark:text-white" type="number" value="1" min="1" max="{{ $product->stock_quantity }}"/>
                                <button type="button" onclick="incrementQty()" class="w-10 h-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-background-dark text-xl font-bold rounded-s-lg text-text-main dark:text-white">+</button>
                            </div>
                            <div class="text-sm text-text-secondary-light">
                                {{ __('market.wholesale_note') }}
                            </div>
                        </div>
                    </form>
                    @endif
                </div>

                <!-- Farmer Card -->
                <div class="bg-white dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl p-4 shadow-sm flex items-center justify-between gap-4 mt-2">
                    <div class="flex items-center gap-3">
                        <div class="size-14 rounded-full bg-gray-200 bg-cover bg-center border-2 border-white dark:border-border-dark shadow-sm" 
                             style='background-image: url("{{ $product->user->avatar ?? "https://ui-avatars.com/api/?name=".urlencode($product->user->name ?? "System") }}");'>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="font-bold text-text-main dark:text-white flex items-center gap-1">
                                {{ $product->user->name ?? __('market.system_admin') }}
                                <span class="material-symbols-outlined text-blue-500 text-[18px] icon-filled">verified</span>
                            </h4>
                            <div class="flex items-center gap-3 text-sm text-text-secondary-light dark:text-gray-400">
                                <span class="flex items-center gap-0.5 text-yellow-500 font-bold">
                                    <span class="material-symbols-outlined text-[16px] icon-filled">star</span>
                                    4.8
                                </span>
                                <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">location_on</span>
                                    {{ $product->user->address ?? 'Saudi Arabia' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->role === 'client' && $product->user_id)
                    <a href="{{ route('chat.start', $product->id) }}" class="flex items-center justify-center size-10 rounded-full bg-gray-100 dark:bg-background-dark hover:bg-primary/20 text-text-main dark:text-white transition-colors" title="Chat">
                        <span class="material-symbols-outlined">chat</span>
                    </a>
                    @endif
                </div>

                <!-- Action Buttons -->
                @if(auth()->user()->role === 'client')
                <div class="flex flex-col gap-3 mt-2">
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Direct actions utilizing the hidden form via JS or strictly form submission -->
                        <button onclick="document.getElementById('addToCartForm').submit()" class="col-span-1 bg-transparent border-2 border-gray-200 dark:border-border-dark hover:border-primary text-text-main dark:text-white hover:text-primary font-bold h-12 rounded-lg text-base flex items-center justify-center gap-2 transition-all">
                            <span class="material-symbols-outlined">add_shopping_cart</span>
                            {{ __('market.btn.add_to_cart') }}
                        </button>
                        <button onclick="document.getElementById('addToCartForm').action='{{ route('cart.checkout') }}'; document.getElementById('addToCartForm').submit()" class="col-span-1 bg-primary hover:bg-primary-hover text-[#102210] font-bold h-12 rounded-lg text-base flex items-center justify-center gap-2 transition-all shadow-[0_4px_14px_0_rgba(19,236,19,0.39)]">
                            <span class="material-symbols-outlined">shopping_bag</span>
                            {{ __('market.btn.buy_now') }}
                        </button>
                    </div>
                    @if($product->user_id)
                    <a href="{{ route('chat.start', $product->id) }}" class="w-full bg-background-light dark:bg-surface-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-text-main dark:text-white font-semibold h-12 rounded-lg text-base flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined">handshake</span>
                        {{ __('market.btn.negotiate') }}
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Info Tabs Section -->
        <div class="mt-16 border-t border-border-light dark:border-border-dark pt-10">
            <div x-data="{ tab: 'desc' }" class="mb-8">
                <div class="flex flex-wrap gap-8 border-b border-border-light dark:border-border-dark mb-8">
                    <button @click="tab = 'desc'" :class="{ 'text-primary border-primary': tab === 'desc', 'text-text-secondary-light border-transparent': tab !== 'desc' }" class="pb-4 border-b-2 font-bold text-lg px-2 transition-colors">{{ __('market.tab.description') }}</button>
                    <button @click="tab = 'specs'" :class="{ 'text-primary border-primary': tab === 'specs', 'text-text-secondary-light border-transparent': tab !== 'specs' }" class="pb-4 border-b-2 font-bold text-lg px-2 transition-colors">{{ __('market.tab.specs') }}</button>
                    <button @click="tab = 'reviews'" :class="{ 'text-primary border-primary': tab === 'reviews', 'text-text-secondary-light border-transparent': tab !== 'reviews' }" class="pb-4 border-b-2 font-bold text-lg px-2 transition-colors">{{ __('market.tab.reviews') }} ({{ $product->reviews->count() }})</button>
                </div>

                <div x-show="tab === 'desc'" class="grid grid-cols-1 md:grid-cols-3 gap-10 text-gray-700 dark:text-gray-300">
                    <div class="md:col-span-2 space-y-4">
                        <h3 class="text-xl font-bold text-text-main dark:text-white">{{ __('market.about_product') }}</h3>
                        <p class="leading-relaxed">
                            {{ $product->description ?? 'No detailed description available.' }}
                            <br><br>
                            {{ __('market.generic_desc_footer') }}
                        </p>
                    </div>
                    <div class="bg-background-light dark:bg-surface-dark p-6 rounded-xl h-fit">
                        <h3 class="text-lg font-bold text-text-main dark:text-white mb-4">{{ __('market.quick_details') }}</h3>
                        <ul class="space-y-4">
                            <li class="flex justify-between items-center border-b border-border-light dark:border-border-dark pb-2">
                                <span class="text-text-secondary-light">{{ __('market.origin') }}</span>
                                <span class="font-medium text-text-main dark:text-white">{{ $product->user->address ?? 'Saudi Arabia' }}</span>
                            </li>
                             <li class="flex justify-between items-center border-b border-border-light dark:border-border-dark pb-2">
                                <span class="text-text-secondary-light">{{ __('market.stock') }}</span>
                                <span class="font-medium text-text-main dark:text-white">{{ $product->stock_quantity }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                 <div x-show="tab === 'specs'" style="display: none;">
                    <p class="text-text-secondary-light">{{ __('market.specs_placeholder') }}</p>
                </div>

                <div x-show="tab === 'reviews'" style="display: none;" class="space-y-6">
                    <!-- Reviews List -->
                    @forelse($product->reviews as $review)
                    <div class="border-b border-border-light dark:border-border-dark pb-6">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-3">
                                <div class="size-10 rounded-full bg-gray-200 bg-cover bg-center" style='background-image: url("{{ $review->user->avatar ?? "https://ui-avatars.com/api/?name=".urlencode($review->user->name) }}");'></div>
                                <div>
                                    <h4 class="font-bold text-text-main dark:text-white">{{ $review->user->name }}</h4>
                                    <span class="text-xs text-text-secondary-light">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex text-yellow-500 text-sm">
                                @for($i=1; $i<=5; $i++)
                                <span class="material-symbols-outlined icon-filled text-[18px] {{ $i <= $review->rating ? '' : 'text-gray-300' }}">star</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300">{{ $review->comment }}</p>
                    </div>
                    @empty
                    <p class="text-text-secondary-light">{{ __('inventory.no_reviews') }}</p>
                    @endforelse
                    
                    @if(auth()->user()->role === 'client')
                    <!-- Add Review Form -->
                    <div class="mt-8 bg-background-light dark:bg-surface-dark p-6 rounded-xl">
                        <h4 class="font-bold text-lg mb-4 dark:text-white">{{ __('inventory.add_review') }}</h4>
                        <form action="{{ route('product.review', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="rating" id="ratingInput" value="5">
                            <div class="flex gap-2 mb-4">
                                @for($i=1; $i<=5; $i++)
                                <button type="button" onclick="document.getElementById('ratingInput').value = {{ $i }}" class="focus:outline-none text-yellow-500 hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-2xl">star</span>
                                </button>
                                @endfor
                            </div>
                            <textarea name="comment" class="w-full bg-white dark:bg-black/20 border border-border-light dark:border-border-dark rounded-lg p-3 mb-4" rows="3" placeholder="{{ __('inventory.write_review_placeholder') }}"></textarea>
                            <button type="submit" class="bg-primary text-[#102210] font-bold py-2 px-6 rounded-lg">{{ __('inventory.submit') }}</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
         @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h3 class="text-2xl font-bold text-text-main dark:text-white mb-8 font-display">{{ __('inventory.related_products') }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                <a href="{{ route('market.show', $related->id) }}" class="group">
                    <div class="aspect-square bg-gray-100 dark:bg-surface-dark rounded-xl overflow-hidden mb-3 relative">
                        <div class="w-full h-full bg-center bg-cover group-hover:scale-110 transition-transform duration-500" style='background-image: url("{{ $related->image_url ?? "https://placehold.co/400x400" }}");'></div>
                    </div>
                    <h4 class="font-bold text-text-main dark:text-white group-hover:text-primary transition-colors">{{ $related->name }}</h4>
                    <p class="text-primary font-bold">{{ $related->price }} {{ __('currency.sar') }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    function incrementQty() {
        const input = document.getElementById('qtyInput');
        if(parseInt(input.value) < parseInt(input.max)) {
            input.value = parseInt(input.value) + 1;
        }
    }
    
    function decrementQty() {
        const input = document.getElementById('qtyInput');
        if(parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
<!-- AlpineJS for Tabs if not already loaded in layout -->
<script src="//unpkg.com/alpinejs" defer></script>
@endsection

