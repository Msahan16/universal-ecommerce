@extends('layouts.store')

@section('content')

<!-- Breadcrumbs -->
<div class="bg-slate-900 text-white py-4 mb-4">
    <div class="container">
        <h1 class="fs-3 fw-extrabold mb-1">Track Custom Quotation</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 text-xs text-slate-400">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-slate-400 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Track Quotation</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="card border-0 rounded-3xl shadow-sm p-4 bg-white mb-4 mx-auto" style="max-width: 650px;">
        <h5 class="fw-bold text-slate-900 mb-2 text-center">Look Up Quotation Status</h5>
        <p class="text-slate-500 text-xs text-center mb-4">Enter your quotation reference number (e.g., QUO-2026-XXXXXX) to view review progress or open your finalized quote.</p>

        <form action="{{ route('quotations.track') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="reference" value="{{ request('reference') }}" class="form-control form-control-lg rounded-pill border-slate-200 ps-4 font-mono text-sm uppercase" placeholder="QUO-2026-XXXXXX" required>
            <button type="submit" class="btn btn-brand-primary rounded-pill px-4 fw-bold text-sm">
                Search
            </button>
        </form>
    </div>

    @if($quotation)
        <div class="text-center">
            <a href="{{ route('quotations.show', $quotation->reference_number) }}" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold text-sm">
                Open Quotation #{{ $quotation->reference_number }} Details <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    @elseif(request('reference'))
        <div class="card border-0 rounded-3xl shadow-sm p-4 text-center bg-white mx-auto" style="max-width: 550px;">
            <p class="text-rose-600 mb-0 font-bold text-sm">Quotation reference not found. Please check your reference code.</p>
        </div>
    @endif
</div>

@endsection
