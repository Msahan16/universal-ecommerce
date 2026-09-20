@extends('layouts.store')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<!-- Breadcrumbs -->
<div class="bg-slate-900 text-white py-4 mb-4">
    <div class="container">
        <h1 class="fs-3 fw-extrabold mb-1">Quotation #{{ $quotation->reference_number }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 text-xs text-slate-400">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-slate-400 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Quotation Details</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="card border-0 rounded-3xl shadow-sm bg-white p-4 p-md-5 mx-auto" style="max-width: 840px;">
        <!-- Header & Status -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <div>
                <span class="badge bg-{{ $quotation->status_badge }} text-white px-3 py-1 rounded-pill text-xs font-bold uppercase mb-2">
                    Status: {{ strtoupper($quotation->status) }}
                </span>
                <h3 class="fs-4 fw-extrabold text-slate-900 mb-0">{{ $quotation->subject }}</h3>
                <small class="text-slate-400 text-xs">Submitted on {{ $quotation->created_at->format('M d, Y h:i A') }}</small>
            </div>
            <div class="text-md-end mt-2 mt-md-0">
                <span class="font-mono font-bold text-slate-700 d-block fs-5">{{ $quotation->reference_number }}</span>
                <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 mt-1 text-xs" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print / Save PDF
                </button>
            </div>
        </div>

        <!-- Customer Requirement Details -->
        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 mb-4 text-xs">
            <strong class="text-slate-900 d-block mb-1 font-bold uppercase tracking-wider">Submitted Requirements:</strong>
            <p class="text-slate-700 mb-2 leading-relaxed whitespace-pre-line">{{ $quotation->requirements }}</p>
            <div class="d-flex flex-wrap gap-4 text-slate-500 pt-2 border-top">
                <span><strong>Customer:</strong> {{ $quotation->customer_name }}</span>
                <span><strong>Phone:</strong> {{ $quotation->customer_phone }}</span>
                <span><strong>Email:</strong> {{ $quotation->customer_email }}</span>
                @if($quotation->attachment)
                    <a href="{{ asset('storage/' . $quotation->attachment) }}" target="_blank" class="text-blue-600 font-bold text-decoration-none">
                        <i class="bi bi-paperclip"></i> View Attached Blueprint / Document
                    </a>
                @endif
            </div>
        </div>

        @if($quotation->status === 'pending' || $quotation->status === 'reviewed')
            <!-- Pending Quotation Notice -->
            <div class="alert alert-info rounded-2xl p-4 text-center my-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 d-flex align-items-center justify-content-center mx-auto mb-2 fs-4">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h5 class="fw-bold text-slate-900 mb-1">Quote In Preparation</h5>
                <p class="text-xs text-slate-600 mb-0">Our technical estimators are currently calculating the customized pricing, materials, and delivery schedule. Once published, the itemized quote will appear right here.</p>
            </div>
        @elseif($quotation->status === 'quoted' || $quotation->status === 'accepted')
            <!-- Official Ready Quotation -->
            <div class="mb-4">
                <h5 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Official Quotation Breakdown</h5>

                <div class="border rounded-2xl overflow-hidden bg-white mb-3">
                    <table class="table align-middle mb-0 text-sm">
                        <thead class="bg-slate-900 text-white text-xs uppercase">
                            <tr>
                                <th class="ps-4 py-3">Description / Specification</th>
                                <th class="py-3 text-center">Qty</th>
                                <th class="py-3 text-end">Unit Price</th>
                                <th class="pe-4 py-3 text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotation->items as $item)
                            <tr>
                                <td class="ps-4 py-3">
                                    <strong class="text-slate-900 d-block">{{ $item->item_name }}</strong>
                                    @if($item->specifications)
                                        <span class="text-slate-500 text-xs">{{ $item->specifications }}</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center font-semibold text-slate-700">{{ $item->quantity }}</td>
                                <td class="py-3 text-end font-mono text-xs">{{ $currency }}{{ number_format($item->unit_price, 2) }}</td>
                                <td class="pe-4 py-3 text-end font-mono font-bold text-slate-900">{{ $currency }}{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Financial Breakdown -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-slate-50 rounded-2xl border text-xs space-y-1">
                            <div><strong>Estimated Delivery:</strong> {{ $quotation->estimated_days ?? '7-14' }} business days</div>
                            @if($quotation->valid_until)
                                <div><strong>Quote Valid Until:</strong> {{ $quotation->valid_until->format('M d, Y') }}</div>
                            @endif
                            @if($quotation->admin_notes)
                                <div class="mt-2 pt-2 border-top text-slate-600">
                                    <strong>Terms & Remarks:</strong>
                                    <div>{{ $quotation->admin_notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-slate-50 rounded-2xl border text-sm space-y-2">
                            <div class="d-flex justify-content-between text-slate-600">
                                <span>Subtotal</span>
                                <span class="font-mono">{{ $currency }}{{ number_format($quotation->subtotal, 2) }}</span>
                            </div>
                            @if($quotation->tax > 0)
                            <div class="d-flex justify-content-between text-slate-600">
                                <span>Taxes / Handling</span>
                                <span class="font-mono">{{ $currency }}{{ number_format($quotation->tax, 2) }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between pt-2 border-top font-bold text-slate-900 fs-5">
                                <span>Quoted Total</span>
                                <span class="font-mono text-blue-600">{{ $currency }}{{ number_format($quotation->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button for Customer -->
                @if($quotation->status === 'quoted')
                    <div class="mt-4 pt-3 border-top text-center">
                        <form action="{{ route('quotations.accept', $quotation->reference_number) }}" method="POST" onsubmit="return confirm('Accept this quotation and generate an official order?');">
                            @csrf
                            <button type="submit" class="btn btn-emerald-600 bg-emerald-600 text-white btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg d-inline-flex align-items-center gap-2">
                                <i class="bi bi-check2-circle fs-5"></i>
                                <span>Accept Quotation & Place Order</span>
                            </button>
                        </form>
                        <small class="text-slate-400 d-block mt-2 text-xs">Our operations team will commence fabrication / preparation upon acceptance.</small>
                    </div>
                @elseif($quotation->status === 'accepted')
                    <div class="alert alert-success rounded-2xl p-3 text-center mt-4">
                        <i class="bi bi-check-circle-fill me-1 text-success"></i> You have accepted this quotation and your order has been placed!
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@endsection
