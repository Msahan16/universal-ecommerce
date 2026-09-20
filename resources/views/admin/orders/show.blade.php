@extends('layouts.admin')

@section('page_title', 'Order Management: ' . $order->order_number)
@section('page_subtitle', 'Update order progress, fulfillment, and customer delivery details')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<div class="row g-4">
    <!-- Left Column: Order Items & Summary -->
    <div class="col-lg-8">
        <div class="admin-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <span class="text-xs text-slate-400 uppercase font-bold">Order ID</span>
                    <h4 class="fw-extrabold text-slate-900 font-mono mb-0">{{ $order->order_number }}</h4>
                    <small class="text-slate-500 text-xs">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</small>
                </div>
                <div>
                    <span class="badge bg-{{ $order->status_color }} text-white px-3 py-1.5 rounded-pill text-xs uppercase font-bold">
                        {{ $order->status }}
                    </span>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive mb-4">
                <table class="table align-middle mb-0 text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                        <tr>
                            <th class="ps-3 py-2">Item</th>
                            <th class="py-2 text-center">Qty</th>
                            <th class="py-2 text-end">Unit Price</th>
                            <th class="pe-3 py-2 text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td class="ps-3 py-3">
                                <strong class="text-slate-900 d-block">{{ $item->product_name }}</strong>
                                @if($item->variant_name)
                                    <span class="text-slate-500 text-xs">Option: {{ $item->variant_name }}</span>
                                @endif
                                @if($item->sku)
                                    <span class="text-slate-400 text-xs font-mono ms-2">SKU: {{ $item->sku }}</span>
                                @endif
                            </td>
                            <td class="py-3 text-center font-semibold">{{ $item->quantity }}</td>
                            <td class="py-3 text-end font-mono text-xs">{{ $currency }}{{ number_format($item->price, 2) }}</td>
                            <td class="pe-3 py-3 text-end font-mono font-bold text-slate-900">{{ $currency }}{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Financial Summary -->
            <div class="p-3 bg-slate-50 rounded-2xl border text-sm" style="max-width: 380px; margin-left: auto;">
                <div class="d-flex justify-content-between text-slate-600 mb-1">
                    <span>Subtotal</span>
                    <span class="font-mono">{{ $currency }}{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="d-flex justify-content-between text-emerald-600 mb-1">
                    <span>Discount ({{ $order->coupon_code ?? 'Promo' }})</span>
                    <span class="font-mono">- {{ $currency }}{{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                <div class="d-flex justify-content-between text-slate-600 mb-1">
                    <span>Shipping Fee</span>
                    <span class="font-mono">{{ $currency }}{{ number_format($order->shipping_fee, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between pt-2 border-top font-bold text-slate-900 fs-6">
                    <span>Grand Total</span>
                    <span class="font-mono text-blue-600">{{ $currency }}{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Status Controls & Customer Info -->
    <div class="col-lg-4">
        <!-- Status Control Box -->
        <div class="admin-card p-4 mb-4">
            <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Update Order Progress</h6>

            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Fulfillment Status</label>
                    <select name="status" class="form-select rounded-xl border-slate-200 text-sm">
                        <option value="new" {{ $order->status === 'new' ? 'selected' : '' }}>1. New Order</option>
                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>2. Confirmed</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>3. Processing / Packing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>4. Shipped / Dispatched</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>5. Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>X. Cancelled</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label text-xs fw-bold text-slate-700">Payment Status</label>
                    <select name="payment_status" class="form-select rounded-xl border-slate-200 text-sm">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold text-xs shadow-lg">
                    Update Order Status
                </button>
            </form>
        </div>

        <!-- Customer & Destination Details -->
        <div class="admin-card p-4">
            <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Delivery Destination</h6>

            <div class="space-y-2 text-xs text-slate-700">
                <div>
                    <span class="text-slate-400 d-block font-semibold">Recipient:</span>
                    <strong class="text-slate-900 fs-6">{{ $order->customer_name }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 d-block font-semibold">Phone:</span>
                    <strong class="font-mono">{{ $order->customer_phone }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 d-block font-semibold">Email:</span>
                    <span>{{ $order->customer_email }}</span>
                </div>
                <div>
                    <span class="text-slate-400 d-block font-semibold">Address:</span>
                    <span>{{ $order->shipping_address }}, {{ $order->city }} {{ $order->postal_code }}</span>
                </div>

                @if($order->notes)
                <div class="p-2.5 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 mt-3">
                    <strong>Customer Notes:</strong>
                    <div>{{ $order->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
