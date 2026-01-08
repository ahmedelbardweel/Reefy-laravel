<!DOCTYPE html>
<html class="light" dir="rtl" lang="ar">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ __('task.update_task') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;family=Noto+Sans+Arabic:wght@300;400;500;700&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec13",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102210",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "Noto Sans Arabic", "sans-serif"],
                        "body": ["Noto Sans Arabic", "sans-serif"],
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-body antialiased text-slate-800 dark:text-slate-100">
    <header class="sticky top-0 z-30 bg-background-light/95 dark:bg-background-dark/95 backdrop-blur-sm pt-4 pb-4 px-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <a href="{{ url()->previous() }}" class="flex items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-slate-900 transition-colors">
            <span class="material-symbols-outlined rtl:rotate-180">arrow_forward</span>
            <span class="font-bold">{{ __('cancel') }}</span>
        </a>
        <h1 class="text-xl font-bold font-display">{{ __('task.update_task') }}</h1>
        <div class="w-8"></div>
    </header>

    <main class="w-full max-w-md mx-auto px-5 py-6">
        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="flex flex-col gap-6">
            @csrf
            @method('PUT')
            
            <!-- Title -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('task.title') }}</label>
                <input type="text" name="title" required placeholder="{{ __('task.title') }}..." 
                       value="{{ old('title', $task->title) }}"
                       class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 py-3 px-4 focus:border-primary focus:ring-primary transition-all shadow-sm">
            </div>

            <!-- Description -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('inventory.additional_desc') }}</label>
                <textarea name="description" rows="3" placeholder="{{ __('inventory.notes_placeholder') }}" 
                          class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 py-3 px-4 focus:border-primary focus:ring-primary transition-all shadow-sm">{{ old('description', $task->description) }}</textarea>
            </div>

            <!-- Date -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('task.due_date') }}</label>
                <input type="datetime-local" name="due_date" required 
                       value="{{ old('due_date', $task->due_date->format('Y-m-d\TH:i')) }}"
                       class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 py-3 px-4 focus:border-primary focus:ring-primary transition-all shadow-sm dir-ltr">
            </div>

            <!-- Priority -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('task.priority') }}</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="priority" value="low" {{ old('priority', $task->priority) == 'low' ? 'checked' : '' }} class="peer sr-only">
                        <div class="text-center py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 peer-checked:bg-green-100 peer-checked:border-green-500 peer-checked:text-green-700 transition-all font-bold text-sm">
                            {{ __('task.priority.low') }}
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="priority" value="medium" {{ old('priority', $task->priority) == 'medium' ? 'checked' : '' }} class="peer sr-only">
                        <div class="text-center py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 peer-checked:bg-amber-100 peer-checked:border-amber-500 peer-checked:text-amber-700 transition-all font-bold text-sm">
                            {{ __('task.priority.medium') }}
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="priority" value="high" {{ old('priority', $task->priority) == 'high' ? 'checked' : '' }} class="peer sr-only">
                        <div class="text-center py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 peer-checked:bg-red-100 peer-checked:border-red-500 peer-checked:text-red-700 transition-all font-bold text-sm">
                            {{ __('task.priority.high') }}
                        </div>
                    </label>
                </div>
            </div>

            <!-- Category -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('task.category') }}</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="category" value="irrigation" {{ old('category', $task->category) == 'irrigation' ? 'checked' : '' }} class="peer sr-only">
                        <div class="flex items-center gap-2 p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 peer-checked:border-primary peer-checked:bg-slate-50 dark:peer-checked:bg-slate-800/50 transition-all">
                            <span class="material-symbols-outlined text-blue-500">water_drop</span>
                            <span class="font-bold text-sm">{{ __('task.category.irrigation') }}</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="category" value="fertilization" {{ old('category', $task->category) == 'fertilization' ? 'checked' : '' }} class="peer sr-only">
                        <div class="flex items-center gap-2 p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 peer-checked:border-primary peer-checked:bg-slate-50 dark:peer-checked:bg-slate-800/50 transition-all">
                            <span class="material-symbols-outlined text-amber-600">compost</span>
                            <span class="font-bold text-sm">{{ __('task.category.fertilization') }}</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="category" value="harvest" {{ old('category', $task->category) == 'harvest' ? 'checked' : '' }} class="peer sr-only">
                        <div class="flex items-center gap-2 p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 peer-checked:border-primary peer-checked:bg-slate-50 dark:peer-checked:bg-slate-800/50 transition-all">
                            <span class="material-symbols-outlined text-green-600">agriculture</span>
                            <span class="font-bold text-sm">{{ __('task.category.harvest') }}</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="category" value="inspection" {{ old('category', $task->category) == 'inspection' ? 'checked' : '' }} class="peer sr-only">
                        <div class="flex items-center gap-2 p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 peer-checked:border-primary peer-checked:bg-slate-50 dark:peer-checked:bg-slate-800/50 transition-all">
                            <span class="material-symbols-outlined text-purple-500">search</span>
                            <span class="font-bold text-sm">{{ __('task.category.inspection') }}</span>
                        </div>
                    </label>
                    <!-- Added Other option for completeness if needed in data but UI only shows 4? The create form had 4. -->
                </div>
            </div>

            <!-- Crop Link -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('task.crop_related') }}</label>
                <select name="crop_id" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 py-3 px-4 focus:border-primary focus:ring-primary transition-all shadow-sm">
                    <option value="">-- {{ __('task.crop_related') }} --</option>
                    @foreach($crops as $crop)
                        <option value="{{ $crop->id }}" {{ old('crop_id', $task->crop_id) == $crop->id ? 'selected' : '' }}>{{ $crop->name }} ({{ $crop->field_name }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Status (For Edit Only) -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('task.status') }}</label>
                <select name="status" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 py-3 px-4 focus:border-primary focus:ring-primary transition-all shadow-sm">
                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>{{ __('task.status.pending') }}</option>
                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>{{ __('task.status.in_progress') }}</option>
                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>{{ __('task.status.completed') }}</option>
                </select>
            </div>

            <button type="submit" class="w-full mt-4 bg-primary hover:bg-green-500 text-slate-900 font-bold py-4 rounded-xl shadow-lg shadow-primary/20 transition-all transform hover:scale-[1.02]">
                {{ __('task.update_task') }}
            </button>
        </form>
    </main>
</body>
</html>
