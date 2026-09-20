@extends('layouts.admin')

@section('page_title', 'Edit Product')
@section('page_subtitle', 'Update specifications, pricing, and stock for ' . $product->name)

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">General Information</h6>
                
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control rounded-xl border-slate-200" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Short Summary</label>
                    <textarea name="short_description" rows="2" class="form-control rounded-xl border-slate-200 text-sm">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Full Description & Details</label>
                    <textarea name="description" rows="5" class="form-control rounded-xl border-slate-200 text-sm">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <!-- Pricing & Inventory -->
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Pricing & Stock Control</h6>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-xs fw-bold text-slate-700">Sale Price ({{ $currency }}) *</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control rounded-xl border-slate-200" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-xs fw-bold text-slate-700">Compare Price</label>
                        <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}" class="form-control rounded-xl border-slate-200">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-xs fw-bold text-slate-700">Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control rounded-xl border-slate-200" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Organization</h6>
                
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Category</label>
                    <select name="category_id" class="form-select rounded-xl border-slate-200 text-sm">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Brand</label>
                    <select name="brand_id" class="form-select rounded-xl border-slate-200 text-sm">
                        <option value="">-- Select Brand --</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control rounded-xl border-slate-200 font-mono text-sm">
                </div>
            </div>

            <!-- Media -->
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Product Media</h6>
                
                @if($product->thumbnail)
                    <div class="mb-3 text-center">
                        <img src="{{ $product->thumbnail_url }}" alt="Preview" class="rounded-xl border object-cover mx-auto" style="height: 120px; width: 120px;">
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Image Web URL</label>
                    <input type="url" name="thumbnail" value="{{ old('thumbnail', $product->thumbnail) }}" class="form-control rounded-xl border-slate-200 text-sm">
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Upload New File</label>
                    <input type="file" name="thumbnail_file" class="form-control rounded-xl border-slate-200 text-sm" accept="image/*">
                </div>
            </div>

            <!-- Publish Settings -->
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Publish Status</h6>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" {{ $product->is_active ? 'checked' : '' }}>
                    <label class="form-check-label text-xs fw-bold text-slate-800" for="activeSwitch">Active in Storefront</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="featuredSwitch" {{ $product->is_featured ? 'checked' : '' }}>
                    <label class="form-check-label text-xs fw-bold text-slate-800" for="featuredSwitch">Featured on Homepage</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill py-2.5 px-4 text-xs fw-bold">Cancel</a>
                <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill py-2.5 fw-bold text-xs shadow-lg">Save Changes</button>
            </div>
        </div>
    </div>
</form>

@endsection
