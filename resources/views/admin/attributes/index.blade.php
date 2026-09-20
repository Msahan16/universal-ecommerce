@extends('layouts.admin')

@section('page_title', 'Dynamic Attribute Engine')
@section('page_subtitle', 'Create industry-specific product specifications, colors, and variant options')

@section('content')

<div class="row g-4">
    <!-- Create New Attribute -->
    <div class="col-lg-4">
        <div class="admin-card p-4 mb-4">
            <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Create Attribute Type</h6>
            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Attribute Name *</label>
                    <input type="text" name="name" class="form-control rounded-xl border-slate-200" placeholder="e.g. Glass Type, Profile Thickness, Vehicle Model, Size" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Input Type</label>
                    <select name="type" class="form-select rounded-xl border-slate-200 text-sm">
                        <option value="select">Dropdown Select (Predefined options)</option>
                        <option value="color">Color Swatch / Code</option>
                        <option value="text">Open Text Specification</option>
                        <option value="number">Numeric Dimension / Measurement</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold text-xs shadow-lg">
                    Create Attribute
                </button>
            </form>
        </div>

        <!-- System Architecture Information -->
        <div class="admin-card p-4 bg-blue-50 border-blue-100 text-xs text-blue-900">
            <strong class="d-block mb-1 font-bold">💡 Multi-Industry Dynamic Engine</strong>
            <p class="mb-0">Attributes created here are instantly available when creating or editing any product in the store, without touching database code.</p>
        </div>
    </div>

    <!-- Attributes & Preset Values List -->
    <div class="col-lg-8">
        <div class="space-y-4">
            @forelse($attributes as $attr)
                <div class="admin-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-slate-900 text-white font-mono text-xs uppercase px-2.5 py-1 rounded-md">{{ $attr->type }}</span>
                            <h5 class="fw-bold text-slate-900 mb-0 fs-6">{{ $attr->name }}</h5>
                            <span class="text-xs text-slate-400 font-mono">({{ $attr->slug }})</span>
                        </div>

                        <form action="{{ route('admin.attributes.destroy', $attr->id) }}" method="POST" onsubmit="return confirm('Delete this attribute?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-link text-rose-500 hover:text-rose-700 p-0 text-xs text-decoration-none">
                                <i class="bi bi-trash3 me-1"></i> Remove
                            </button>
                        </form>
                    </div>

                    <!-- Existing Values -->
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-slate-500 uppercase tracking-wider d-block">Predefined Options / Values:</label>
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($attr->values as $val)
                                <div class="d-flex align-items-center gap-2 px-3 py-1 bg-slate-100 rounded-lg border text-xs font-semibold text-slate-800">
                                    @if($val->color_code)
                                        <span class="rounded-circle border" style="width: 14px; height: 14px; background-color: {{ $val->color_code }};"></span>
                                    @endif
                                    <span>{{ $val->value }}</span>
                                    <form action="{{ route('admin.attributes.values.destroy', $val->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 text-slate-400 hover:text-rose-600 leading-none">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <span class="text-slate-400 text-xs italic">No preset options. Open value text field will be used.</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Add Value to this Attribute -->
                    <form action="{{ route('admin.attributes.values.store', $attr->id) }}" method="POST" class="d-flex gap-2 pt-2 border-top">
                        @csrf
                        <input type="text" name="value" class="form-control form-control-sm rounded-lg border-slate-200 text-xs flex-grow-1" placeholder="Add new option (e.g. 1.5mm, Matte White, 2025)..." required>
                        @if($attr->type === 'color')
                            <input type="color" name="color_code" class="form-control form-control-color form-control-sm rounded-lg" value="#000000" title="Pick color swatch" style="width: 42px;">
                        @endif
                        <button type="submit" class="btn btn-dark btn-sm rounded-lg px-3 text-xs fw-semibold">
                            + Add
                        </button>
                    </form>
                </div>
            @empty
                <div class="admin-card p-5 text-center text-slate-400">
                    No custom attributes created yet.
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
