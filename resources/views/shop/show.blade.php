@extends('layouts.store')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
@endphp

<!-- Breadcrumbs -->
<div class="bg-slate-100 py-3 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 text-xs text-slate-500">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-slate-600 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-slate-600 text-decoration-none">Shop</a></li>
                @if($product->category)
                    <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="text-slate-600 text-decoration-none">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active text-slate-900 font-semibold" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Gallery Images Column -->
        <div class="col-lg-6">
            <div class="card border-0 rounded-3xl overflow-hidden shadow-sm bg-white p-3">
                <div class="position-relative mb-3 bg-slate-50 rounded-2xl overflow-hidden" style="height: 420px;">
                    <img id="mainImage" src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-cover">
                    
                    @if($product->discount_percentage)
                        <span class="position-absolute top-4 start-4 badge bg-rose-600 text-white rounded-pill px-3 py-1.5 fs-6 font-bold shadow">
                            Save {{ $product->discount_percentage }}% OFF
                        </span>
                    @endif
                </div>

                @if($product->images->count() > 0)
                <div class="d-flex gap-2 overflow-x-auto pb-2">
                    <img src="{{ $product->thumbnail_url }}" class="rounded-xl border-2 border-blue-600 object-cover cursor-pointer" style="width: 75px; height: 75px;" onclick="document.getElementById('mainImage').src='{{ $product->thumbnail_url }}'">
                    @foreach($product->images as $img)
                        <img src="{{ $img->url }}" class="rounded-xl border object-cover cursor-pointer opacity-75 hover:opacity-100 transition" style="width: 75px; height: 75px;" onclick="document.getElementById('mainImage').src='{{ $img->url }}'">
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Product Purchase / Information Column -->
        <div class="col-lg-6">
            <div class="ps-lg-3">
                <!-- Brand & SKU -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-blue-50 text-blue-700 px-3 py-1 rounded-pill text-xs font-bold uppercase">
                        {{ $product->brand->name ?? 'Universal Quality' }}
                    </span>
                    <span class="text-xs text-slate-400 font-mono">SKU: <strong class="text-slate-600">{{ $product->sku }}</strong></span>
                </div>

                <!-- Product Name -->
                <h1 class="fs-2 fw-extrabold text-slate-900 mb-3 tracking-tight">
                    {{ $product->name }}
                </h1>

                <!-- Price Box -->
                <div class="d-flex align-items-baseline gap-3 mb-4 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="fs-2 fw-extrabold text-blue-600" id="displayPrice">
                        {{ $currency }}{{ number_format($product->price, 2) }}
                    </div>
                    @if($product->compare_price)
                        <div class="fs-5 text-slate-400 text-decoration-line-through">
                            {{ $currency }}{{ number_format($product->compare_price, 2) }}
                        </div>
                    @endif
                </div>

                <!-- Stock Badge -->
                <div class="mb-4">
                    @if($product->stock > 10)
                        <span class="badge bg-emerald-100 text-emerald-800 rounded-pill px-3 py-1 text-xs font-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> In Stock ({{ $product->stock }} units ready to ship)
                        </span>
                    @elseif($product->stock > 0)
                        <span class="badge bg-amber-100 text-amber-900 rounded-pill px-3 py-1 text-xs font-bold">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> Low Stock (Only {{ $product->stock }} left)
                        </span>
                    @else
                        <span class="badge bg-rose-100 text-rose-800 rounded-pill px-3 py-1 text-xs font-bold">
                            <i class="bi bi-x-circle-fill me-1"></i> Out of Stock
                        </span>
                    @endif
                </div>

                <!-- Short Description -->
                @if($product->short_description)
                    <p class="text-slate-600 leading-relaxed mb-4 text-sm">
                        {{ $product->short_description }}
                    </p>
                @endif

                <!-- Purchase Form (Supports Variants & Dynamic Attributes) -->
                <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Variant Selection (if any) -->
                    @if($product->variants->count() > 0)
                        <div class="mb-4 p-3 bg-white border border-slate-200 rounded-2xl shadow-sm">
                            <label class="form-label text-xs fw-bold text-slate-700 uppercase tracking-wider mb-2">Select Option / Variant:</label>
                            <div class="space-y-2">
                                @foreach($product->variants as $index => $variant)
                                    <div class="form-check p-2 border rounded-xl hover:bg-slate-50 transition cursor-pointer d-flex align-items-center justify-content-between">
                                        <div>
                                            <input class="form-check-input ms-0 me-2" type="radio" name="variant_id" id="var_{{ $variant->id }}" value="{{ $variant->id }}" {{ $index === 0 ? 'checked' : '' }} onchange="updateVariantPrice('{{ number_format($variant->price, 2) }}')">
                                            <label class="form-check-label text-sm fw-semibold text-slate-800" for="var_{{ $variant->id }}">
                                                {{ $variant->name }}
                                            </label>
                                        </div>
                                        <div class="text-sm font-bold text-blue-600 font-mono">
                                            {{ $currency }}{{ number_format($variant->price, 2) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quantity & Add to Cart -->
                    <div class="d-flex gap-3 align-items-center">
                        <div class="d-flex align-items-center border border-slate-300 rounded-xl bg-white p-1" style="width: 130px;">
                            <button type="button" class="btn btn-sm btn-light rounded-lg px-2" onclick="let q=document.getElementById('qtyInput'); if(q.value>1) q.value--;">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" id="qtyInput" name="quantity" value="1" min="1" max="{{ max(1, $product->stock) }}" class="form-control form-control-sm text-center border-0 fw-bold shadow-none p-0">
                            <button type="button" class="btn btn-sm btn-light rounded-lg px-2" onclick="let q=document.getElementById('qtyInput'); q.value++;">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>

                        <button type="submit" class="btn btn-brand-primary btn-lg rounded-xl flex-grow-1 py-3 fw-bold shadow-lg d-flex align-items-center justify-content-center gap-2" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="bi bi-cart-plus-fill fs-5"></i>
                            <span>Add to Cart</span>
                        </button>
                    </div>
                </form>

                <!-- Value Highlights -->
                <div class="row g-3 py-3 border-top border-slate-200 text-xs text-slate-600">
                    <div class="col-6 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-emerald-600 fs-5"></i>
                        <span>Guaranteed Authenticity</span>
                    </div>
                    <div class="col-6 d-flex align-items-center gap-2">
                        <i class="bi bi-truck text-blue-600 fs-5"></i>
                        <span>Doorstep Islandwide Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Specifications & Details Tabs -->
    <div class="mt-5 pt-4 border-top">
        <ul class="nav nav-tabs border-slate-200" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold text-sm px-4 py-3" id="spec-tab" data-bs-toggle="tab" data-bs-target="#spec-tab-pane" type="button" role="tab">
                    <i class="bi bi-sliders me-1"></i> Technical Specifications
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-sm px-4 py-3 text-slate-600" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-tab-pane" type="button" role="tab">
                    <i class="bi bi-card-text me-1"></i> Full Description
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-sm px-4 py-3 text-slate-600" id="ship-tab" data-bs-toggle="tab" data-bs-target="#ship-tab-pane" type="button" role="tab">
                    <i class="bi bi-box-seam me-1"></i> Shipping & Returns
                </button>
            </li>
        </ul>

        <div class="tab-content bg-white p-4 rounded-b-2xl border-x border-b border-slate-200 shadow-sm" id="productTabContent">
            <!-- Specifications Tab -->
            <div class="tab-pane fade show active" id="spec-tab-pane" role="tabpanel">
                <h6 class="fw-bold text-slate-900 mb-3">Product Attributes & Engineering Data</h6>
                
                @if($product->attributeValues->count() > 0)
                    <div class="table-responsive" style="max-width: 700px;">
                        <table class="table table-striped table-bordered text-sm mb-0">
                            <tbody>
                                @foreach($product->attributeValues as $pav)
                                    <tr>
                                        <th class="bg-slate-50 text-slate-700 w-33 fw-semibold">{{ $pav->attribute->name }}</th>
                                        <td class="text-slate-800">{{ $pav->display_value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-slate-500 text-sm mb-0">Standard industrial manufacturing specifications apply.</p>
                @endif
            </div>

            <!-- Description Tab -->
            <div class="tab-pane fade" id="desc-tab-pane" role="tabpanel">
                <div class="prose max-w-none text-slate-700 text-sm leading-relaxed">
                    {!! nl2br(e($product->description ?? $product->short_description)) !!}
                </div>
            </div>

            <!-- Shipping Tab -->
            <div class="tab-pane fade text-sm text-slate-700 leading-relaxed" id="ship-tab-pane" role="tabpanel">
                <h6 class="fw-bold text-slate-900 mb-2">Delivery & Logistics Information</h6>
                <p>Orders are dispatched within 24-48 business hours with verified courier tracking. Flat shipping rate is applied islandwide, and free delivery is available for eligible order totals over the minimum threshold.</p>
                <h6 class="fw-bold text-slate-900 mb-2 mt-3">Warranty & Return Policy</h6>
                <p class="mb-0">All products include official factory warranty against manufacturing defects. Unopened items in original packaging may be returned within 7 days of delivery.</p>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5 pt-4">
        <h3 class="fs-4 fw-extrabold text-slate-900 mb-4 tracking-tight">Related Products You May Like</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $rel)
            <div class="col-lg-3 col-md-6">
                <div class="card-product h-100 d-flex flex-column shadow-sm">
                    <div class="position-relative overflow-hidden bg-slate-100" style="height: 180px;">
                        <a href="{{ route('shop.show', $rel->slug) }}">
                            <img src="{{ $rel->thumbnail_url }}" alt="{{ $rel->name }}" class="w-100 h-100 object-cover">
                        </a>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <h6 class="fw-bold text-slate-900 mb-2 line-clamp-1 text-sm">
                            <a href="{{ route('shop.show', $rel->slug) }}" class="text-slate-900 text-decoration-none hover:text-blue-600">
                                {{ $rel->name }}
                            </a>
                        </h6>
                        <div class="mt-auto d-flex align-items-center justify-content-between pt-2 border-top border-slate-100">
                            <span class="fs-6 fw-bold text-slate-900">{{ $currency }}{{ number_format($rel->price, 2) }}</span>
                            <a href="{{ route('shop.show', $rel->slug) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function updateVariantPrice(price) {
        document.getElementById('displayPrice').innerText = '{{ $currency }}' + price;
    }
</script>
@endpush

@endsection
