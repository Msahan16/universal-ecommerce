@extends('layouts.store')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<!-- Catalog Breadcrumbs & Title Bar -->
<div class="bg-slate-900 text-white py-4 mb-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h1 class="fs-3 fw-extrabold mb-1">Product Catalog</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 text-xs text-slate-400">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-slate-400 text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Shop</li>
                </ol>
            </nav>
        </div>
        <div class="text-xs text-slate-400">
            Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card border-0 rounded-2xl shadow-sm p-4 bg-white sticky-top" style="top: 100px; z-index: 10;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-base text-slate-900"><i class="bi bi-funnel-fill text-blue-600 me-2"></i>Filters</h5>
                    <a href="{{ route('shop.index') }}" class="text-xs text-blue-600 text-decoration-none fw-semibold">Reset All</a>
                </div>

                <form action="{{ route('shop.index') }}" method="GET">
                    <!-- Search Input -->
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-slate-500 uppercase tracking-wider">Search Keyword</label>
                        <div class="position-relative">
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm rounded-lg border-slate-200 ps-3 pe-4" placeholder="Name or SKU...">
                            @if(request('q'))
                                <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="position-absolute end-0 top-50 translate-middle-y me-2 text-slate-400 text-xs"><i class="bi bi-x-circle-fill"></i></a>
                            @endif
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-slate-500 uppercase tracking-wider">Categories</label>
                        <div class="space-y-1 max-h-48 overflow-y-auto pe-1">
                            @foreach($categories as $cat)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" value="{{ $cat->slug }}" id="cat_{{ $cat->id }}" {{ request('category') == $cat->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label text-xs d-flex justify-content-between cursor-pointer" for="cat_{{ $cat->id }}">
                                        <span>{{ $cat->name }}</span>
                                        <span class="text-slate-400 font-mono">({{ $cat->products_count }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-slate-500 uppercase tracking-wider">Brands</label>
                        <div class="space-y-1 max-h-40 overflow-y-auto pe-1">
                            @foreach($brands as $brand)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="brand" value="{{ $brand->slug }}" id="brand_{{ $brand->id }}" {{ request('brand') == $brand->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label text-xs d-flex justify-content-between cursor-pointer" for="brand_{{ $brand->id }}">
                                        <span>{{ $brand->name }}</span>
                                        <span class="text-slate-400 font-mono">({{ $brand->products_count }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-4">
                        <label class="form-label text-xs fw-bold text-slate-500 uppercase tracking-wider">Price Range ({{ $currency }})</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control form-control-sm border-slate-200" placeholder="Min">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control form-control-sm border-slate-200" placeholder="Max">
                        </div>
                    </div>

                    <!-- In Stock Checkbox -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="in_stock_check" {{ request('in_stock') ? 'checked' : '' }}>
                        <label class="form-check-label text-xs fw-bold text-slate-700" for="in_stock_check">
                            In Stock Items Only
                        </label>
                    </div>

                    <button type="submit" class="btn btn-brand-primary w-100 rounded-lg py-2 text-xs fw-bold">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Catalog Product Grid -->
        <div class="col-lg-9">
            <!-- Filter Bar & Sort -->
            <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded-2xl shadow-sm mb-4 border border-slate-100">
                <div class="text-xs text-slate-500 mb-2 mb-sm-0">
                    Showing <strong class="text-slate-800">{{ $products->count() }}</strong> of <strong class="text-slate-800">{{ $products->total() }}</strong> products
                </div>

                <div class="d-flex align-items-center gap-2">
                    <label class="text-xs text-slate-500 fw-bold uppercase">Sort By:</label>
                    <form action="{{ route('shop.index') }}" method="GET" class="d-inline">
                        @foreach(request()->except('sort', 'page') as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <select name="sort" class="form-select form-select-sm border-slate-200 rounded-lg text-xs" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest Arrivals</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Alphabetical (A-Z)</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-md-4 col-sm-6">
                        <div class="card-product h-100 d-flex flex-column shadow-sm">
                            <div class="position-relative overflow-hidden bg-slate-100" style="height: 200px;">
                                <a href="{{ route('shop.show', $product->slug) }}">
                                    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-cover">
                                </a>
                                @if($product->discount_percentage)
                                    <span class="position-absolute top-3 start-3 badge bg-rose-600 text-white rounded-pill px-2 py-1 text-xs font-bold shadow">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @endif
                                @if($product->stock <= 0)
                                    <span class="position-absolute bottom-3 start-3 badge bg-slate-800 text-white rounded-pill px-2 py-0.5 text-xs">
                                        Out of Stock
                                    </span>
                                @endif
                            </div>

                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1 text-xs text-slate-400">
                                    <span>{{ $product->category->name ?? 'Universal' }}</span>
                                    <span class="font-mono text-slate-500">{{ $product->sku }}</span>
                                </div>

                                <h6 class="fw-bold text-slate-900 mb-2 line-clamp-2 text-sm">
                                    <a href="{{ route('shop.show', $product->slug) }}" class="text-slate-900 text-decoration-none hover:text-blue-600 transition">
                                        {{ $product->name }}
                                    </a>
                                </h6>

                                <div class="mt-auto d-flex align-items-center justify-content-between pt-2 border-top border-slate-100">
                                    <div>
                                        <div class="fs-6 fw-extrabold text-slate-900">
                                            {{ $currency }}{{ number_format($product->price, 2) }}
                                        </div>
                                        @if($product->compare_price)
                                            <small class="text-slate-400 text-decoration-line-through text-xs">
                                                {{ $currency }}{{ number_format($product->compare_price, 2) }}
                                            </small>
                                        @endif
                                    </div>

                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-brand-primary btn-sm rounded-pill px-3 fw-bold d-flex align-items-center gap-1 shadow-sm" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                            <i class="bi bi-cart-plus"></i>
                                            <span>Add</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="card border-0 rounded-2xl shadow-sm p-5 text-center bg-white my-4">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 d-flex align-items-center justify-content-center mx-auto mb-3 fs-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="fw-bold text-slate-800">No Products Found</h5>
                    <p class="text-slate-500 text-sm mb-4">We couldn't find any products matching your selected criteria.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-brand-primary btn-sm rounded-pill px-4 mx-auto">
                        Clear All Filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
