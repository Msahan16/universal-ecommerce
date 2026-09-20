@extends('layouts.admin')

@section('page_title', 'Brands & Manufacturers')
@section('page_subtitle', 'Manage partner brands, suppliers, and logos')

@section('content')

<div class="row g-4">
    <!-- Add Brand Form -->
    <div class="col-lg-4">
        <div class="admin-card p-4">
            <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Add New Brand</h6>
            <form action="{{ route('admin.brands.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Brand Name *</label>
                    <input type="text" name="name" class="form-control rounded-xl border-slate-200" placeholder="e.g. Alumex, Bosch, Swisstek" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Description</label>
                    <textarea name="description" rows="2" class="form-control rounded-xl border-slate-200 text-sm" placeholder="Manufacturer background..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold text-xs shadow-lg">
                    Add Brand
                </button>
            </form>
        </div>
    </div>

    <!-- Brands Table -->
    <div class="col-lg-8">
        <div class="admin-card overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0 text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                        <tr>
                            <th class="ps-4 py-3">Brand Name</th>
                            <th class="py-3">Slug</th>
                            <th class="py-3">Products</th>
                            <th class="pe-4 py-3 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                        <tr>
                            <td class="ps-4 py-3 fw-bold text-slate-900">
                                {{ $brand->name }}
                            </td>
                            <td class="py-3 font-mono text-xs text-slate-500">
                                {{ $brand->slug }}
                            </td>
                            <td class="py-3 font-mono fw-bold text-slate-700">
                                {{ $brand->products_count }} products
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Delete this brand?');" class="d-inline">
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
                            <td colspan="4" class="text-center py-4 text-slate-400">No brands found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
