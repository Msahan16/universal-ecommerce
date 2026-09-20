@extends('layouts.store')

@section('content')

<!-- Breadcrumbs -->
<div class="bg-slate-900 text-white py-4 mb-4">
    <div class="container">
        <h1 class="fs-3 fw-extrabold mb-1">Request a Custom Quotation</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 text-xs text-slate-400">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-slate-400 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Custom Quotation</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="card border-0 rounded-3xl shadow-sm bg-white p-4 p-md-5 mx-auto" style="max-width: 780px;">
        <div class="mb-4">
            <span class="badge bg-blue-100 text-blue-700 px-3 py-1 rounded-pill text-xs font-bold uppercase mb-2">Custom Sizing & Bulk Orders</span>
            <h2 class="fs-4 fw-extrabold text-slate-900 tracking-tight mb-2">Tell Us What You Need</h2>
            <p class="text-slate-500 text-xs mb-0">Whether you need custom aluminium fabrication, precision machinery parts, or bulk packages, submit your specifications below. Our engineering team will review your requirements and generate an official quote directly to your account.</p>
        </div>

        <form action="{{ route('quotations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Contact Details -->
            <div class="row g-3 mb-4 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="col-12">
                    <strong class="text-slate-800 text-xs uppercase tracking-wider d-block">Your Contact Information</strong>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-xs fw-bold text-slate-700">Your Full Name *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', $user->name ?? '') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="e.g. Kasun Perera" required>
                    @error('customer_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label text-xs fw-bold text-slate-700">Email Address *</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email', $user->email ?? '') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="name@example.com" required>
                    @error('customer_email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label text-xs fw-bold text-slate-700">Mobile Phone *</label>
                    <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="077 123 4567" required>
                    @error('customer_phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Quotation Details -->
            <div class="mb-3">
                <label class="form-label text-xs fw-bold text-slate-700">Project / Item Requirement Title *</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="e.g. 3-Track Sliding Doors for Villa / Brake Caliper Batch Order" required>
                @error('subject') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label text-xs fw-bold text-slate-700">Detailed Measurements & Specifications *</label>
                <textarea name="requirements" rows="5" class="form-control rounded-xl border-slate-200 text-sm" placeholder="Please specify dimensions (Width x Height), color profile, glass thickness, material grades, vehicle chassis numbers, or total quantity needed..." required>{{ old('requirements') }}</textarea>
                @error('requirements') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label text-xs fw-bold text-slate-700">Upload Blueprint / Sketch / Spec Document (Optional)</label>
                <input type="file" name="attachment_file" class="form-control rounded-xl border-slate-200 text-sm" accept=".pdf,.jpg,.jpeg,.png,.zip,.doc,.docx,.dwg">
                <small class="text-slate-400 text-xs mt-1 d-block">Supported formats: PDF, Images, CAD/DWG, Word, ZIP (Max: 10MB)</small>
            </div>

            <button type="submit" class="btn btn-brand-primary w-100 rounded-pill py-3 fw-bold shadow-lg d-flex align-items-center justify-content-center gap-2 text-sm">
                <i class="bi bi-send-fill"></i>
                <span>Submit Quotation Request</span>
            </button>
        </form>
    </div>
</div>

@endsection
