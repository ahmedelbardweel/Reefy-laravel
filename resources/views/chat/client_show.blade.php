<!DOCTYPE html>
<html class="light" dir="rtl" lang="ar">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>محادثات العميل والتفاوض</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;family=Noto+Sans+Arabic:wght@100..900&amp;display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Theme Configuration -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec13",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102210",
                        "surface-light": "#ffffff",
                        "surface-dark": "#162b16",
                        "text-main": "#111811",
                        "text-secondary": "#618961",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "Noto Sans Arabic", "sans-serif"],
                        "body": ["Noto Sans Arabic", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "1rem",
                        "xl": "1.5rem",
                        "2xl": "2rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #334b33;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-text-main dark:text-white font-display h-screen flex overflow-hidden">
    <!-- 1. Navigation Rail (Global App Nav) -->
    <aside class="w-20 hidden md:flex flex-col items-center py-6 bg-surface-light dark:bg-surface-dark border-l border-[#dbe6db] dark:border-[#1f3f1f] z-20 shrink-0">
        <div class="mb-8">
            <div class="bg-primary/20 p-2 rounded-xl">
                <span class="material-symbols-outlined text-primary text-3xl">agriculture</span>
            </div>
        </div>
        <nav class="flex-1 flex flex-col gap-6 w-full px-2">
            <a class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-background-light dark:hover:bg-[#1f3f1f] group transition-colors" href="{{ route('client.dashboard') }}">
                <span class="material-symbols-outlined text-text-secondary dark:text-gray-400 group-hover:text-primary transition-colors">house</span>
                <span class="text-[10px] font-medium text-text-secondary dark:text-gray-400">الرئيسية</span>
            </a>
            <a class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-background-light dark:hover:bg-[#1f3f1f] group transition-colors" href="{{ route('market.index') }}">
                <span class="material-symbols-outlined text-text-secondary dark:text-gray-400 group-hover:text-primary transition-colors">storefront</span>
                <span class="text-[10px] font-medium text-text-secondary dark:text-gray-400">المتجر</span>
            </a>
            <a class="flex flex-col items-center gap-1 p-2 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary" href="{{ route('chat.index') }}">
                <span class="material-symbols-outlined text-primary font-variation-settings-'FILL' 1">chat</span>
                <span class="text-[10px] font-medium">المحادثات</span>
            </a>
            <a class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-background-light dark:hover:bg-[#1f3f1f] group transition-colors" href="#">
                <span class="material-symbols-outlined text-text-secondary dark:text-gray-400 group-hover:text-primary transition-colors">person</span>
                <span class="text-[10px] font-medium text-text-secondary dark:text-gray-400">حسابي</span>
            </a>
        </nav>
        <div class="mt-auto">
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 ring-2 ring-white dark:ring-[#162b16]" data-alt="User profile picture placeholder" style='background-image: url("https://placehold.co/100");'></div>
        </div>
    </aside>

    <!-- 2. Chat List Sidebar -->
    <div class="w-full md:w-96 flex flex-col bg-surface-light dark:bg-surface-dark border-l border-[#dbe6db] dark:border-[#1f3f1f] z-10 shrink-0 {{ isset($conversation) ? 'hidden md:flex' : 'flex' }}">
        <!-- Header -->
        <div class="p-5 pb-2">
            <h1 class="text-2xl font-bold mb-1">الرسائل</h1>
            <p class="text-text-secondary text-sm dark:text-gray-400">إدارة مفاوضاتك وتواصلك</p>
        </div>
        <!-- Tabs -->
        <div class="px-5 mt-2">
            <div class="flex border-b border-[#dbe6db] dark:border-[#2a4a2a] gap-6">
                <button class="flex-1 flex flex-col items-center justify-center border-b-[3px] border-b-primary text-text-main dark:text-white pb-3 pt-2">
                    <p class="text-sm font-bold tracking-wide">المحادثات</p>
                </button>
            </div>
        </div>
        <!-- Conversation List -->
        <div class="flex-1 overflow-y-auto py-2">
            @php
                $user = auth()->user();
                $allConversations = \App\Models\Conversation::where(function ($q) use ($user) {
                    $q->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
                })
                ->with(['sender', 'receiver', 'product', 'messages'])
                ->latest()
                ->get();
            @endphp

            @foreach($allConversations as $conv)
                @php
                    $otherUser = $conv->sender_id == auth()->id() ? $conv->receiver : $conv->sender;
                    // Skip if user was deleted
                    if (!$otherUser) continue;
                    
                    $isActive = isset($conversation) && $conversation->id == $conv->id;
                    $lastMessage = $conv->messages->last();
                @endphp
                <a href="{{ route('chat.show', $conv->id) }}" class="flex items-center gap-3 px-5 py-4 cursor-pointer transition-colors {{ $isActive ? 'bg-background-light dark:bg-[#1f3f1f] border-r-4 border-primary' : 'hover:bg-gray-50 dark:hover:bg-[#1a331a] border-r-4 border-transparent' }}">
                    <div class="relative shrink-0">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-12 w-12" style='background-image: url("https://placehold.co/100?text={{ strtoupper(substr($otherUser->name, 0, 2)) }}");'></div>
                        @if($isActive)
                            <span class="absolute bottom-0 left-0 size-3 bg-primary rounded-full border-2 border-white dark:border-[#1f3f1f]"></span>
                        @endif
                    </div>
                    <div class="flex flex-col flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <p class="text-text-main dark:text-white text-base font-bold truncate">{{ $otherUser->name }}</p>
                            <span class="text-xs {{ $isActive ? 'text-primary' : 'text-text-secondary dark:text-gray-500' }} font-medium shrink-0">{{ $lastMessage ? $lastMessage->created_at->format('h:i A') : '' }}</span>
                        </div>
                        <p class="text-text-secondary dark:text-gray-300 text-sm truncate">
                            @if($conv->product)
                                <span class="text-xs bg-green-100 text-green-800 px-1 rounded ml-1">{{ $conv->product->name }}</span>
                            @endif
                            {{ $lastMessage ? \Illuminate\Support\Str::limit($lastMessage->content, 30) : '...' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- 3. Main Chat Area -->
    @if(isset($conversation))
    <main class="flex-1 flex flex-col bg-background-light dark:bg-background-dark relative min-w-0 w-full h-full">
        @php
            $otherUser = $conversation->sender_id == auth()->id() ? $conversation->receiver : $conversation->sender;
        @endphp
        
        <!-- Chat Header -->
        <div class="h-[72px] px-6 flex items-center justify-between bg-surface-light dark:bg-surface-dark border-b border-[#dbe6db] dark:border-[#1f3f1f] shrink-0 z-30">
            <div class="flex items-center gap-4">
                <!-- Back Button for Mobile -->
                <a href="{{ route('chat.index') }}" class="md:hidden text-text-secondary">
                    <span class="material-symbols-outlined rtl:rotate-180">arrow_forward</span>
                </a>
                
                <div class="relative">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-10 w-10" style='background-image: url("https://placehold.co/100?text={{ strtoupper(substr($otherUser->name, 0, 2)) }}");'></div>
                    <span class="absolute bottom-0 left-0 size-2.5 bg-primary rounded-full ring-2 ring-white dark:ring-[#162b16]"></span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-text-main dark:text-white leading-tight">{{ $otherUser->name }}</h2>
                    <div class="flex items-center gap-1">
                        @if($conversation->product)
                            <span class="text-xs text-primary font-bold">{{ $conversation->product->name }}</span>
                        @else
                            <span class="text-xs text-text-secondary dark:text-gray-400">متصل الآن</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($otherUser->phone)
                <a href="tel:{{ $otherUser->phone }}" class="p-2 text-text-secondary hover:text-primary hover:bg-background-light dark:hover:bg-[#1f3f1f] rounded-full transition-colors">
                    <span class="material-symbols-outlined">call</span>
                </a>
                @endif
            </div>
        </div>

        <!-- Negotiation Bar (Sticky under header) -->
        @if($conversation->negotiation && $conversation->product)
            <div class="px-6 py-3 bg-white dark:bg-[#1a331a] border-b border-[#dbe6db] dark:border-[#2a4a2a] flex flex-wrap items-center justify-between gap-4 shrink-0 transition-all" id="negotiation-bar">
                 <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">handshake</span>
                    <span class="text-sm font-bold text-text-main dark:text-white">تفاوض:</span>
                 </div>
                 
                 <div class="flex gap-4 flex-1 justify-center">
                    <div class="flex items-center gap-2 bg-background-light dark:bg-[#102210] px-3 py-1 rounded-lg">
                        <label class="text-xs text-text-secondary">الكمية:</label>
                        <input type="number" id="neg-qty" value="{{ $conversation->negotiation->quantity }}" 
                            class="w-16 bg-transparent border-none p-0 text-sm font-bold text-center focus:ring-0" 
                            {{ (auth()->id() == optional($conversation->product)->user_id) ? '' : 'readonly' }} onchange="updateNegotiation()">
                    </div>
                    <div class="flex items-center gap-2 bg-background-light dark:bg-[#102210] px-3 py-1 rounded-lg">
                        <label class="text-xs text-text-secondary">السعر:</label>
                        <input type="number" id="neg-price" value="{{ $conversation->negotiation->price }}" 
                            class="w-20 bg-transparent border-none p-0 text-sm font-bold text-center focus:ring-0" 
                            {{ (auth()->id() == optional($conversation->product)->user_id) ? '' : 'readonly' }} onchange="updateNegotiation()">
                        <span class="text-xs font-bold text-primary">SAR</span>
                    </div>
                 </div>

                 <div class="flex items-center gap-3">
                     <span class="text-sm font-bold text-text-main dark:text-white" id="neg-total">{{ $conversation->negotiation->price * $conversation->negotiation->quantity }} SAR total</span>
                     
                     @if(auth()->id() == optional($conversation->product)->user_id)
                        <form action="{{ route('negotiation.confirm', $conversation->negotiation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-primary hover:bg-[#0fd60f] text-black text-xs font-bold px-3 py-1.5 rounded-lg transition-colors">
                                تثبيت
                            </button>
                        </form>
                     @endif
                     
                     <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $conversation->negotiation->status == 'accepted' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $conversation->negotiation->status == 'accepted' ? 'تم الاتفاق' : 'جاري التفاوض' }}
                    </span>
                 </div>
            </div>
        @endif

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/50 dark:bg-transparent scroll-smooth" id="messages-container" style="background-image: radial-gradient(#13ec1310 1px, transparent 1px); background-size: 24px 24px;">
             @foreach($conversation->messages as $message)
                @php $isMine = $message->user_id == auth()->id(); @endphp
                <div id="msg-{{ $message->id }}" class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-3 max-w-[85%] {{ $isMine ? 'mr-auto' : '' }}">
                    @if(!$isMine)
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-8 w-8 mb-1 shrink-0" style='background-image: url("https://placehold.co/100?text={{ strtoupper(substr($message->user->name, 0, 2)) }}");'></div>
                    @endif
                    
                    <div class="flex flex-col gap-1 {{ $isMine ? 'items-end' : 'items-start' }}">
                        <div class="{{ $isMine ? 'bg-primary/20 dark:bg-primary/30 rounded-bl-none' : 'bg-white dark:bg-[#1f3f1f] rounded-br-none border border-gray-100 dark:border-[#2a4a2a]' }} p-4 rounded-2xl shadow-sm">
                            <p class="text-sm text-text-main dark:text-white leading-relaxed font-body">
                                {{ $message->content }}
                            </p>
                        </div>
                        <span class="text-[10px] text-text-secondary dark:text-gray-500 {{ $isMine ? 'ml-2' : 'mr-2' }}">{{ $message->created_at->format('h:i A') }}</span>
                    </div>
                </div>
             @endforeach
        </div>

        <!-- Chat Input Area -->
        <div class="p-5 bg-surface-light dark:bg-surface-dark border-t border-[#dbe6db] dark:border-[#1f3f1f] shrink-0 z-30">
            <form action="{{ route('chat.send', $conversation->id) }}" method="POST" id="msg-form">
                @csrf
                <div class="relative flex items-end gap-2 bg-background-light dark:bg-[#162b16] p-2 rounded-2xl border border-transparent focus-within:border-primary/50 transition-colors">
                    <div class="flex items-center gap-1 pb-1 pr-1">
                        <button type="button" class="p-2 text-text-secondary hover:text-primary hover:bg-white dark:hover:bg-[#1f3f1f] rounded-xl transition-colors">
                            <span class="material-symbols-outlined">add_photo_alternate</span>
                        </button>
                    </div>
                    <textarea name="content" class="w-full bg-transparent border-none text-text-main dark:text-white placeholder-gray-400 focus:ring-0 resize-none py-3 max-h-32 text-sm leading-relaxed" placeholder="اكتب رسالتك..." rows="1" required></textarea>
                    <div class="pb-1 pl-1">
                        <button type="submit" class="p-3 bg-primary hover:bg-[#0fd60f] text-black rounded-xl shadow-sm transition-transform hover:scale-105 active:scale-95 flex items-center justify-center">
                            <span class="material-symbols-outlined -ml-1 rotate-180">send</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
    @else
    <!-- Empty State -->
    <main class="flex-1 flex flex-col items-center justify-center bg-background-light dark:bg-background-dark text-center px-4">
        <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-5xl text-primary">chat_bubble_outline</span>
        </div>
        <h2 class="text-2xl font-bold text-text-main dark:text-white mb-2">اختر محادثة للبدء</h2>
        <p class="text-text-secondary dark:text-gray-400 max-w-sm">تواصل مع المزارعين، فاوض على الأسعار، واتفق على الصفقات بسهولة.</p>
    </main>
    @endif

    <script>
        // Basic Scroll to bottom
        const msgContainer = document.getElementById('messages-container');
        if(msgContainer) msgContainer.scrollTop = msgContainer.scrollHeight;

        // Negotiation Update Logic (Copied from previous view)
        function updateNegotiation() {
            @if(isset($conversation) && $conversation->negotiation && $conversation->product && auth()->id() == $conversation->product->user_id)
            const price = document.getElementById('neg-price').value;
            const qty = document.getElementById('neg-qty').value;
            const totalEl = document.getElementById('neg-total');
            
            totalEl.textContent = (price * qty) + ' SAR total';

            fetch("{{ route('negotiation.update', $conversation->negotiation->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    price: price,
                    quantity: qty
                })
            })
            .then(r => r.json())
            .then(d => console.log('Updated', d));
            @endif
        }
        
        // Simple polling (Refresh page logic or AJAX) - Keeping simple for now, using existing JS logic would be better but keeping file small.
        // Re-injecting the full polling script from the previous view is recommended for real-time.
    </script>
</body>
</html>
