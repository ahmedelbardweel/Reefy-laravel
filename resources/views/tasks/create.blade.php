@extends('layouts.app')

@section('content')
<!-- Top Navigation Bar -->
<header class="h-20 flex items-center justify-between px-8 bg-surface-light dark:bg-surface-dark border-b border-border-light dark:border-border-dark shrink-0 z-10">
    <div class="flex items-center gap-4 lg:hidden">
        <button class="text-text-main dark:text-white">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <span class="text-lg font-bold">Reefy</span>
    </div>
    <div class="hidden lg:flex flex-1 max-w-xl">
        <div class="relative w-full group">
            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none text-text-secondary-light dark:text-text-secondary-dark">
                <span class="material-symbols-outlined">search</span>
            </div>
            <input class="block w-full rounded-lg border-none bg-background-light dark:bg-background-dark py-2.5 {{ app()->getLocale() == 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} text-sm text-text-main dark:text-white placeholder-text-secondary-light dark:placeholder-text-secondary-dark focus:ring-2 focus:ring-primary focus:bg-surface-light dark:focus:bg-surface-dark transition-all" placeholder="{{ __('search') }}" type="text"/>
        </div>
    </div>
    <div class="flex items-center gap-4 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }}">
        <button class="relative p-2 text-text-secondary-light dark:text-text-secondary-dark hover:bg-background-light dark:hover:bg-background-dark rounded-full transition-colors">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2 {{ app()->getLocale() == 'ar' ? 'left-2' : 'right-2' }} size-2 bg-red-500 rounded-full border border-surface-light dark:border-surface-dark"></span>
        </button>
        <div class="h-10 w-10 rounded-full bg-cover bg-center border-2 border-surface-light dark:border-surface-dark shadow-sm cursor-pointer" style='background-image: url("{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.Auth::user()->name }}");'></div>
    </div>
</header>

