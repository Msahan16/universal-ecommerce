@extends('layouts.admin')

@section('page_title', 'Customer Quotation Requests')
@section('page_subtitle', 'Review customer requests, calculate specifications, and generate official quotes')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<!-- Filter Tabs -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.quotations.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-light border' }} rounded-pill px-3 text-xs fw-semibold">
            All Requests
        </a>
        <a href="{{ route('admin.quotations.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-light border' }} rounded-pill px-3 text-xs fw-semibold">
            Pending Review
        </a>
        <a href="{{ route('admin.quotations.index', ['status' => 'quoted']) }}" class="btn btn-sm {{ request('status') === 'quoted' ? 'btn-primary' : 'btn-light border' }} rounded-pill px-3 text-xs fw-semibold">
            Quoted
        </a>
        <a href="{{ route('admin.quotations.index', ['status' => 'accepted']) }}" class="btn btn-sm {{ request('status') === 'accepted' ? 'btn-success' : 'btn-light border' }} rounded-pill px-3 text-xs fw-semibold">
            Accepted
        </a>
    </div>

    <form action="{{ route('admin.quotations.index') }}" method="GET" class="d-flex gap-2" style="max-width: 320px;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm rounded-lg border-slate-200 text-xs font-mono" placeholder="Ref # or Customer...">
        <button type="submit" class="btn btn-dark btn-sm rounded-lg px-3 text-xs">Search</button>
    </form>
</div>

<!-- Quotations Table -->
<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0 text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                <tr>
                    <th class="ps-4 py-3">Reference #</th>
                    <th class="py-3">Customer</th>
                    <th class="py-3">Subject / Project</th>
                    <th class="py-3">Date</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Quoted Amount</th>
                    <th class="pe-4 py-3 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $q)
                <tr>
                    <td class="ps-4 py-3 font-mono font-bold text-slate-900">
                        {{ $q->reference_number }}
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-slate-900 text-xs">{{ $q->customer_name }}</div>
                        <small class="text-slate-500 text-xs">{{ $q->customer_phone }}</small>
                    </td>
                    <td class="py-3 text-xs font-semibold text-slate-800">
                        <div class="line-clamp-1" style="max-width: 250px;">{{ $q->subject }}</div>
                        @if($q->attachment)
                            <span class="badge bg-slate-100 text-slate-600 text-xs"><i class="bi bi-paperclip"></i> Has File</span>
                        @endif
                    </td>
                    <td class="py-3 text-xs text-slate-500">
                        {{ $q->created_at->format('M d, Y') }}
                    </td>
                    <td class="py-3">
                        <span class="badge bg-{{ $q->status_badge }} text-white text-xs px-2.5 py-1 rounded-pill uppercase">
                            {{ $q->status }}
                        </span>
                    </td>
                    <td class="py-3 font-mono font-bold text-slate-900">
                        @if($q->total > 0)
                            {{ $currency }}{{ number_format($q->total, 2) }}
                        @else
                            <span class="text-slate-400 font-normal">--</span>
                        @endif
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <a href="{{ route('admin.quotations.show', $q->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 text-xs fw-semibold">
                            Generate Quote
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-slate-400">No quotation requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex justify-content-center">
        {{ $quotations->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
