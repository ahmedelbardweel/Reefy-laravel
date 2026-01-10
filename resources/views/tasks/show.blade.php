@extends('layouts.app')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('tasks.index') }}" class="size-10 rounded-full bg-background-light dark:bg-background-dark hover:bg-primary/10 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-text-main dark:text-white">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-3xl font-bold">{{ $task->title }}</h1>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark mt-1">{{ __('task.title') }}</p>
                </div>
            </div>
           
            <div class="flex items-center gap-2">
                <a href="{{ route('tasks.edit', $task->id) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                    {{ __('edit') }}
                </a>
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('{{ __('confirm_delete') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg flex items-center gap-2 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                        {{ __('delete') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Task Details Card -->
        <div class="bg-surface-light dark:bg-surface-dark rounded-3xl p-6 shadow-sm border border-border-light dark:border-border-dark">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Description -->
                <div class="md:col-span-2">
                    <h3 class="text-sm font-bold text-text-secondary-light dark:text-text-secondary-dark mb-2">{{ __('task.description') }}</h3>
                    <p class="text-base">{{ $task->description ?? __('tasks.no_description') }}</p>
                </div>

                <!-- Due Date & Time -->
                <div>
                    <h3 class="text-sm font-bold text-text-secondary-light dark:text-text-secondary-dark mb-2">{{ __('task.due_date') }}</h3>
                    <div class="flex items-center gap-2 text-base">
                        <span class="material-symbols-outlined text-primary">schedule</span>
                        {{ $task->due_date ? $task->due_date->format('Y-m-d H:i') : '--' }}
                    </div>
                </div>

                <!-- Priority -->
                <div>
                    <h3 class="text-sm font-bold text-text-secondary-light dark:text-text-secondary-dark mb-2">{{ __('task.priority') }}</h3>
                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-bold
                        @if($task->priority === 'high') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300
                        @else bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                        @endif">
                        {{ __('task.priority.' . $task->priority) }}
                    </span>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-sm font-bold text-text-secondary-light dark:text-text-secondary-dark mb-2">{{ __('task.status') }}</h3>
                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-bold
                        @if($task->status === 'completed') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300
                        @else bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-300
                        @endif">
                        {{ __('task.status.' . $task->status) }}
                    </span>
                </div>

                <!-- Category -->
                <div>
                    <h3 class="text-sm font-bold text-text-secondary-light dark:text-text-secondary-dark mb-2">{{ __('task.category') }}</h3>
                    <span class="inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">category</span>
                        {{ __('task.category.' . $task->category) }}
                    </span>
                </div>

                <!-- Related Crop -->
                @if($task->crop)
                <div class="md:col-span-2">
                    <h3 class="text-sm font-bold text-text-secondary-light dark:text-text-secondary-dark mb-2">{{ __('task.crop_related') }}</h3>
                    <div class="flex items-center gap-3 p-3 bg-background-light dark:bg-background-dark rounded-lg">
                        <div class="size-12 rounded-lg bg-cover bg-center" style="background-image: url('{{ $task->crop->image_url }}')"></div>
                        <div>
                            <p class="font-bold">{{ $task->crop->name }}</p>
                            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">{{ $task->crop->type }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Notification Info -->
        <div class="mt-6 bg-surface-light dark:bg-surface-dark rounded-3xl p-6 shadow-sm border border-border-light dark:border-border-dark">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">notifications</span>
                {{ __('notifications.title') }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mb-1">{{ __('tasks.reminder_time') }}</p>
                    <p class="font-bold">{{ $task->reminder_date ? $task->reminder_date->format('Y-m-d H:i') : '--' }}</p>
                </div>
                <div>
                    <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mb-1">{{ __('tasks.notification_status') }}</p>
                    @if($task->notification_sent)
                        <span class="inline-flex items-center gap-1 text-green-600">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            {{ __('tasks.notification_sent') }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-yellow-600">
                            <span class="material-symbols-outlined text-[18px]">schedule</span>
                            {{ __('tasks.notification_pending') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 flex items-center gap-3">
            @if($task->status !== 'completed')
                <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl flex items-center justify-center gap-2 transition-colors font-bold">
                        <span class="material-symbols-outlined">task_alt</span>
                        {{ __('tasks.mark_complete') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
