@extends('layouts.admin')

@section('page_title', 'Order Management & Fulfillment')
@section('page_subtitle', 'Track and update store customer orders')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<!-- Status Filter Tabs -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-light border' }} rounded-pill px-3 text-xs fw-semibold">
            All Orders
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'new']) }}" class="btn btn-sm {{ request('status') === 'new' ? 'btn-primary' : 'btn-light border' }} rounded-pill px-3 text-xs fw-semibold">
            New
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="btn btn-sm {{ request('status') === 'confirmed' ? 'btn-info text-white' : 'btn-light border' }} rounded-pill px-3 text-xs fw-semibold">
            Confirmed
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="btn btn-sm {{ request('status') === 'processing' ? 'btn-warning text-dark' : 'btn-light border' }} rounded-pill px-3 text-xs fw-semibold">
            Processing
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="btn btn-sm {{ request('status') === 'shipped' ? 'btn-secondary' : 'btn-light border' }} rounded-pill px-3 text-xs fw-semibold">
            Shipped
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="btn btn-sm {{ request('status') === 'delivered' ? 'btn-success' : 'btn-light border' }} rounded-pill px-3 text-xs fw-semibold">
            Delivered
        </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex gap-2" style="max-width: 320px;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm rounded-lg border-slate-200 text-xs font-mono" placeholder="Order # or Customer...">
        <button type="submit" class="btn btn-dark btn-sm rounded-lg px-3 text-xs">Search</button>
    </form>
</div>

<!-- Orders Table -->
<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0 text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                <tr>
                    <th class="ps-4 py-3">Order Number</th>
                    <th class="py-3">Customer</th>
                    <th class="py-3">Date</th>
                    <th class="py-3">Payment</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Total</th>
                    <th class="pe-4 py-3 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="ps-4 py-3 font-mono font-bold text-slate-900">
                        {{ $order->order_number }}
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-slate-900 text-xs">{{ $order->customer_name }}</div>
                        <small class="text-slate-500 text-xs">{{ $order->customer_phone }}</small>
                    </td>
                    <td class="py-3 text-xs text-slate-500">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                    <td class="py-3 text-xs">
                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'emerald-100 text-emerald-800' : 'amber-100 text-amber-800' }} rounded-pill">
                            {{ strtoupper($order->payment_method) }} • {{ strtoupper($order->payment_status) }}
                        </span>
                    </td>
                    <td class="py-3">
                        <span class="badge bg-{{ $order->status_color }} text-white text-xs px-2.5 py-1 rounded-pill uppercase">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="py-3 font-mono font-bold text-slate-900">
                        {{ $currency }}{{ number_format($order->total, 2) }}
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 text-xs fw-semibold">
                            View & Manage
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-slate-400">No orders found matching the filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex justify-content-center">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
