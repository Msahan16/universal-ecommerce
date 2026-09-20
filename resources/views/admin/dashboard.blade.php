@extends('layouts.admin')

@section('page_title', 'Analytics Dashboard')
@section('page_subtitle', 'Real-time commerce metrics and store activity')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<!-- KPI Cards -->
<div class="row g-4 mb-4">
    <!-- Total Revenue -->
    <div class="col-xl-3 col-sm-6">
        <div class="admin-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-xs uppercase font-bold text-slate-400">Total Sales</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 d-flex align-items-center justify-content-center fs-5">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <h3 class="fs-4 fw-extrabold text-slate-900 font-mono mb-1">{{ $currency }}{{ number_format($totalSales, 2) }}</h3>
            <span class="text-xs text-emerald-600 font-bold"><i class="bi bi-arrow-up-right"></i> Completed transactions</span>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-xl-3 col-sm-6">
        <div class="admin-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-xs uppercase font-bold text-slate-400">Total Orders</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 d-flex align-items-center justify-content-center fs-5">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
            <h3 class="fs-4 fw-extrabold text-slate-900 font-mono mb-1">{{ $totalOrders }}</h3>
            <span class="text-xs text-slate-500 font-semibold">{{ $pendingOrders }} pending fulfillment</span>
        </div>
    </div>

    <!-- Active Products -->
    <div class="col-xl-3 col-sm-6">
        <div class="admin-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-xs uppercase font-bold text-slate-400">Total Catalog Items</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 d-flex align-items-center justify-content-center fs-5">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <h3 class="fs-4 fw-extrabold text-slate-900 font-mono mb-1">{{ $totalProducts }}</h3>
            <span class="text-xs text-emerald-600 font-semibold">Active in store</span>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="col-xl-3 col-sm-6">
        <div class="admin-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-xs uppercase font-bold text-slate-400">Low Stock Alert</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 d-flex align-items-center justify-content-center fs-5">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
            <h3 class="fs-4 fw-extrabold text-slate-900 font-mono mb-1">{{ $lowStockCount }}</h3>
            <span class="text-xs text-rose-600 font-bold">Items require restock</span>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders Table -->
    <div class="col-lg-8">
        <div class="admin-card overflow-hidden">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-slate-900 mb-0">Recent Store Orders</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill text-xs px-3">
                    View All Orders
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                        <tr>
                            <th class="ps-4 py-3">Order #</th>
                            <th class="py-3">Customer</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Total</th>
                            <th class="pe-4 py-3 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td class="ps-4 py-3 font-mono font-bold text-slate-900">
                                {{ $order->order_number }}
                            </td>
                            <td class="py-3">
                                <div class="fw-bold text-slate-800">{{ $order->customer_name }}</div>
                                <small class="text-slate-400 text-xs">{{ $order->city }}</small>
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
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light border rounded-lg text-xs px-2.5">
                                    Manage
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-slate-400">No orders placed yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Low Stock Items & Quick Links -->
    <div class="col-lg-4">
        <!-- Low Stock Items Warning -->
        <div class="admin-card p-4 mb-4">
            <h6 class="fw-bold text-slate-900 mb-3 text-sm d-flex align-items-center gap-2">
                <i class="bi bi-shield-exclamation text-amber-500"></i> Stock Warnings
            </h6>

            @if($lowStockProducts->count() > 0)
                <div class="space-y-3">
                    @foreach($lowStockProducts as $lsp)
                        <div class="d-flex align-items-center justify-content-between p-2.5 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                            <div>
                                <strong class="text-slate-800 d-block line-clamp-1">{{ $lsp->name }}</strong>
                                <span class="text-slate-400 font-mono">SKU: {{ $lsp->sku }}</span>
                            </div>
                            <span class="badge bg-rose-600 text-white font-mono px-2 py-1">
                                {{ $lsp->stock }} left
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-xs text-emerald-600 fw-semibold">
                    <i class="bi bi-check-circle me-1"></i> All products have healthy inventory levels.
                </div>
            @endif
        </div>

        <!-- Quick Administration Shortcuts -->
        <div class="admin-card p-4">
            <h6 class="fw-bold text-slate-900 mb-3 text-sm">Quick Management</h6>
            <div class="space-y-2">
                <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary btn-sm w-100 rounded-xl py-2 fw-semibold d-flex align-items-center justify-content-between text-xs">
                    <span><i class="bi bi-plus-circle me-2"></i> Add New Product</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
                <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary btn-sm w-100 rounded-xl py-2 fw-semibold d-flex align-items-center justify-content-between text-xs">
                    <span><i class="bi bi-sliders2 me-2"></i> Configure Attributes</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
                <a href="{{ route('admin.cms.index') }}" class="btn btn-outline-secondary btn-sm w-100 rounded-xl py-2 fw-semibold d-flex align-items-center justify-content-between text-xs">
                    <span><i class="bi bi-palette me-2"></i> Edit Website CMS / Hero</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection