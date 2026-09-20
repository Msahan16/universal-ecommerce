@extends('layouts.store')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
    $freeShippingThreshold = (float) \App\Models\SiteSetting::get('free_shipping_threshold', 15000);
@endphp

<!-- Breadcrumbs -->
<div class="bg-slate-900 text-white py-4 mb-4">
    <div class="container">
        <h1 class="fs-3 fw-extrabold mb-1">Your Shopping Cart</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 text-xs text-slate-400">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-slate-400 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-slate-400 text-decoration-none">Shop</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Cart</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    @if(count($items) > 0)
        <!-- Free shipping progress bar -->
        @if($freeShippingThreshold > 0)
            @php
                $percentage = min(100, round(($subtotal / $freeShippingThreshold) * 100));
                $difference = max(0, $freeShippingThreshold - $subtotal);
            @endphp
            <div class="card border-0 rounded-2xl shadow-sm p-4 bg-white mb-4 border border-blue-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-xs font-bold text-slate-700">
                        @if($difference > 0)
                            Add <strong class="text-blue-600">{{ $currency }}{{ number_format($difference, 2) }}</strong> more to unlock <strong class="text-emerald-600">FREE SHIPPING!</strong>
                        @else
                            🎉 <strong class="text-emerald-600">Congratulations! You qualify for Free Islandwide Shipping!</strong>
                        @endif
                    </span>
                    <span class="text-xs font-mono font-bold text-slate-500">{{ $percentage }}%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-gradient-to-r from-blue-500 to-emerald-500" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        @endif

        <div class="row g-4">
            <!-- Cart Items List -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-2xl shadow-sm bg-white overflow-hidden">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Product</th>
                                    <th class="py-3">Price</th>
                                    <th class="py-3 text-center">Quantity</th>
                                    <th class="py-3 text-end">Total</th>
                                    <th class="pe-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $key => $item)
                                <tr>
                                    <!-- Product Info -->
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}" class="rounded-xl object-cover" style="width: 60px; height: 60px;">
                                            <div>
                                                <h6 class="fw-bold text-slate-900 mb-0 text-sm">
                                                    <a href="{{ route('shop.show', \Illuminate\Support\Str::slug($item['name'])) }}" class="text-slate-900 text-decoration-none hover:text-blue-600">
                                                        {{ $item['name'] }}
                                                    </a>
                                                </h6>
                                                @if(!empty($item['variant_name']))
                                                    <span class="badge bg-slate-100 text-slate-700 text-xs mt-1">Option: {{ $item['variant_name'] }}</span>
                                                @endif
                                                @if(!empty($item['sku']))
                                                    <div class="text-xs text-slate-400 font-mono">SKU: {{ $item['sku'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Unit Price -->
                                    <td class="py-3 font-mono text-sm fw-semibold text-slate-700">
                                        {{ $currency }}{{ number_format($item['price'], 2) }}
                                    </td>

                                    <!-- Qty Selector -->
                                    <td class="py-3 text-center">
                                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline-flex align-items-center border rounded-lg bg-slate-50 p-1">
                                            @csrf
                                            <input type="hidden" name="item_key" value="{{ $key }}">
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="btn btn-sm btn-link text-slate-600 p-0 px-2 text-decoration-none">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <span class="px-2 font-bold text-sm text-slate-800">{{ $item['quantity'] }}</span>
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="btn btn-sm btn-link text-slate-600 p-0 px-2 text-decoration-none" {{ (isset($item['max_stock']) && $item['quantity'] >= $item['max_stock']) ? 'disabled' : '' }}>
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Subtotal -->
                                    <td class="py-3 text-end font-mono fw-bold text-slate-900 text-sm">
                                        {{ $currency }}{{ number_format($item['total'], 2) }}
                                    </td>

                                    <!-- Remove Button -->
                                    <td class="pe-4 py-3 text-center">
                                        <form action="{{ route('cart.remove', $key) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-rose-500 hover:text-rose-700 p-1" title="Remove item">
                                                <i class="bi bi-trash3 fs-6"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Back to Shop button -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-4 fw-bold">
                        <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Order Summary & Checkout -->
            <div class="col-lg-4">
                <!-- Coupon Code Card -->
                <div class="card border-0 rounded-2xl shadow-sm p-4 bg-white mb-4">
                    <h6 class="fw-bold text-slate-900 mb-3 text-sm">Have a Coupon or Promo Code?</h6>
                    
                    @if($coupon)
                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="badge bg-emerald-600 text-white font-mono font-bold">{{ $coupon['code'] }}</span>
                                <small class="text-emerald-700 d-block mt-1 font-medium text-xs">
                                    {{ $coupon['type'] === 'percentage' ? $coupon['value'].'% discount applied' : $currency.number_format($coupon['value'], 2).' discount applied' }}
                                </small>
                            </div>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm text-rose-600 hover:text-rose-800 p-0 text-xs fw-bold">
                                    Remove
                                </button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="coupon_code" class="form-control form-control-sm rounded-lg border-slate-200 text-xs font-mono uppercase" placeholder="e.g. WELCOME10" required>
                            <button type="submit" class="btn btn-slate-900 bg-slate-900 text-white btn-sm rounded-lg px-3 fw-bold text-xs">
                                Apply
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Order Summary Breakdown -->
                <div class="card border-0 rounded-2xl shadow-sm p-4 bg-white">
                    <h5 class="fw-bold text-slate-900 mb-3 text-base">Order Summary</h5>

                    <div class="space-y-3 text-sm">
                        <div class="d-flex justify-content-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-mono fw-semibold">{{ $currency }}{{ number_format($subtotal, 2) }}</span>
                        </div>

                        @if($discount > 0)
                        <div class="d-flex justify-content-between text-emerald-600">
                            <span>Coupon Discount</span>
                            <span class="font-mono fw-semibold">- {{ $currency }}{{ number_format($discount, 2) }}</span>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between text-slate-600">
                            <span>Shipping Fee</span>
                            <span class="font-mono fw-semibold">
                                @if($shipping > 0)
                                    {{ $currency }}{{ number_format($shipping, 2) }}
                                @else
                                    <span class="text-emerald-600 font-bold">FREE</span>
                                @endif
                            </span>
                        </div>

                        <hr class="border-slate-200 my-2">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-6 fw-bold text-slate-900">Total</span>
                            <span class="fs-4 fw-extrabold text-blue-600 font-mono">{{ $currency }}{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-brand-primary w-100 rounded-xl py-3 fw-bold mt-4 shadow-lg d-flex align-items-center justify-content-center gap-2">
                        <span>Proceed to Checkout</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>

                    <div class="text-center mt-3 text-xs text-slate-400">
                        <i class="bi bi-lock-fill me-1 text-emerald-500"></i> Secure Checkout Guaranteed
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 rounded-3xl shadow-sm p-5 text-center bg-white my-5 mx-auto" style="max-width: 550px;">
            <div class="w-20 h-20 rounded-full bg-blue-50 text-blue-600 d-flex align-items-center justify-content-center mx-auto mb-4 fs-1">
                <i class="bi bi-cart-x"></i>
            </div>
            <h4 class="fw-bold text-slate-900 mb-2">Your Shopping Cart is Empty</h4>
            <p class="text-slate-500 text-sm mb-4">Looks like you haven't added any items to your cart yet. Explore our catalog of high quality systems and spare parts.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-brand-primary rounded-pill px-4 py-2.5 fw-bold mx-auto">
                Start Shopping Now
            </a>
        </div>
    @endif
</div>

@endsection
