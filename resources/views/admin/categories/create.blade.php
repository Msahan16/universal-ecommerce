@extends('layouts.admin')

@section('page_title', 'Create Category')
@section('page_subtitle', 'Add a new catalog department')

@section('content')

<div class="admin-card p-4 mx-auto" style="max-width: 650px;">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label text-xs fw-bold text-slate-700">Category Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-xl border-slate-200" placeholder="e.g. Aluminium Profiles & Systems" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label text-xs fw-bold text-slate-700">Category Image URL</label>
            <input type="url" name="image" value="{{ old('image') }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="https://images.unsplash.com/...">
        </div>

        <div class="mb-3">
            <label class="form-label text-xs fw-bold text-slate-700">Description</label>
            <textarea name="description" rows="3" class="form-control rounded-xl border-slate-200 text-sm" placeholder="Brief summary of products in this category...">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label text-xs fw-bold text-slate-700">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control rounded-xl border-slate-200 text-sm" style="max-width: 150px;">
        </div>

        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="is_featured" id="featuredSwitch" checked>
            <label class="form-check-label text-xs fw-bold text-slate-800" for="featuredSwitch">Show in Homepage Featured Grid</label>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4 text-xs fw-bold">Cancel</a>
            <button type="submit" class="btn btn-primary rounded-pill px-4 text-xs fw-bold shadow-lg">Save Category</button>
        </div>
    </form>
</div>

@endsection
