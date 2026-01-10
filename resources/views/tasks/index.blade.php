@extends('layouts.app')

@section('content')
<!-- Top Navigation / Header -->
<header class="w-full px-6 py-5 sm:px-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-background-light dark:bg-background-dark shrink-0 border-b border-border-light dark:border-border-dark">
    <div class="flex flex-col gap-1">
        <h2 class="text-3xl font-black leading-tight tracking-tight">{{ __('tasks.title') }}</h2>
        <p class="text-text-secondary-light dark:text-text-secondary-dark text-base">{{ __('tasks.subtitle') }}</p>
    </div>
    <div class="flex items-center gap-3">
        <button class="size-10 rounded-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark flex items-center justify-center hover:bg-background-light dark:hover:bg-background-dark transition-colors relative">
            <span class="material-symbols-outlined">notifications</span>
            @if(isset($unreadCount) && $unreadCount > 0)
            <span class="absolute top-2 {{ app()->getLocale() == 'ar' ? 'left-2' : 'right-2' }} size-2 bg-red-500 rounded-full ring-2 ring-surface-light dark:ring-surface-dark"></span>
            @endif
        </button>
        <a href="{{ route('tasks.create') }}" class="flex items-center justify-center gap-2 rounded-xl h-12 px-6 bg-primary hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 text-background-dark text-sm font-bold">
            <span class="material-symbols-outlined text-[20px]">add</span>
            <span class="truncate">{{ __('tasks.add_new') }}</span>
        </a>
    </div>
</header>

