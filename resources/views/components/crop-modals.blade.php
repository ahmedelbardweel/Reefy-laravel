
<!-- Sell to Market Modal -->


<!-- Irrigation Modal -->
<div id="irrigationModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4 transition-all">
    <div class="bg-surface-light dark:bg-surface-dark rounded-3xl p-8 max-w-md w-full shadow-2xl border border-gray-100 dark:border-gray-800">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-black text-text-main dark:text-white">{{ __('crops.irrigation_title') }}</h3>
            <button onclick="document.getElementById('irrigationModal').classList.add('hidden')" class="size-10 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('crops.irrigate', $crop->id) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-2 text-text-main dark:text-white">{{ __('crops.irrigation_amount') }} ({{ __('liter') }})</label>
                    <div class="relative">
                        <input type="number" name="amount" step="1" min="1" value="50" required class="w-full rounded-xl border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark px-4 py-3 pl-10 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all font-bold">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-blue-500">water_drop</span>
                    </div>
                    <p class="text-xs text-text-muted mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        {{ __('crops.irrigation_note') }}
                    </p>
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button type="button" onclick="document.getElementById('irrigationModal').classList.add('hidden')" class="flex-1 px-4 py-3 border border-border-light dark:border-border-dark rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors font-bold text-text-muted">
                    {{ __('cancel') }}
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors font-bold shadow-lg shadow-blue-500/25">
                    {{ __('crops.confirm_irrigation') }}
                </button>
            </div>
        </form>
    </div>
</div>
