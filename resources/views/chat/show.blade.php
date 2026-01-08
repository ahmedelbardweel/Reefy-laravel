@extends('layouts.app')

@section('content')
<div class="flex flex-col fixed inset-0 z-0 bg-slate-50 dark:bg-slate-950"> 
    <!-- Main Chat Wrapper - Uses absolute/fixed to lock the height -->
    <div class="flex flex-col h-full w-full max-w-md mx-auto relative bg-slate-50 dark:bg-slate-900 border-x border-slate-100 dark:border-slate-800">
        
        <!-- Chat Header Area (Stationary) -->
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md shadow-sm border-b border-slate-100 dark:border-slate-700 p-4 shrink-0 z-20">
        <div class="flex items-center gap-3">
            <a href="{{ route('chat.index') }}" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                <span class="material-symbols-outlined rtl:rotate-180">arrow_forward</span>
            </a>
            
            @php
                $otherUser = $conversation->sender_id == auth()->id() ? $conversation->receiver : $conversation->sender;
            @endphp

            <div class="flex-1">
                <h1 class="font-bold text-slate-900 dark:text-white text-sm">{{ $otherUser->name }}</h1>
                @if($conversation->product)
                    <div class="flex items-center gap-1 text-xs text-slate-500">
                        <span class="material-symbols-outlined text-[14px]">inventory_2</span>
                        <span>تفاوض على: <strong>{{ $conversation->product->name }}</strong></span>
                    </div>
                @endif
            </div>

            <!-- Call Actions (If configured) -->
            @if($otherUser->phone)
                <a href="tel:{{ $otherUser->phone }}" class="w-9 h-9 rounded-full bg-green-50 dark:bg-green-900/20 text-green-600 flex items-center justify-center hover:bg-green-100 transition-colors" title="اتصال">
                    <span class="material-symbols-outlined text-lg">call</span>
                </a>
            @endif
             @if($otherUser->facebook || $otherUser->instagram || $otherUser->twitter)
                <div class="relative group">
                    <button class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors">
                        <span class="material-symbols-outlined text-lg">public</span>
                    </button>
                    <!-- Dropdown for social links -->
                    <div class="absolute top-full left-0 mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 p-2 hidden group-hover:block min-w-[150px]">
                        @if($otherUser->facebook)
                            <a href="{{ $otherUser->facebook }}" target="_blank" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg text-xs text-slate-700 dark:text-slate-300">
                                <span class="material-symbols-outlined text-blue-600">facebook</span> فيسبوك
                            </a>
                        @endif
                         @if($otherUser->twitter)
                            <a href="{{ $otherUser->twitter }}" target="_blank" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg text-xs text-slate-700 dark:text-slate-300">
                                <span class="material-symbols-outlined text-sky-500">flutter</span> تويتر
                            </a>
                        @endif
                         @if($otherUser->instagram)
                            <a href="{{ $otherUser->instagram }}" target="_blank" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg text-xs text-slate-700 dark:text-slate-300">
                                <span class="material-symbols-outlined text-pink-500">photo_camera</span> انستجرام
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Negotiation Card -->
        @if($conversation->negotiation)
            <div class="mt-3 bg-slate-50 dark:bg-slate-900 rounded-xl p-3 border border-slate-100 dark:border-slate-700" id="negotiation-card">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500">تفاصيل الاتفاق</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $conversation->negotiation->status == 'accepted' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $conversation->negotiation->status == 'accepted' ? 'تم الاتفاق' : 'قيد التفاوض' }}
                    </span>
                </div>
                
                @php
                    $isFarmer = auth()->id() == $conversation->negotiation->product->user_id;
                @endphp

                <div class="flex gap-2">
                    <!-- Price Input -->
                    <div class="flex-1">
                        <label class="block text-[10px] text-slate-400 mb-1">السعر (للكيلو)</label>
                        <div class="relative">
                            <input type="number" id="neg-price" value="{{ $conversation->negotiation->price }}" 
                                class="w-full bg-white dark:bg-slate-800 border {{ $isFarmer ? 'border-primary' : 'border-slate-200 dark:border-slate-600' }} rounded-lg px-2 py-1.5 text-sm font-bold text-center"
                                {{ $isFarmer ? '' : 'readonly' }} onchange="updateNegotiation()">
                            <span class="absolute left-2 top-1.5 text-xs text-slate-400">₪</span>
                        </div>
                    </div>
                    
                    <!-- Quantity Input -->
                    <div class="flex-1">
                        <label class="block text-[10px] text-slate-400 mb-1">الكمية (كجم)</label>
                         <div class="relative">
                            <input type="number" id="neg-qty" value="{{ $conversation->negotiation->quantity }}" 
                                class="w-full bg-white dark:bg-slate-800 border {{ $isFarmer ? 'border-primary' : 'border-slate-200 dark:border-slate-600' }} rounded-lg px-2 py-1.5 text-sm font-bold text-center"
                                {{ $isFarmer ? '' : 'readonly' }} onchange="updateNegotiation()">
                        </div>
                    </div>
                </div>

                <!-- Total & Action -->
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                    <div class="text-xs">
                        <span class="text-slate-400">الإجمالي:</span>
                        <strong class="text-slate-900 dark:text-white text-sm" id="neg-total">{{ $conversation->negotiation->price * $conversation->negotiation->quantity }}</strong>
                        <span class="text-[10px]">₪</span>
                    </div>

                    @if($isFarmer)
                        <form action="{{ route('negotiation.confirm', $conversation->negotiation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-primary text-slate-900 px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-primary/90 transition-colors flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">check</span>
                                تثبيت الاتفاق
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Messages Area -->
    <div class="flex-1 overflow-y-auto p-5 space-y-4" id="messages-container">
        @foreach($conversation->messages as $message)
            <div id="msg-{{ $message->id }}" class="flex {{ $message->user_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] {{ $message->user_id == auth()->id() ? 'bg-primary text-slate-900 rounded-tl-none' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-tr-none border border-slate-100 dark:border-slate-700' }} px-4 py-3 rounded-2xl shadow-sm text-sm leading-relaxed">
                    {{ $message->content }}
                    <div class="text-[10px] mt-1 opacity-70 {{ $message->user_id == auth()->id() ? 'text-slate-800' : 'text-slate-400' }} text-left">
                        {{ $message->created_at->format('h:i A') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

        <!-- Input Area (Stationary) -->
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md p-3 border-t border-slate-100 dark:border-slate-700 shrink-0 pb-[76px]">
        <form action="{{ route('chat.send', $conversation->id) }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="content" required placeholder="اكتب رسالتك للمفاوضة..." 
                class="flex-1 bg-slate-100 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary text-slate-900 dark:text-white">
            <button type="submit" class="w-12 h-11 bg-primary text-slate-900 rounded-xl flex items-center justify-center hover:bg-primary/90 transition-all shadow-md shadow-primary/20">
                <span class="material-symbols-outlined rtl:rotate-180">send</span>
            </button>
        </form>
        </div>
    </div>
</div>

<script>
    // Configuration
    const conversationId = {{ $conversation->id }};
    const currentUserId = {{ auth()->id() }};
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.querySelector('form[action*="send"]');
    const messageInput = messageForm.querySelector('input[name="content"]');

    // Scroll to bottom helper
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Initial scroll
    scrollToBottom();

    // Helper: Format Time
    function formatTime(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    // Append Message to UI
    function appendMessage(message, isOptimistic = false) {
        // Avoid duplicates
        if (document.getElementById(`msg-${message.id}`)) return;

        const isMine = message.user_id == currentUserId;
        const msgWrapper = document.createElement('div');
        msgWrapper.id = `msg-${message.id}`;
        msgWrapper.className = `flex ${isMine ? 'justify-end' : 'justify-start'}`;
        
        const bgColor = isMine ? 'bg-primary text-slate-900 rounded-tl-none' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-tr-none border border-slate-100 dark:border-slate-700';
        const textColorTime = isMine ? 'text-slate-800' : 'text-slate-400';

        msgWrapper.innerHTML = `
            <div class="max-w-[80%] ${bgColor} px-4 py-3 rounded-2xl shadow-sm text-sm leading-relaxed ${isOptimistic ? 'opacity-50' : ''}">
                ${message.content}
                <div class="text-[10px] mt-1 opacity-70 ${textColorTime} text-left">
                    ${formatTime(message.created_at)}
                </div>
            </div>
        `;
        
        messagesContainer.appendChild(msgWrapper);
        if (!isOptimistic) scrollToBottom();
    }

    // Sync Negotiation Card
    function syncNegotiation(neg) {
        if (!neg) return;
        
        const priceInput = document.getElementById('neg-price');
        const qtyInput = document.getElementById('neg-qty');
        const totalEl = document.getElementById('neg-total');
        const statusBadge = document.querySelector('#negotiation-card span:last-child');

        // Update values if not being edited (simple check)
        if (document.activeElement !== priceInput) priceInput.value = neg.price;
        if (document.activeElement !== qtyInput) qtyInput.value = neg.quantity;
        
        totalEl.textContent = (neg.price * neg.quantity).toFixed(2);
        
        if (neg.status === 'accepted') {
            statusBadge.textContent = 'تم الاتفاق';
            statusBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700';
            // Disable inputs
            priceInput.readOnly = true;
            qtyInput.readOnly = true;
            // Hide confirm button if it exists
            const confirmBtn = document.querySelector('button[type="submit"][class*="bg-primary"]');
            if (confirmBtn) confirmBtn.remove();
        }
    }

    // AJAX Message Sending
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const content = messageInput.value.trim();
        if (!content) return;

        // Optimistic UI
        const tempId = Date.now();
        const tempMsg = {
            id: tempId,
            user_id: currentUserId,
            content: content,
            created_at: new Date().toISOString()
        };
        appendMessage(tempMsg, true);
        messageInput.value = '';

        fetch(messageForm.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ content: content })
        })
        .then(r => r.json())
        .then(data => {
            // Replace optimistic message with real one
            const tempEl = document.getElementById(`msg-${tempId}`);
            if (tempEl) tempEl.remove();
            appendMessage(data.message);
        });
    });

    // Polling Logic
    function poll() {
        fetch(window.location.href, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            data.messages.forEach(msg => appendMessage(msg));
            syncNegotiation(data.negotiation);
        })
        .catch(err => console.error('Polling error:', err));
    }

    // Start Polling (every 3 seconds)
    setInterval(poll, 3000);

    // Existing updateNegotiation function modified for consistency
    function updateNegotiation() {
        const price = document.getElementById('neg-price').value;
        const qty = document.getElementById('neg-qty').value;
        const totalEl = document.getElementById('neg-total');
        
        totalEl.textContent = (price * qty).toFixed(2);

        @if($conversation->negotiation)
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
        .then(response => response.json())
        .then(data => {
            console.log('Negotiation updated', data);
        });
        @endif
    }
</script>
@endsection
