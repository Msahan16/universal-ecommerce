@extends('layouts.store')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<!-- Breadcrumbs -->
<div class="bg-slate-900 text-white py-4 mb-4">
    <div class="container">
        <h1 class="fs-3 fw-extrabold mb-1">Order Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 text-xs text-slate-400">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-slate-400 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.orders') }}" class="text-slate-400 text-decoration-none">My Orders</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $order->order_number }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="card border-0 rounded-3xl shadow-sm bg-white p-4 p-md-5 mx-auto" style="max-width: 800px;">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <div>
                <span class="text-xs text-slate-400 uppercase font-bold">Order Reference</span>
                <h4 class="fw-extrabold text-slate-900 font-mono mb-0">{{ $order->order_number }}</h4>
            </div>
            <div class="text-md-end mt-2 mt-md-0">
                <span class="badge bg-{{ $order->status_color }} text-white px-3 py-1 rounded-pill text-xs uppercase font-bold">
                    {{ $order->status }}
                </span>
                <small class="text-slate-400 d-block mt-1 text-xs">{{ $order->created_at->format('M d, Y h:i A') }}</small>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="fw-bold text-slate-900 mb-3 text-xs uppercase tracking-wider">Ordered Products</h6>
            <div class="border rounded-2xl overflow-hidden bg-white">
                <table class="table align-middle mb-0 text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                        <tr>
                            <th class="ps-4 py-2">Item</th>
                            <th class="py-2 text-center">Qty</th>
                            <th class="py-2 text-end">Price</th>
                            <th class="pe-4 py-2 text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-slate-900">{{ $item->product_name }}</div>
                                @if($item->variant_name)
                                    <div class="text-xs text-slate-500">Option: {{ $item->variant_name }}</div>
                                @endif
                                @if($item->sku)
                                    <div class="text-xs text-slate-400 font-mono">SKU: {{ $item->sku }}</div>
                                @endif
                            </td>
                            <td class="py-3 text-center font-semibold">{{ $item->quantity }}</td>
                            <td class="py-3 text-end font-mono">{{ $currency }}{{ number_format($item->price, 2) }}</td>
                            <td class="pe-4 py-3 text-end font-mono font-bold text-slate-900">{{ $currency }}{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row g-4 text-xs">
            <div class="col-md-6">
                <div class="p-3 bg-slate-50 rounded-2xl border">
                    <strong class="text-slate-900 d-block mb-1 uppercase tracking-wider">Delivery Destination</strong>
                    <div class="text-slate-700 fw-semibold">{{ $order->customer_name }}</div>
                    <div class="text-slate-500">{{ $order->customer_phone }}</div>
                    <div class="text-slate-600 mt-1">{{ $order->shipping_address }}, {{ $order->city }} {{ $order->postal_code }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-slate-50 rounded-2xl border">
                    <strong class="text-slate-900 d-block mb-1 uppercase tracking-wider">Payment Breakdown</strong>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-slate-500">Subtotal:</span>
                        <span class="font-mono">{{ $currency }}{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="d-flex justify-content-between mb-1 text-emerald-600">
                        <span>Discount:</span>
                        <span class="font-mono">- {{ $currency }}{{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-slate-500">Shipping:</span>
                        <span class="font-mono">{{ $currency }}{{ number_format($order->shipping_fee, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between pt-2 border-top font-bold text-slate-900 fs-6">
                        <span>Total:</span>
                        <span class="font-mono text-blue-600">{{ $currency }}{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Orders
            </a>
            <a href="{{ route('orders.track', ['order_number' => $order->order_number]) }}" class="btn btn-primary rounded-pill btn-sm px-4 fw-bold">
                <i class="bi bi-geo-alt-fill me-1"></i> Track Order Status
            </a>
        </div>
    </div>
</div>

@endsection
