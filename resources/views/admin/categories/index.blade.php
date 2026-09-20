@extends('layouts.admin')

@section('page_title', 'Categories Management')
@section('page_subtitle', 'Organize your universal catalog into departments & subcategories')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="text-xs text-slate-500">
        Total <strong class="text-slate-800">{{ $categories->count() }}</strong> categories configured
    </div>

    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill btn-sm px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Add New Category
    </a>
</div>

<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0 text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                <tr>
                    <th class="ps-4 py-3">Category</th>
                    <th class="py-3">Slug</th>
                    <th class="py-3">Products</th>
                    <th class="py-3">Featured</th>
                    <th class="pe-4 py-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $category->image ?? 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=200&auto=format&fit=crop&q=80' }}" class="rounded-xl object-cover border" style="width: 45px; height: 45px;">
                            <div>
                                <h6 class="fw-bold text-slate-900 mb-0 text-sm">{{ $category->name }}</h6>
                                <small class="text-slate-400 text-xs">{{ $category->description ?? 'No description' }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 font-mono text-xs text-slate-500">
                        {{ $category->slug }}
                    </td>
                    <td class="py-3 font-mono fw-bold text-slate-800">
                        {{ $category->products_count }} items
                    </td>
                    <td class="py-3">
                        @if($category->is_featured)
                            <span class="badge bg-blue-100 text-blue-800 rounded-pill text-xs">Featured</span>
                        @else
                            <span class="badge bg-slate-100 text-slate-600 rounded-pill text-xs">Standard</span>
                        @endif
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <div class="d-inline-flex gap-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary p-1 px-2">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category? Products in it will be uncategorized.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-slate-400">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
