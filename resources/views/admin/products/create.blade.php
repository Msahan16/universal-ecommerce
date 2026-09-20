@extends('layouts.admin')

@section('page_title', 'Add New Product')
@section('page_subtitle', 'Create a new catalog item with pricing and dynamic specifications')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <!-- General Information -->
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">General Information</h6>
                
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-xl border-slate-200" placeholder="e.g. 70mm Sliding Window System" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Short Summary</label>
                    <textarea name="short_description" rows="2" class="form-control rounded-xl border-slate-200 text-sm" placeholder="Brief summary for listings and search cards...">{{ old('short_description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Full Description & Details</label>
                    <textarea name="description" rows="5" class="form-control rounded-xl border-slate-200 text-sm" placeholder="Detailed technical description, composition, instructions...">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Dynamic Attributes (Industry Universal Engine) -->
            <div class="admin-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold text-slate-900 mb-0 text-sm uppercase tracking-wider">Dynamic Specifications & Attributes</h6>
                        <small class="text-slate-500 text-xs">Assign custom values for aluminium, spare parts, clothing, or hardware.</small>
                    </div>
                    <a href="{{ route('admin.attributes.index') }}" target="_blank" class="text-xs text-blue-600 font-semibold text-decoration-none">
                        + Manage Attribute Types
                    </a>
                </div>

                <div class="row g-3">
                    @foreach($attributes as $attr)
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-slate-700">{{ $attr->name }}</label>
                            
                            @if($attr->values->count() > 0)
                                <select name="attributes[{{ $attr->id }}][value_id]" class="form-select form-select-sm rounded-lg border-slate-200 text-xs">
                                    <option value="">-- Not Assigned --</option>
                                    @foreach($attr->values as $val)
                                        <option value="{{ $val->id }}">{{ $val->value }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" name="attributes[{{ $attr->id }}][custom]" class="form-control form-control-sm rounded-lg border-slate-200 text-xs" placeholder="e.g. Custom {{ $attr->name }}...">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pricing & Inventory -->
            <div class="admin-card p-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Pricing & Stock Control</h6>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-xs fw-bold text-slate-700">Sale Price ({{ $currency }}) *</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-control rounded-xl border-slate-200" placeholder="25000.00" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-xs fw-bold text-slate-700">Compare Price (Original / Discounted)</label>
                        <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price') }}" class="form-control rounded-xl border-slate-200" placeholder="30000.00">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-xs fw-bold text-slate-700">Initial Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', 10) }}" class="form-control rounded-xl border-slate-200" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Organization / Taxonomy -->
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Organization</h6>
                
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Category</label>
                    <select name="category_id" class="form-select rounded-xl border-slate-200 text-sm">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Brand / Manufacturer</label>
                    <select name="brand_id" class="form-select rounded-xl border-slate-200 text-sm">
                        <option value="">-- Select Brand --</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">SKU / Item Code</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" class="form-control rounded-xl border-slate-200 font-mono text-sm" placeholder="e.g. WIN-SLD-7001">
                </div>
            </div>

            <!-- Media / Images -->
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Product Media</h6>
                
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Image Web URL (or leave blank to upload)</label>
                    <input type="url" name="thumbnail" value="{{ old('thumbnail') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="https://images.unsplash.com/...">
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Upload Image File</label>
                    <input type="file" name="thumbnail_file" class="form-control rounded-xl border-slate-200 text-sm" accept="image/*">
                </div>
            </div>

            <!-- Visibility Settings -->
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Publish Status</h6>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" checked>
                    <label class="form-check-label text-xs fw-bold text-slate-800" for="activeSwitch">Active in Storefront</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="featuredSwitch">
                    <label class="form-check-label text-xs fw-bold text-slate-800" for="featuredSwitch">Featured on Homepage</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-lg">
                Save & Publish Product
            </button>
        </div>
    </div>
</form>

@endsection
