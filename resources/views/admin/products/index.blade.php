@extends('layouts.admin')

@section('page_title', 'Product Catalog Management')
@section('page_subtitle', 'Manage all store inventory, pricing, and variants')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <!-- Filter Search Bar -->
    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex gap-2 flex-grow-1" style="max-width: 500px;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm rounded-lg border-slate-200" placeholder="Search by name or SKU...">
        <select name="category_id" class="form-select form-select-sm rounded-lg border-slate-200" style="max-width: 180px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-sm btn-dark rounded-lg px-3">Filter</button>
    </form>

    <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill btn-sm px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Add New Product
    </a>
</div>

<!-- Products Table -->
<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0 text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                <tr>
                    <th class="ps-4 py-3">Product</th>
                    <th class="py-3">Category</th>
                    <th class="py-3">Price</th>
                    <th class="py-3">Stock</th>
                    <th class="py-3">Status</th>
                    <th class="pe-4 py-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="rounded-xl object-cover border" style="width: 50px; height: 50px;">
                            <div>
                                <h6 class="fw-bold text-slate-900 mb-0 text-sm">{{ $product->name }}</h6>
                                <span class="font-mono text-xs text-slate-400">SKU: {{ $product->sku }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 text-xs text-slate-600">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </td>
                    <td class="py-3 font-mono font-bold text-slate-900">
                        {{ $currency }}{{ number_format($product->price, 2) }}
                    </td>
                    <td class="py-3">
                        @if($product->stock <= 0)
                            <span class="badge bg-rose-100 text-rose-800 font-mono">0 (Out)</span>
                        @elseif($product->stock <= 5)
                            <span class="badge bg-amber-100 text-amber-900 font-mono">{{ $product->stock }} (Low)</span>
                        @else
                            <span class="badge bg-emerald-100 text-emerald-800 font-mono">{{ $product->stock }} in stock</span>
                        @endif
                    </td>
                    <td class="py-3">
                        <span class="badge bg-{{ $product->is_active ? 'emerald-600' : 'slate-400' }} text-white text-xs rounded-pill">
                            {{ $product->is_active ? 'Active' : 'Draft' }}
                        </span>
                        @if($product->is_featured)
                            <span class="badge bg-blue-600 text-white text-xs rounded-pill">Featured</span>
                        @endif
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <div class="d-inline-flex gap-2">
                            <a href="{{ route('shop.show', $product->slug) }}" target="_blank" class="btn btn-sm btn-light border p-1 px-2" title="View on Store">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary p-1 px-2" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="Delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-slate-400">No products found. Add your first product to get started!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
