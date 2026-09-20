@extends('layouts.store')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<!-- Breadcrumbs -->
<div class="bg-slate-900 text-white py-4 mb-4">
    <div class="container">
        <h1 class="fs-3 fw-extrabold mb-1">Express Checkout</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 text-xs text-slate-400">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-slate-400 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-slate-400 text-decoration-none">Cart</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Checkout</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-4">
            <!-- Left Column: Shipping & Payment Details -->
            <div class="col-lg-7">
                <!-- Customer Contact Information -->
                <div class="card border-0 rounded-2xl shadow-sm p-4 bg-white mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-slate-900 mb-0 text-base">
                            <i class="bi bi-person-fill text-blue-600 me-2"></i>Contact Information
                        </h5>
                        @guest
                            <span class="text-xs text-slate-500">Checking out as <strong class="text-blue-600">Guest</strong> (or <a href="{{ route('login') }}" class="text-blue-600">Sign In</a>)</span>
                        @endguest
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-slate-700">Full Name *</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', $user->name ?? '') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="e.g. Kasun Perera" required>
                            @error('customer_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-slate-700">Email Address *</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', $user->email ?? '') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="name@example.com" required>
                            @error('customer_email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-slate-700">Mobile Phone Number *</label>
                            <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="077 123 4567" required>
                            @error('customer_phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card border-0 rounded-2xl shadow-sm p-4 bg-white mb-4">
                    <h5 class="fw-bold text-slate-900 mb-3 text-base">
                        <i class="bi bi-geo-alt-fill text-blue-600 me-2"></i>Delivery Address
                    </h5>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-slate-700">Street Address / House No / Building *</label>
                            <textarea name="shipping_address" rows="2" class="form-control rounded-xl border-slate-200 text-sm" placeholder="No 12, Galle Road..." required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-slate-700">City / Town *</label>
                            <input type="text" name="city" value="{{ old('city') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="Colombo" required>
                            @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-slate-700">Postal Code</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="00400">
                        </div>

                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-slate-700">Order Notes (Optional)</label>
                            <input type="text" name="notes" value="{{ old('notes') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="Special delivery instructions, landmark, etc.">
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 rounded-2xl shadow-sm p-4 bg-white mb-4">
                    <h5 class="fw-bold text-slate-900 mb-3 text-base">
                        <i class="bi bi-credit-card-2-front-fill text-blue-600 me-2"></i>Select Payment Method
                    </h5>

                    <div class="space-y-3">
                        <!-- COD Option -->
                        <div class="form-check p-3 border rounded-xl hover:bg-slate-50 transition cursor-pointer d-flex align-items-center">
                            <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_cod" value="cod" checked onclick="document.getElementById('cardFields').classList.add('d-none')">
                            <label class="form-check-label d-flex justify-content-between align-items-center w-100 cursor-pointer" for="pay_cod">
                                <div>
                                    <strong class="text-slate-900 text-sm d-block">Cash on Delivery (COD)</strong>
                                    <small class="text-slate-500 text-xs">Pay with cash when your package arrives at your doorstep.</small>
                                </div>
                                <span class="badge bg-slate-100 text-slate-800"><i class="bi bi-cash-stack fs-5"></i></span>
                            </label>
                        </div>

                        <!-- Card Gateway Option -->
                        <div class="form-check p-3 border rounded-xl hover:bg-slate-50 transition cursor-pointer d-flex align-items-center">
                            <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_card" value="card" onclick="document.getElementById('cardFields').classList.remove('d-none')">
                            <label class="form-check-label d-flex justify-content-between align-items-center w-100 cursor-pointer" for="pay_card">
                                <div>
                                    <strong class="text-slate-900 text-sm d-block">Credit / Debit Card (Instant Gateway)</strong>
                                    <small class="text-slate-500 text-xs">Secure instant online payment via Visa, Mastercard & AMEX.</small>
                                </div>
                                <div class="d-flex gap-1 text-blue-600 fs-5">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Card Simulator Fields -->
                    <div id="cardFields" class="d-none mt-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label text-xs fw-bold text-slate-600">Card Number</label>
                                <input type="text" class="form-control form-control-sm rounded-lg border-slate-200 font-mono" placeholder="4532 •••• •••• 8920" maxlength="19">
                            </div>
                            <div class="col-6">
                                <label class="form-label text-xs fw-bold text-slate-600">Expiry (MM/YY)</label>
                                <input type="text" class="form-control form-control-sm rounded-lg border-slate-200 font-mono" placeholder="12/28" maxlength="5">
                            </div>
                            <div class="col-6">
                                <label class="form-label text-xs fw-bold text-slate-600">CVV / CVC</label>
                                <input type="password" class="form-control form-control-sm rounded-lg border-slate-200 font-mono" placeholder="•••" maxlength="4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Review & Submit -->
            <div class="col-lg-5">
                <div class="card border-0 rounded-2xl shadow-sm p-4 bg-white sticky-top" style="top: 100px;">
                    <h5 class="fw-bold text-slate-900 mb-3 text-base">Order Review ({{ count($items) }} items)</h5>

                    <!-- Mini Cart List -->
                    <div class="space-y-3 mb-4 max-h-60 overflow-y-auto pe-1">
                        @foreach($items as $item)
                            <div class="d-flex align-items-center justify-content-between gap-3 text-sm">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}" class="rounded-lg object-cover" style="width: 45px; height: 45px;">
                                    <div>
                                        <div class="fw-semibold text-slate-800 line-clamp-1 text-xs">{{ $item['name'] }}</div>
                                        <div class="text-xs text-slate-400">Qty: {{ $item['quantity'] }} @ {{ $currency }}{{ number_format($item['price'], 2) }}</div>
                                    </div>
                                </div>
                                <div class="font-mono font-bold text-slate-900 text-xs">
                                    {{ $currency }}{{ number_format($item['total'], 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="border-slate-200 my-3">

                    <!-- Breakdown -->
                    <div class="space-y-2 text-sm">
                        <div class="d-flex justify-content-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-mono fw-semibold">{{ $currency }}{{ number_format($subtotal, 2) }}</span>
                        </div>

                        @if($discount > 0)
                        <div class="d-flex justify-content-between text-emerald-600">
                            <span>Coupon Discount ({{ $coupon['code'] ?? '' }})</span>
                            <span class="font-mono fw-semibold">- {{ $currency }}{{ number_format($discount, 2) }}</span>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between text-slate-600">
                            <span>Shipping & Delivery</span>
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
                            <span class="fs-6 fw-bold text-slate-900">Grand Total</span>
                            <span class="fs-3 fw-extrabold text-blue-600 font-mono">{{ $currency }}{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-brand-primary w-100 rounded-xl py-3 fw-bold mt-4 shadow-lg d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check2-circle fs-5"></i>
                        <span>Place Order Now</span>
                    </button>

                    <div class="mt-3 text-center text-xs text-slate-400">
                        By placing your order you agree to our terms of service and delivery policy.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
