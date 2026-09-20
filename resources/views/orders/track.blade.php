@extends('layouts.store')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');

    $statuses = ['new', 'confirmed', 'processing', 'shipped', 'delivered'];
    $currentStatusIndex = $order ? array_search($order->status, $statuses) : -1;
    if ($currentStatusIndex === false) $currentStatusIndex = -1;
@endphp

<!-- Breadcrumbs -->
<div class="bg-slate-900 text-white py-4 mb-4">
    <div class="container">
        <h1 class="fs-3 fw-extrabold mb-1">Live Order Tracking</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 text-xs text-slate-400">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-slate-400 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Track Order</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <!-- Search Order Number Box -->
    <div class="card border-0 rounded-3xl shadow-sm p-4 bg-white mb-5 mx-auto" style="max-width: 650px;">
        <h5 class="fw-bold text-slate-900 mb-2 text-center">Track Your Package</h5>
        <p class="text-slate-500 text-xs text-center mb-4">Enter your order tracking number (e.g., ORD-2026-000101) to check delivery status.</p>

        <form action="{{ route('orders.track') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="order_number" value="{{ request('order_number') }}" class="form-control form-control-lg rounded-pill border-slate-200 ps-4 font-mono text-sm uppercase" placeholder="ORD-2026-XXXXXX" required>
            <button type="submit" class="btn btn-brand-primary rounded-pill px-4 fw-bold text-sm">
                Track
            </button>
        </form>
    </div>

    @if($order)
        <div class="card border-0 rounded-3xl shadow-sm bg-white p-4 p-md-5 mx-auto" style="max-width: 800px;">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <span class="text-xs text-slate-400 font-bold uppercase">Tracking Order</span>
                    <h3 class="fs-4 fw-extrabold text-slate-900 font-mono mb-0">{{ $order->order_number }}</h3>
                </div>
                <div class="text-md-end mt-2 mt-md-0">
                    <span class="badge bg-{{ $order->status_color }} text-white px-3 py-1.5 rounded-pill font-bold uppercase text-xs">
                        {{ $order->status }}
                    </span>
                    <small class="text-slate-400 d-block mt-1 text-xs">Placed on {{ $order->created_at->format('M d, Y') }}</small>
                </div>
            </div>

            <!-- Visual Stepper -->
            @if($order->status === 'cancelled')
                <div class="alert alert-danger rounded-2xl p-4 text-center mb-4">
                    <i class="bi bi-x-circle-fill fs-2 text-danger d-block mb-2"></i>
                    <h5 class="fw-bold">Order Cancelled</h5>
                    <p class="text-xs mb-0">This order has been cancelled. For further assistance, please contact our support team.</p>
                </div>
            @else
                <div class="py-4 my-2">
                    <div class="d-flex justify-content-between position-relative">
                        <!-- Progress Line -->
                        <div class="position-absolute top-50 start-0 translate-middle-y w-100 bg-slate-200" style="height: 4px; z-index: 1;"></div>
                        <div class="position-absolute top-50 start-0 translate-middle-y bg-blue-600 transition-all duration-500" style="height: 4px; width: {{ max(0, min(100, ($currentStatusIndex / 4) * 100)) }}%; z-index: 1;"></div>

                        <!-- Stepper Items -->
                        @php
                            $stepLabels = [
                                'new' => 'Placed',
                                'confirmed' => 'Confirmed',
                                'processing' => 'Processing',
                                'shipped' => 'Dispatched',
                                'delivered' => 'Delivered',
                            ];
                        @endphp

                        @foreach($statuses as $index => $st)
                            @php
                                $isCompleted = $index <= $currentStatusIndex;
                                $isCurrent = $index === $currentStatusIndex;
                            @endphp
                            <div class="text-center position-relative" style="z-index: 2;">
                                <div class="w-10 h-10 rounded-full d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold text-xs shadow-sm {{ $isCompleted ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }} {{ $isCurrent ? 'ring-4 ring-blue-100' : '' }}">
                                    @if($isCompleted && !$isCurrent)
                                        <i class="bi bi-check-lg"></i>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <span class="d-block text-xs font-semibold {{ $isCompleted ? 'text-slate-900' : 'text-slate-400' }}">
                                    {{ $stepLabels[$st] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Order Details Breakdown -->
            <div class="row g-4 mt-2">
                <div class="col-md-6">
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 h-100 text-xs">
                        <strong class="text-slate-900 d-block mb-2 uppercase tracking-wider text-xs">Shipping Information</strong>
                        <div class="text-slate-700 fw-semibold">{{ $order->customer_name }}</div>
                        <div class="text-slate-500">{{ $order->customer_phone }} • {{ $order->customer_email }}</div>
                        <div class="text-slate-600 mt-2">{{ $order->shipping_address }}, {{ $order->city }} {{ $order->postal_code }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 h-100 text-xs">
                        <strong class="text-slate-900 d-block mb-2 uppercase tracking-wider text-xs">Payment & Summary</strong>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-slate-500">Method:</span>
                            <span class="fw-semibold text-uppercase">{{ $order->payment_method }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-slate-500">Payment Status:</span>
                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'emerald-100 text-emerald-800' : 'amber-100 text-amber-800' }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                            <span class="fw-bold text-slate-800">Total Paid:</span>
                            <span class="font-mono font-bold text-blue-600 fs-6">{{ $currency }}{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ordered Items List -->
            <div class="mt-4 pt-3 border-top">
                <h6 class="fw-bold text-slate-900 mb-3 text-xs uppercase tracking-wider">Items in this shipment</h6>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="p-2.5 bg-white border rounded-xl d-flex justify-content-between align-items-center text-xs">
                            <div>
                                <strong class="text-slate-800">{{ $item->product_name }}</strong>
                                @if($item->variant_name)
                                    <span class="text-slate-500">({{ $item->variant_name }})</span>
                                @endif
                                <span class="text-slate-400 ms-2">x {{ $item->quantity }}</span>
                            </div>
                            <span class="font-mono font-bold text-slate-800">{{ $currency }}{{ number_format($item->total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @elseif(request('order_number'))
        <div class="card border-0 rounded-3xl shadow-sm p-5 text-center bg-white mx-auto my-4" style="max-width: 550px;">
            <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-500 d-flex align-items-center justify-content-center mx-auto mb-3 fs-3">
                <i class="bi bi-question-circle"></i>
            </div>
            <h5 class="fw-bold text-slate-900">Order Not Found</h5>
            <p class="text-slate-500 text-xs mb-0">We couldn't find any order matching "<strong class="font-mono">{{ request('order_number') }}</strong>". Please double check your order confirmation email.</p>
        </div>
    @endif
</div>

@endsection
