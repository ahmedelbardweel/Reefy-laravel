@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('المبيعات') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-bold mb-4">طلبات الشراء الواردة</h3>

                @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الطلب</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المشتري</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتجات</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجمالي</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تحديث الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">#{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->created_at->format('Y/m/d') }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <ul class="list-disc list-inside">
                                    @foreach($order->items as $item)
                                        <li>{{ $item->product->name }} (x{{ $item->quantity }})</li>
                                    @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-primary">
                                    {{ $order->total_amount }} {{ __('currency.sar') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ __('orders.status.' . $order->status) ?? ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('sales.updateStatus', $order) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="text-sm rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>تم الطلب (Pending)</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد التجهيز (Processing)</option>
                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>جاري التوصيل (On Way)</option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم الاستلام (Delivered)</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>إلغاء (Cancelled)</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
                @else
                <p class="text-center text-gray-500 py-10">لا توجد مبيعات حتى الآن.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