<!-- Scrollable Content -->
<div class="flex-1 overflow-y-auto p-6 md:p-10">
    <div class="mx-auto max-w-4xl">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-text-main dark:text-white">{{ __('tasks.create_title') }}</h2>
                <p class="text-text-secondary-light dark:text-text-secondary-dark mt-1 text-sm">{{ __('tasks.create_subtitle') }}</p>
            </div>
            <a href="{{ route('tasks.index') }}" class="hidden md:flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                <span class="material-symbols-outlined text-[20px]">delete</span>
                {{ __('tasks.form.cancel') }}
            </a>
        </div>
        
        <!-- Form Card -->
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-border-light dark:border-border-dark overflow-hidden">
            <!-- Header with Progress/Icon -->
            <div class="bg-background-light dark:bg-background-dark px-6 py-4 border-b border-border-light dark:border-border-dark flex items-center gap-3">
                <div class="size-8 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-sm">edit_document</span>
                </div>
                <span class="font-bold text-sm text-text-main dark:text-white">{{ __('tasks.form.task_details') }}</span>
            </div>
            
            <form action="{{ route('tasks.store') }}" method="POST" class="p-6 md:p-8 space-y-8">
                @csrf
                <!-- Task Name Input -->
                <div class="space-y-2">
                    <label class="block text-base font-semibold text-text-main dark:text-white">{{ __('tasks.form.task_name') }} <span class="text-red-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none text-text-secondary-light dark:text-text-secondary-dark">
                            <span class="material-symbols-outlined group-focus-within:text-primary transition-colors">assignment</span>
                        </div>
                        <input type="text" name="title" required class="block w-full rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark py-3.5 {{ app()->getLocale() == 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} text-text-main dark:text-white placeholder-text-secondary-light dark:placeholder-text-secondary-dark focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="{{ __('tasks.form.task_name_placeholder') }}"/>
                    </div>
                </div>
                
                <!-- Field & Type Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-text-main dark:text-white">{{ __('tasks.form.related_field') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="crop_id" class="block w-full appearance-none rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark py-3.5 {{ app()->getLocale() == 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} text-text-main dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                <option disabled selected value="">{{ __('tasks.form.select_field') }}</option>
                                @foreach($crops as $crop)
                                <option value="{{ $crop->id }}">{{ $crop->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none text-text-secondary-light dark:text-text-secondary-dark">
                                <span class="material-symbols-outlined">location_on</span>
                            </div>
                            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center pointer-events-none text-text-secondary-light dark:text-text-secondary-dark">
                                <span class="material-symbols-outlined">expand_more</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-text-main dark:text-white">{{ __('tasks.form.task_type') }}</label>
                        <div class="relative">
                            <select name="category" class="block w-full appearance-none rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark py-3.5 {{ app()->getLocale() == 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} text-text-main dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                <option value="irrigation">{{ __('tasks.form.type_irrigation') }}</option>
                                <option value="fertilization">{{ __('tasks.form.type_fertilizer') }}</option>
                                <option value="harvest">{{ __('tasks.form.type_harvest') }}</option>
                                <option value="inspection">{{ __('tasks.form.type_maintenance') }}</option>
                                <option value="other">{{ __('tasks.form.type_other') }}</option>
                            </select>
                            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none text-text-secondary-light dark:text-text-secondary-dark">
                                <span class="material-symbols-outlined">category</span>
                            </div>
                            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center pointer-events-none text-text-secondary-light dark:text-text-secondary-dark">
                                <span class="material-symbols-outlined">expand_more</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Schedule Section -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-text-main dark:text-white">{{ __('tasks.form.schedule') }}</label>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="relative flex-1">
                            <input type="date" name="due_date" required class="block w-full rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark py-3.5 pr-4 pl-4 text-text-main dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all" value="{{ date('Y-m-d') }}"/>
                        </div>
                        <div class="relative flex-1">
                            <input type="time" name="due_time" class="block w-full rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark py-3.5 pr-4 pl-4 text-text-main dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all" value="09:00"/>
                        </div>
                    </div>
                </div>
                
                <!-- Priority Section -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-text-main dark:text-white">{{ __('tasks.form.priority') }}</label>
                    <div class="flex gap-4">
                        <label class="cursor-pointer relative flex-1">
                            <input type="radio" name="priority" value="high" class="peer sr-only"/>
                            <div class="flex flex-col items-center justify-center p-3 bg-background-light dark:bg-background-dark border-2 border-transparent rounded-lg peer-checked:bg-red-50 dark:peer-checked:bg-red-900/10 peer-checked:border-red-500 peer-checked:text-red-700 dark:peer-checked:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                                <span class="material-symbols-outlined mb-1">priority_high</span>
                                <span class="text-sm font-bold">{{ __('tasks.form.priority_high') }}</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative flex-1">
                            <input type="radio" name="priority" value="medium" checked class="peer sr-only"/>
                            <div class="flex flex-col items-center justify-center p-3 bg-background-light dark:bg-background-dark border-2 border-transparent rounded-lg peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/10 peer-checked:border-yellow-500 peer-checked:text-yellow-700 dark:peer-checked:text-yellow-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                                <span class="material-symbols-outlined mb-1">remove</span>
                                <span class="text-sm font-bold">{{ __('tasks.form.priority_medium') }}</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative flex-1">
                            <input type="radio" name="priority" value="low" class="peer sr-only"/>
                            <div class="flex flex-col items-center justify-center p-3 bg-background-light dark:bg-background-dark border-2 border-transparent rounded-lg peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/10 peer-checked:border-blue-500 peer-checked:text-blue-700 dark:peer-checked:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                                <span class="material-symbols-outlined mb-1">arrow_downward</span>
                                <span class="text-sm font-bold">{{ __('tasks.form.priority_low') }}</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Notes Section -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-text-main dark:text-white">{{ __('tasks.form.additional_notes') }}</label>
                    <textarea name="description" rows="3" class="block w-full rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-4 text-text-main dark:text-white placeholder-text-secondary-light dark:placeholder-text-secondary-dark focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none" placeholder="{{ __('tasks.form.notes_placeholder') }}"></textarea>
                </div>
                
                <!-- Action Buttons -->
                <div class="pt-4 flex flex-col-reverse md:flex-row gap-4 border-t border-border-light dark:border-border-dark">
                    <a href="{{ route('tasks.index') }}" class="md:w-32 py-3 rounded-lg border border-border-light dark:border-border-dark text-text-main dark:text-white font-bold hover:bg-background-light dark:hover:bg-background-dark transition-colors flex items-center justify-center">
                        {{ __('tasks.form.cancel') }}
                    </a>
                    <button type="submit" class="flex-1 py-3 rounded-lg bg-primary text-background-dark font-bold shadow-lg shadow-primary/20 hover:bg-primary-dark transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">save</span>
                        {{ __('tasks.form.save') }}
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Footer Info -->
        <p class="text-center text-xs text-text-secondary-light dark:text-text-secondary-dark mt-8 mb-4">
            {{ __('tasks.form.notification_hint') }}
        </p>
    </div>
</div>
@endsection
