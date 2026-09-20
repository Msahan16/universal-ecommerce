@extends('layouts.admin')

@section('page_title', 'Coupons & Discounts')
@section('page_subtitle', 'Create promotional discount codes and special offer campaigns')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<div class="row g-4">
    <!-- Add Coupon Form -->
    <div class="col-lg-4">
        <div class="admin-card p-4">
            <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Create Coupon</h6>
            
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Coupon Code *</label>
                    <input type="text" name="code" class="form-control rounded-xl border-slate-200 font-mono text-sm uppercase" placeholder="e.g. MEGA2026, WELCOME10" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Discount Type *</label>
                    <select name="type" class="form-select rounded-xl border-slate-200 text-sm">
                        <option value="percentage">Percentage Discount (%)</option>
                        <option value="fixed">Fixed Amount ({{ $currency }})</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Discount Value *</label>
                    <input type="number" step="0.01" name="value" class="form-control rounded-xl border-slate-200 font-mono" placeholder="10 or 1000" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Minimum Order Spend ({{ $currency }})</label>
                    <input type="number" step="0.01" name="min_spend" class="form-control rounded-xl border-slate-200 font-mono" placeholder="0.00">
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Max Discount Limit (Optional)</label>
                    <input type="number" step="0.01" name="max_discount" class="form-control rounded-xl border-slate-200 font-mono" placeholder="5000.00">
                </div>

                <div class="mb-4">
                    <label class="form-label text-xs fw-bold text-slate-700">Usage Limit (Max Uses)</label>
                    <input type="number" name="usage_limit" class="form-control rounded-xl border-slate-200 font-mono" placeholder="500">
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold text-xs shadow-lg">
                    Create Coupon Code
                </button>
            </form>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="col-lg-8">
        <div class="admin-card overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0 text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                        <tr>
                            <th class="ps-4 py-3">Code</th>
                            <th class="py-3">Discount</th>
                            <th class="py-3">Min Spend</th>
                            <th class="py-3">Used</th>
                            <th class="pe-4 py-3 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="badge bg-slate-900 text-white font-mono fs-6 px-3 py-1.5 rounded-lg">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td class="py-3 fw-bold text-slate-900 font-mono">
                                {{ $coupon->type === 'percentage' ? $coupon->value . '%' : $currency . number_format($coupon->value, 2) }}
                            </td>
                            <td class="py-3 font-mono text-xs text-slate-600">
                                {{ $currency }}{{ number_format($coupon->min_spend, 2) }}
                            </td>
                            <td class="py-3 font-mono text-xs text-slate-600">
                                {{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Delete coupon?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-slate-400">No active coupons found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
