@extends('layouts.store')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<div class="container py-5">
    <div class="card border-0 rounded-3xl shadow-sm bg-white p-4 p-md-5 mx-auto" style="max-width: 780px;">
        <!-- Success Animation & Badge -->
        <div class="text-center mb-4">
            <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 d-flex align-items-center justify-content-center mx-auto mb-3 fs-1 shadow-sm">
                <i class="bi bi-check-lg"></i>
            </div>
            <span class="badge bg-emerald-100 text-emerald-800 px-3 py-1 rounded-pill text-xs font-bold uppercase mb-2">Order Confirmed</span>
            <h2 class="fs-2 fw-extrabold text-slate-900 tracking-tight mb-2">Thank You for Your Order!</h2>
            <p class="text-slate-500 text-sm mb-0">Your order has been received and is being processed by our dispatch team.</p>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 mb-4">
            <div class="row g-3 text-sm">
                <div class="col-sm-6">
                    <span class="text-xs text-slate-400 d-block uppercase font-bold">Order Number</span>
                    <span class="font-mono font-bold text-slate-900 fs-5">{{ $order->order_number }}</span>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <span class="text-xs text-slate-400 d-block uppercase font-bold">Order Date</span>
                    <span class="text-slate-800 fw-semibold">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="col-sm-6">
                    <span class="text-xs text-slate-400 d-block uppercase font-bold">Payment Method</span>
                    <span class="text-slate-800 fw-semibold text-uppercase">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Credit / Debit Card' }}</span>
                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'emerald-100 text-emerald-800' : 'amber-100 text-amber-800' }} ms-1 text-xs">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <span class="text-xs text-slate-400 d-block uppercase font-bold">Total Amount</span>
                    <span class="font-mono font-extrabold text-blue-600 fs-5">{{ $currency }}{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Ordered Items -->
        <div class="mb-4">
            <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Ordered Items</h6>
            <div class="border rounded-2xl overflow-hidden bg-white">
                <table class="table align-middle mb-0 text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                        <tr>
                            <th class="ps-4 py-2">Item</th>
                            <th class="py-2 text-center">Qty</th>
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
                            <td class="py-3 text-center font-semibold text-slate-700">{{ $item->quantity }}</td>
                            <td class="pe-4 py-3 text-end font-mono font-bold text-slate-900">{{ $currency }}{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Shipping Destination -->
        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-600 mb-4">
            <strong class="text-slate-800 d-block mb-1">Delivering To:</strong>
            <div>{{ $order->customer_name }} • {{ $order->customer_phone }}</div>
            <div>{{ $order->shipping_address }}, {{ $order->city }} {{ $order->postal_code }}</div>
        </div>

        <!-- Actions -->
        <div class="d-flex flex-wrap gap-3 justify-content-center pt-2">
            <a href="{{ route('orders.track', ['order_number' => $order->order_number]) }}" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold text-sm">
                <i class="bi bi-geo-alt-fill me-1"></i> Track Live Status
            </a>
            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2.5 fw-bold text-sm">
                Back to Shop
            </a>
        </div>
    </div>
</div>

@endsection