<!-- Scrollable Content -->
<div class="flex-1 overflow-y-auto px-6 sm:px-10 pb-10">
    <div class="max-w-5xl mx-auto flex flex-col gap-6 py-6">
        <!-- Alert Panel -->
        @if($overdueTasks->count() > 0)
        <div class="@container">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 rounded-xl border border-red-200 bg-red-50 dark:bg-red-900/10 dark:border-red-900/30 p-5 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-2 rounded-lg shrink-0">
                        <span class="material-symbols-outlined">warning</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <p class="text-red-900 dark:text-red-200 text-base font-bold leading-tight">{{ __('tasks.alert_title') }}</p>
                        <p class="text-red-700 dark:text-red-300 text-sm">{{ __('tasks.alert_desc', ['count' => $overdueTasks->count()]) }}</p>
                    </div>
                </div>
                <button class="w-full sm:w-auto flex cursor-pointer items-center justify-center rounded-lg h-9 px-4 bg-white dark:bg-red-950 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-200 hover:bg-red-50 dark:hover:bg-red-900/40 text-sm font-medium transition-colors">
                    <span class="truncate">{{ __('tasks.view_overdue') }}</span>
                </button>
            </div>
        </div>
        @endif
        
        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-3">

            <a href="{{ route('tasks.index', ['filter' => 'today']) }}" class="flex h-10 items-center justify-center gap-x-2 rounded-xl {{ request('filter') == 'today' ? 'bg-text-main dark:bg-white text-white dark:text-text-main' : 'bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark hover:border-primary' }} px-5 transition-all">
                <p class="text-sm {{ request('filter') == 'today' ? 'font-bold' : 'font-medium' }}">{{ __('tasks.filter.today') }}</p>
                <span class="{{ request('filter') == 'today' ? 'bg-white/20 dark:bg-black/10' : 'bg-background-light dark:bg-background-dark text-text-secondary-light dark:text-text-secondary-dark' }} px-1.5 py-0.5 rounded text-xs font-bold">{{ $todayTasks->count() }}</span>
            </a>
            <a href="{{ route('tasks.index', ['filter' => 'tomorrow']) }}" class="flex h-10 items-center justify-center gap-x-2 rounded-xl {{ request('filter') == 'tomorrow' ? 'bg-text-main dark:bg-white text-white dark:text-text-main' : 'bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark hover:border-primary' }} px-5 transition-all">
                <p class="text-sm {{ request('filter') == 'tomorrow' ? 'font-bold' : 'font-medium' }}">{{ __('tasks.filter.tomorrow') }}</p>
                <span class="{{ request('filter') == 'tomorrow' ? 'bg-white/20 dark:bg-black/10' : 'bg-background-light dark:bg-background-dark text-text-secondary-light dark:text-text-secondary-dark' }} px-1.5 py-0.5 rounded text-xs font-bold">{{ $tomorrowTasks ?? 0 }}</span>
            </a>
            <a href="{{ route('tasks.index', ['filter' => 'overdue']) }}" class="flex h-10 items-center justify-center gap-x-2 rounded-xl {{ request('filter') == 'overdue' ? 'bg-text-main dark:bg-white text-white dark:text-text-main' : 'bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark hover:border-red-400 hover:text-red-600' }} px-5 transition-all">
                <p class="text-sm {{ request('filter') == 'overdue' ? 'font-bold' : 'font-medium' }}">{{ __('tasks.filter.overdue') }}</p>
                <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-1.5 py-0.5 rounded text-xs font-bold">{{ $overdueTasks->count() }}</span>
            </a>
        </div>
        
        <!-- Tasks List -->
        <div class="flex flex-col gap-3">
            <!-- Header for section -->
            <div class="flex items-center justify-between pb-2">
                <h3 class="text-lg font-bold">{{ __('tasks.today_tasks') }}</h3>
                <span class="text-sm text-text-secondary-light dark:text-text-secondary-dark">{{ \Carbon\Carbon::now()->isoFormat('dddd، D MMMM') }}</span>
            </div>
            
            @forelse($tasks as $task)
            <div id="task-card-{{ $task->id }}" class="group flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-light dark:bg-surface-dark p-4 rounded-xl border border-border-light dark:border-border-dark shadow-sm hover:shadow-md hover:border-primary/50 transition-all {{ $task->completed ? 'opacity-75 hover:opacity-100' : '' }} {{ $task->is_overdue && !$task->completed ? 'border-s-4 border-s-red-500' : '' }}">
                <div class="flex items-start gap-4 w-full">
                    <div class="pt-1">
                        <input type="checkbox" {{ $task->completed ? 'checked' : '' }} onchange="toggleTask(event, {{ $task->id }})" class="size-5 rounded border-2 border-gray-300 text-primary focus:ring-primary focus:ring-offset-0 cursor-pointer"/>
                    </div>
                    <div class="flex flex-col gap-1 grow">
                        <div class="flex items-center gap-2">
                            <p class="{{ $task->completed ? 'line-through decoration-gray-400 text-base font-medium' : 'text-base font-bold group-hover:text-primary transition-colors' }} line-clamp-1">{{ $task->title }}</p>
                            @if($task->priority == 'high')
                            <span class="hidden sm:inline-flex bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ __('tasks.priority.high') }}</span>
                            @elseif($task->priority == 'medium')
                            <span class="hidden sm:inline-flex bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ __('tasks.priority.medium') }}</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-text-secondary-light dark:text-text-secondary-dark">
                            @if($task->completed)
                            <div class="flex items-center gap-1 text-primary">
                                <span class="material-symbols-outlined text-[16px] filled">check_circle</span>
                                <span>{{ __('tasks.completed_at', ['time' => \Carbon\Carbon::parse($task->updated_at)->format('g:i A')]) }}</span>
                            </div>
                            @elseif($task->is_overdue)
                            <div class="flex items-center gap-1 text-red-600 font-medium">
                                <span class="material-symbols-outlined text-[16px]">event_busy</span>
                                <span>{{ __('tasks.overdue_since', ['days' => \Carbon\Carbon::parse($task->due_date)->diffInDays(\Carbon\Carbon::now())]) }}</span>
                            </div>
                            @else
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">schedule</span>
                                <span>{{ $task->due_time ?? __('tasks.no_time') }}</span>
                            </div>
                            @endif
                            @if($task->crop_id)
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">location_on</span>
                                <span>{{ $task->crop->name ?? __('tasks.no_location') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('tasks.show', $task->id) }}" class="size-9 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 flex items-center justify-center transition-colors group-action" title="{{ __('view_details') }}">
                        <span class="material-symbols-outlined text-[20px] text-blue-600">visibility</span>
                    </a>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="size-9 rounded-lg bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 flex items-center justify-center transition-colors group-action" title="{{ __('edit') }}">
                        <span class="material-symbols-outlined text-[20px] text-green-600">edit</span>
                    </a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('{{ __('confirm_delete') }}')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="size-9 rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 flex items-center justify-center transition-colors group-action" title="{{ __('delete') }}">
                            <span class="material-symbols-outlined text-[20px] text-red-600">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">task_alt</span>
                <p class="text-text-secondary-light dark:text-text-secondary-dark">{{ __('tasks.no_tasks') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function toggleTask(event, taskId) {
        const checkbox = event.target;
        checkbox.disabled = true;
        
        fetch(`/farmer/tasks/${taskId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Toggle successful, task data:', data);
            
            // Always remove the task element with animation
            const elementId = `task-card-${taskId}`;
            const taskElement = document.getElementById(elementId);
            console.log('Element to remove:', elementId, taskElement);
            
            if (taskElement) {
                taskElement.style.transition = 'all 0.3s ease';
                taskElement.style.opacity = '0';
                setTimeout(() => taskElement.remove(), 300);
            } else {
                // Element not found? fallback to reload
                console.log('Element not found, reloading page');
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Toggle error:', error);
            checkbox.disabled = false;
            checkbox.checked = !checkbox.checked; // Revert checkbox state
            alert('حدث خطأ في تحديث المهمة. يرجى المحاولة مرة أخرى.');
        });
    }
</script>
@endsection
