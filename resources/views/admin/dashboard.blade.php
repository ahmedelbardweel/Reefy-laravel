@extends('layouts.app')

@section('content')
<!-- Admin Header -->
<header class="bg-surface-light dark:bg-surface-dark border-b border-border-light dark:border-border-dark p-6 mb-6">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-text-main dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">admin_panel_settings</span>
                لوحة تحكم المسؤول
            </h1>
            <p class="text-text-secondary-light dark:text-text-secondary-dark mt-1">إدارة المنصة ونشر النصائح الزراعية</p>
        </div>
        <div class="flex items-center gap-4">
             <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-bold">
                 {{ Auth::user()->name }} (Admin)
             </span>
        </div>
    </div>
</header>

<div class="max-w-7xl mx-auto px-6 pb-12">
    
    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Farmers Count -->
        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl shadow-sm border border-border-light dark:border-border-dark flex items-center gap-4">
            <div class="size-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined">person</span>
            </div>
            <div>
                <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">عدد المزارعين</p>
                <h3 class="text-2xl font-bold text-text-main dark:text-white">{{ $stats['farmers_count'] }}</h3>
            </div>
        </div>

        <!-- Crops Count -->
        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl shadow-sm border border-border-light dark:border-border-dark flex items-center gap-4">
            <div class="size-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                <span class="material-symbols-outlined">potted_plant</span>
            </div>
            <div>
                <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">المحاصيل النشطة</p>
                <h3 class="text-2xl font-bold text-text-main dark:text-white">{{ $stats['crops_count'] }}</h3>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl shadow-sm border border-border-light dark:border-border-dark flex items-center gap-4">
            <div class="size-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 dark:text-yellow-400">
                <span class="material-symbols-outlined">pending_actions</span>
            </div>
            <div>
                <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">مهام قيد الانتظار</p>
                <h3 class="text-2xl font-bold text-text-main dark:text-white">{{ $stats['tasks_pending'] }}</h3>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl shadow-sm border border-border-light dark:border-border-dark flex items-center gap-4">
            <div class="size-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                <span class="material-symbols-outlined">task_alt</span>
            </div>
            <div>
                <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">مهام مكتملة</p>
                <h3 class="text-2xl font-bold text-text-main dark:text-white">{{ $stats['tasks_completed'] }}</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Publish Review/Advice Section (Main Feature) -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-border-light dark:border-border-dark overflow-hidden">
                <div class="bg-gradient-to-r from-primary to-green-600 px-6 py-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-white">campaign</span>
                    <h3 class="font-bold text-white text-lg">نشر نصيحة أو تنبيه للمزارعين</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.advice.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-text-main dark:text-white font-medium mb-2">نص الرسالة</label>
                            <textarea name="content" rows="4" class="w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark p-4 text-text-main dark:text-white focus:ring-2 focus:ring-primary" placeholder="اكتب نصيحة زراعية، تحذير جوي، أو إرشاد عام..."></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-primary hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined">send</span>
                                نشر الرسالة
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Activity Placeholder (Could be recent tasks or logs) -->
             <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-border-light dark:border-border-dark p-6">
                <h3 class="font-bold text-text-main dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-500">history</span>
                    سجل النشاطات الأخيرة
                </h3>
                <div class="text-center py-8 text-text-secondary-light dark:text-text-secondary-dark">
                    لا توجد نشاطات حديثة للعرض حالياً.
                </div>
            </div>
        </div>

        <!-- Recent Users Section -->
        <div class="lg:col-span-1">
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-border-light dark:border-border-dark overflow-hidden">
                <div class="px-6 py-4 border-b border-border-light dark:border-border-dark flex justify-between items-center">
                    <h3 class="font-bold text-text-main dark:text-white">أحدث المزارعين</h3>
                </div>
                <div class="divide-y divide-border-light dark:divide-border-dark">
                    @forelse($recent_farmers as $farmer)
                    <div class="p-4 flex items-center gap-3">
                        <div class="size-10 rounded-full bg-gray-200 dark:bg-gray-700 bg-cover bg-center" style="background-image: url('{{ $farmer->avatar ?? 'https://ui-avatars.com/api/?name='.$farmer->name }}')"></div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-sm text-text-main dark:text-white truncate">{{ $farmer->name }}</h4>
                            <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark truncate">{{ $farmer->email }}</p>
                        </div>
                        <form action="{{ route('admin.users.delete', $farmer->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 p-2 rounded-lg transition-colors" title="حذف المستخدم">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="p-6 text-center text-sm text-text-secondary-light dark:text-text-secondary-dark">
                        لا يوجد مزارعين مسجلين بعد.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
