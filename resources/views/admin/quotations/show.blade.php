@extends('layouts.admin')

@section('page_title', 'Quotation: ' . $quotation->reference_number)
@section('page_subtitle', 'Review customer requirements and generate official line-item quotation')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<div class="row g-4">
    <!-- Left Column: Customer Request & Requirements -->
    <div class="col-lg-5">
        <div class="admin-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h6 class="fw-bold text-slate-900 mb-0 text-sm uppercase tracking-wider">Customer Inquiry</h6>
                <span class="badge bg-{{ $quotation->status_badge }} text-white text-xs px-2.5 py-1 rounded-pill uppercase">
                    {{ $quotation->status }}
                </span>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-slate-400 d-block font-semibold">Subject / Project Title:</span>
                    <strong class="text-slate-900 fs-6">{{ $quotation->subject }}</strong>
                </div>

                <div>
                    <span class="text-slate-400 d-block font-semibold">Customer:</span>
                    <strong class="text-slate-800">{{ $quotation->customer_name }}</strong>
                </div>

                <div>
                    <span class="text-slate-400 d-block font-semibold">Contact:</span>
                    <div>{{ $quotation->customer_phone }} • {{ $quotation->customer_email }}</div>
                </div>

                <div class="pt-2 border-top">
                    <span class="text-slate-400 d-block font-semibold mb-1">Detailed Requirements & Specifications:</span>
                    <div class="p-3 bg-slate-50 rounded-xl border text-slate-800 leading-relaxed whitespace-pre-line">
                        {{ $quotation->requirements }}
                    </div>
                </div>

                @if($quotation->attachment)
                <div class="pt-2 border-top">
                    <span class="text-slate-400 d-block font-semibold mb-1">Uploaded Blueprint / Attachment:</span>
                    <a href="{{ asset('storage/' . $quotation->attachment) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-xl px-3 text-xs fw-bold">
                        <i class="bi bi-download me-1"></i> Download Attached File
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Status Update -->
        <div class="admin-card p-4">
            <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Status Override</h6>
            <form action="{{ route('admin.quotations.status', $quotation->id) }}" method="POST">
                @csrf
                <div class="d-flex gap-2">
                    <select name="status" class="form-select form-select-sm rounded-lg text-xs">
                        <option value="pending" {{ $quotation->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="reviewed" {{ $quotation->status === 'reviewed' ? 'selected' : '' }}>Under Review</option>
                        <option value="quoted" {{ $quotation->status === 'quoted' ? 'selected' : '' }}>Quoted / Sent</option>
                        <option value="accepted" {{ $quotation->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ $quotation->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button type="submit" class="btn btn-dark btn-sm rounded-lg text-xs px-3">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Official Quotation Builder -->
    <div class="col-lg-7">
        <div class="admin-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-slate-900 mb-0 text-sm uppercase tracking-wider">
                    <i class="bi bi-calculator text-blue-600 me-1"></i> Generate / Edit Official Quote
                </h6>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-xs fw-bold" onclick="addQuoteItem()">
                    + Add Item Line
                </button>
            </div>

            <form action="{{ route('admin.quotations.generate', $quotation->id) }}" method="POST" id="quotationForm">
                @csrf

                <!-- Line Items Container -->
                <div id="quoteItemsContainer" class="space-y-3 mb-4">
                    @if($quotation->items->count() > 0)
                        @foreach($quotation->items as $index => $item)
                            <div class="quote-row p-3 bg-slate-50 rounded-2xl border border-slate-200">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-xs text-slate-700">Item Line #{{ $index + 1 }}</strong>
                                    <button type="button" class="btn btn-sm text-danger p-0 text-xs" onclick="this.closest('.quote-row').remove(); calculateTotals();">
                                        <i class="bi bi-trash3"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-7">
                                        <input type="text" name="items[{{ $index }}][item_name]" value="{{ $item->item_name }}" class="form-control form-control-sm rounded-lg border-slate-200 text-xs fw-bold" placeholder="Item Name / Profile / Part..." required>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="items[{{ $index }}][specifications]" value="{{ $item->specifications }}" class="form-control form-control-sm rounded-lg border-slate-200 text-xs" placeholder="Dimensions / Finish / Specs...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-xs text-slate-400">Qty</label>
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="form-control form-control-sm rounded-lg border-slate-200 text-xs item-qty" min="1" required onchange="calculateTotals()">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="text-xs text-slate-400">Unit Price ({{ $currency }})</label>
                                        <input type="number" step="0.01" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" class="form-control form-control-sm rounded-lg border-slate-200 text-xs item-price font-mono" required onchange="calculateTotals()">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Default Initial Row -->
                        <div class="quote-row p-3 bg-slate-50 rounded-2xl border border-slate-200">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-xs text-slate-700">Item Line #1</strong>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-7">
                                    <input type="text" name="items[0][item_name]" class="form-control form-control-sm rounded-lg border-slate-200 text-xs fw-bold" placeholder="e.g. 70mm Sliding Window System 2-Track" required>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="items[0][specifications]" class="form-control form-control-sm rounded-lg border-slate-200 text-xs" placeholder="e.g. 1500mm x 1200mm, 8mm Clear, Matte Black">
                                </div>
                                <div class="col-md-4">
                                    <label class="text-xs text-slate-400">Qty</label>
                                    <input type="number" name="items[0][quantity]" value="1" class="form-control form-control-sm rounded-lg border-slate-200 text-xs item-qty" min="1" required onchange="calculateTotals()">
                                </div>
                                <div class="col-md-8">
                                    <label class="text-xs text-slate-400">Unit Price ({{ $currency }})</label>
                                    <input type="number" step="0.01" name="items[0][unit_price]" value="0" class="form-control form-control-sm rounded-lg border-slate-200 text-xs item-price font-mono" required onchange="calculateTotals()">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Parameters (Delivery, Validity, Tax) -->
                <div class="row g-3 mb-4 p-3 bg-slate-100 rounded-2xl border border-slate-200">
                    <div class="col-md-6">
                        <label class="form-label text-xs fw-bold text-slate-700">Estimated Delivery Time (Business Days) *</label>
                        <input type="number" name="estimated_days" value="{{ old('estimated_days', $quotation->estimated_days ?? 7) }}" class="form-control form-control-sm rounded-lg border-slate-200" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-xs fw-bold text-slate-700">Quotation Valid Until *</label>
                        <input type="date" name="valid_until" value="{{ old('valid_until', $quotation->valid_until ? $quotation->valid_until->format('Y-m-d') : now()->addDays(14)->format('Y-m-d')) }}" class="form-control form-control-sm rounded-lg border-slate-200" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-xs fw-bold text-slate-700">Taxes / Handling / Delivery Fee ({{ $currency }})</label>
                        <input type="number" step="0.01" name="tax" id="taxInput" value="{{ old('tax', $quotation->tax ?? 0) }}" class="form-control form-control-sm rounded-lg border-slate-200 font-mono" onchange="calculateTotals()">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-xs fw-bold text-slate-700">Calculated Total</label>
                        <div class="fs-5 font-bold text-blue-600 font-mono pt-1" id="totalPreview">
                            {{ $currency }}{{ number_format($quotation->total, 2) }}
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-xs fw-bold text-slate-700">Special Terms, Notes & Warranty Remarks</label>
                        <textarea name="admin_notes" rows="2" class="form-control rounded-xl border-slate-200 text-xs" placeholder="e.g. 50% advance upon confirmation, 10-year structural warranty included.">{{ old('admin_notes', $quotation->admin_notes) }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-lg d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-send-check-fill"></i>
                    <span>Generate & Send Official Quote to Customer</span>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemIndex = {{ max(1, $quotation->items->count()) }};

    function addQuoteItem() {
        const container = document.getElementById('quoteItemsContainer');
        const row = document.createElement('div');
        row.className = 'quote-row p-3 bg-slate-50 rounded-2xl border border-slate-200';
        row.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong class="text-xs text-slate-700">Item Line #${itemIndex + 1}</strong>
                <button type="button" class="btn btn-sm text-danger p-0 text-xs" onclick="this.closest('.quote-row').remove(); calculateTotals();">
                    <i class="bi bi-trash3"></i> Remove
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-7">
                    <input type="text" name="items[${itemIndex}][item_name]" class="form-control form-control-sm rounded-lg border-slate-200 text-xs fw-bold" placeholder="Item Name..." required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="items[${itemIndex}][specifications]" class="form-control form-control-sm rounded-lg border-slate-200 text-xs" placeholder="Specs / Details...">
                </div>
                <div class="col-md-4">
                    <label class="text-xs text-slate-400">Qty</label>
                    <input type="number" name="items[${itemIndex}][quantity]" value="1" class="form-control form-control-sm rounded-lg border-slate-200 text-xs item-qty" min="1" required onchange="calculateTotals()">
                </div>
                <div class="col-md-8">
                    <label class="text-xs text-slate-400">Unit Price ({{ $currency }})</label>
                    <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" value="0" class="form-control form-control-sm rounded-lg border-slate-200 text-xs item-price font-mono" required onchange="calculateTotals()">
                </div>
            </div>
        `;
        container.appendChild(row);
        itemIndex++;
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        const rows = document.querySelectorAll('.quote-row');
        rows.forEach(r => {
            const qty = parseFloat(r.querySelector('.item-qty')?.value || 0);
            const price = parseFloat(r.querySelector('.item-price')?.value || 0);
            subtotal += (qty * price);
        });
        const tax = parseFloat(document.getElementById('taxInput')?.value || 0);
        const total = subtotal + tax;
        document.getElementById('totalPreview').innerText = '{{ $currency }}' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
</script>
@endpush

@endsection
