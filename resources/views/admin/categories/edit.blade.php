@extends('layouts.admin')

@section('page_title', 'Edit Category')
@section('page_subtitle', 'Update ' . $category->name)

@section('content')

<div class="admin-card p-4 mx-auto" style="max-width: 650px;">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label text-xs fw-bold text-slate-700">Category Name *</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control rounded-xl border-slate-200" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-xs fw-bold text-slate-700">Category Image URL</label>
            <input type="url" name="image" value="{{ old('image', $category->image) }}" class="form-control rounded-xl border-slate-200 text-sm">
        </div>

        <div class="mb-3">
            <label class="form-label text-xs fw-bold text-slate-700">Description</label>
            <textarea name="description" rows="3" class="form-control rounded-xl border-slate-200 text-sm">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label text-xs fw-bold text-slate-700">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="form-control rounded-xl border-slate-200 text-sm" style="max-width: 150px;">
        </div>

        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="is_featured" id="featuredSwitch" {{ $category->is_featured ? 'checked' : '' }}>
            <label class="form-check-label text-xs fw-bold text-slate-800" for="featuredSwitch">Show in Homepage Featured Grid</label>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4 text-xs fw-bold">Cancel</a>
            <button type="submit" class="btn btn-primary rounded-pill px-4 text-xs fw-bold shadow-lg">Save Changes</button>
        </div>
    </form>
</div>

@endsection
