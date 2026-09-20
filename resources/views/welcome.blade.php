@extends('layouts.store')

@section('content')

@php
    $currency = \App\Models\SiteSetting::get('currency_symbol', 'Rs. ');
    $heroEnabled = \App\Models\SiteSetting::get('section_hero_enabled', '1') == '1';
    $categoriesEnabled = \App\Models\SiteSetting::get('section_categories_enabled', '1') == '1';
    $featuredEnabled = \App\Models\SiteSetting::get('section_featured_enabled', '1') == '1';
    $promoEnabled = \App\Models\SiteSetting::get('section_promo_enabled', '1') == '1';
    $featuresEnabled = \App\Models\SiteSetting::get('section_features_enabled', '1') == '1';
@endphp

<!-- Hero Section -->
@if($heroEnabled)
<section class="position-relative overflow-hidden py-5" style="background: radial-gradient(130% 100% at 50% 0%, #1e293b 0%, #0f172a 100%);">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-white">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs sm:text-sm font-semibold mb-4">
                    <span>{{ \App\Models\SiteSetting::get('hero_badge', '⚡ High Grade Materials & Fast Delivery') }}</span>
                </div>
                
                <h1 class="display-4 fw-extrabold text-white mb-3 tracking-tight leading-tight">
                    {{ \App\Models\SiteSetting::get('hero_title', 'Engineered for Performance. Built for Reliability.') }}
                </h1>
                
                <p class="lead text-slate-300 mb-4 fs-5 pe-lg-5 leading-relaxed font-normal">
                    {{ \App\Models\SiteSetting::get('hero_subtitle', 'Browse our universal catalog of architectural systems, commercial profiles, auto spare parts, and industrial hardware.') }}
                </p>

                <div class="d-flex flex-wrap gap-3 align-items-center pt-2">
                    <a href="{{ url(\App\Models\SiteSetting::get('hero_cta_link', '/shop')) }}" class="btn btn-primary btn-lg rounded-pill px-4 py-3 fw-bold shadow-lg d-flex align-items-center gap-2">
                        <span>{{ \App\Models\SiteSetting::get('hero_cta_text', 'Shop Full Catalog') }}</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('orders.track') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3 fw-semibold">
                        <i class="bi bi-geo-alt me-1"></i> Track Order
                    </a>
                </div>

                <!-- Live stats strip -->
                <div class="row g-3 pt-5 mt-2 border-top border-slate-700/60">
                    <div class="col-4">
                        <div class="fs-4 fw-bold text-white">100%</div>
                        <div class="text-xs text-slate-400">Authentic Parts</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 fw-bold text-white">Fast</div>
                        <div class="text-xs text-slate-400">Islandwide Shipping</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 fw-bold text-white">24/7</div>
                        <div class="text-xs text-slate-400">Technical Support</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="position-relative">
                    <div class="position-absolute -top-10 -left-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="card border-0 rounded-3xl overflow-hidden shadow-2xl bg-slate-800/80 border border-slate-700/80 p-2">
                        @php
                            $heroImg = \App\Models\SiteSetting::get('hero_image', 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&auto=format&fit=crop&q=80');
                            if (!str_starts_with($heroImg, 'http')) {
                                $heroImg = asset('storage/' . $heroImg);
                            }
                        @endphp
                        <img src="{{ $heroImg }}" alt="Hero Showcase" class="w-100 rounded-2xl object-cover" style="height: 380px;">
                        
                        <div class="p-3 d-flex justify-content-between align-items-center bg-slate-900/90 rounded-xl mt-2 text-white">
                            <div>
                                <small class="text-xs text-slate-400 d-block uppercase font-bold">Catalog Range</small>
                                <span class="fw-bold text-sm">Aluminium • Spare Parts • Hardware</span>
                            </div>
                            <span class="badge bg-blue-600 px-3 py-2 rounded-pill font-bold">In Stock</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Value Proposition Features -->
@if($featuresEnabled)
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row g-4 py-2">
            <div class="col-md-3 col-6 d-flex align-items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 d-flex align-items-center justify-content-center fs-4 flex-shrink-0">
                    <i class="bi bi-truck"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-sm">Express Shipping</h6>
                    <small class="text-slate-500 text-xs">Free over {{ $currency }}{{ number_format(\App\Models\SiteSetting::get('free_shipping_threshold', 15000)) }}</small>
                </div>
            </div>

            <div class="col-md-3 col-6 d-flex align-items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 d-flex align-items-center justify-content-center fs-4 flex-shrink-0">
                    <i class="bi bi-patch-check"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-sm">Genuine Guarantee</h6>
                    <small class="text-slate-500 text-xs">Factory-certified standards</small>
                </div>
            </div>

            <div class="col-md-3 col-6 d-flex align-items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 d-flex align-items-center justify-content-center fs-4 flex-shrink-0">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-sm">Secure Payment</h6>
                    <small class="text-slate-500 text-xs">COD or Instant Online Card</small>
                </div>
            </div>

            <div class="col-md-3 col-6 d-flex align-items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 d-flex align-items-center justify-content-center fs-4 flex-shrink-0">
                    <i class="bi bi-headset"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-sm">Expert Consultation</h6>
                    <small class="text-slate-500 text-xs">Custom sizing & fabrication</small>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Categories Showcase -->
@if($categoriesEnabled && $featuredCategories->count() > 0)
<section class="py-5 bg-slate-50">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge-pill-soft text-xs uppercase mb-2 d-inline-block">Product Catalog</span>
                <h2 class="fs-2 fw-extrabold text-slate-900 tracking-tight">Explore by Category</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="text-blue-600 fw-bold text-sm text-decoration-none d-flex align-items-center gap-1 hover:gap-2 transition">
                <span>View All Categories</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($featuredCategories as $category)
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="card border-0 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition text-decoration-none text-dark position-relative group h-100 bg-white">
                    <div class="overflow-hidden" style="height: 180px;">
                        <img src="{{ $category->image ?? 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=600&auto=format&fit=crop&q=80' }}" class="w-100 h-100 object-cover group-hover:scale-105 transition duration-300" alt="{{ $category->name }}">
                    </div>
                    <div class="p-4">
                        <h5 class="fw-bold text-slate-900 mb-1 text-base">{{ $category->name }}</h5>
                        <p class="text-slate-500 text-xs mb-0 line-clamp-2">{{ $category->description ?? 'Explore premium selections' }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products Section -->
@if($featuredEnabled && $featuredProducts->count() > 0)
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge bg-blue-100 text-blue-700 px-3 py-1 rounded-pill text-xs font-bold uppercase mb-2 d-inline-block">Handpicked</span>
                <h2 class="fs-2 fw-extrabold text-slate-900 tracking-tight">Featured Products</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-bold">
                View All
            </a>
        </div>

        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card-product h-100 d-flex flex-column">
                    <!-- Image Box -->
                    <div class="position-relative overflow-hidden bg-slate-100" style="height: 220px;">
                        <a href="{{ route('shop.show', $product->slug) }}">
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-cover">
                        </a>

                        @if($product->discount_percentage)
                            <span class="position-absolute top-3 start-3 badge bg-rose-600 text-white rounded-pill px-2.5 py-1 text-xs font-bold shadow">
                                -{{ $product->discount_percentage }}% OFF
                            </span>
                        @endif

                        @if($product->stock > 0)
                            <span class="position-absolute bottom-3 start-3 badge bg-emerald-500/90 text-white rounded-pill px-2 py-0.5 text-xs font-semibold backdrop-blur">
                                In Stock
                            </span>
                        @else
                            <span class="position-absolute bottom-3 start-3 badge bg-slate-700 text-white rounded-pill px-2 py-0.5 text-xs font-semibold">
                                Sold Out
                            </span>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">
                            {{ $product->category->name ?? 'General' }}
                        </div>
                        <h6 class="fw-bold text-slate-900 mb-2 line-clamp-2">
                            <a href="{{ route('shop.show', $product->slug) }}" class="text-slate-900 text-decoration-none hover:text-blue-600 transition">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <p class="text-slate-500 text-xs mb-3 line-clamp-2 flex-grow-1">
                            {{ $product->short_description ?? '' }}
                        </p>

                        <!-- Price & Add button -->
                        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-slate-100">
                            <div>
                                <div class="fs-5 fw-extrabold text-slate-900 leading-none">
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
                                <button type="submit" class="btn btn-brand-primary rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;" title="Add to Cart">
                                    <i class="bi bi-cart-plus text-base"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Promotional Banner (CMS Dynamic) -->
@if($promoEnabled)
<section class="py-5 bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-900 text-white my-4">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge bg-amber-400 text-slate-900 font-bold px-3 py-1 rounded-pill mb-3 uppercase text-xs">Special Promotion</span>
                <h2 class="display-6 fw-extrabold mb-2 text-white">
                    {{ \App\Models\SiteSetting::get('promo_banner_title', 'Limited Time Mega Deal!') }}
                </h2>
                <p class="fs-6 text-indigo-100 mb-0 pe-lg-4">
                    {{ \App\Models\SiteSetting::get('promo_banner_subtitle', 'Get instant discounts on select categories and wholesale packages.') }}
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex flex-column align-items-lg-end">
                    <div class="d-flex align-items-center gap-2 bg-white/10 backdrop-blur border border-white/20 px-4 py-2 rounded-2xl mb-3">
                        <span class="text-xs text-slate-200">Use Code:</span>
                        <span class="font-mono font-bold text-amber-300 fs-5">{{ \App\Models\SiteSetting::get('promo_banner_code', 'WELCOME10') }}</span>
                    </div>
                    <a href="{{ route('shop.index') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-bold shadow">
                        Claim Discount Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Latest Arrivals Catalog Grid -->
@if($latestProducts->count() > 0)
<section class="py-5 bg-slate-50">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge bg-emerald-100 text-emerald-800 px-3 py-1 rounded-pill text-xs font-bold uppercase mb-2 d-inline-block">Fresh Additions</span>
                <h2 class="fs-2 fw-extrabold text-slate-900 tracking-tight">Latest Catalog Arrivals</h2>
            </div>
            <a href="{{ route('shop.index', ['sort' => 'latest']) }}" class="text-blue-600 fw-bold text-sm text-decoration-none">
                Browse New Arrivals <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($latestProducts as $product)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card-product h-100 d-flex flex-column">
                    <div class="position-relative overflow-hidden bg-slate-100" style="height: 200px;">
                        <a href="{{ route('shop.show', $product->slug) }}">
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-cover">
                        </a>
                        @if($product->discount_percentage)
                            <span class="position-absolute top-3 start-3 badge bg-rose-600 text-white rounded-pill px-2.5 py-1 text-xs font-bold shadow">
                                -{{ $product->discount_percentage }}%
                            </span>
                        @endif
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="text-xs text-slate-500 font-semibold mb-1">
                            {{ $product->brand->name ?? 'Universal' }}
                        </div>
                        <h6 class="fw-bold text-slate-900 mb-2 line-clamp-2">
                            <a href="{{ route('shop.show', $product->slug) }}" class="text-slate-900 text-decoration-none hover:text-blue-600 transition">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <div class="mt-auto d-flex align-items-center justify-content-between pt-2 border-top border-slate-100">
                            <span class="fs-5 fw-extrabold text-slate-900">{{ $currency }}{{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Brands Carousel / Strip -->
@if($brands->count() > 0)
<section class="py-5 bg-white border-top">
    <div class="container text-center">
        <span class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-4 d-block">Trusted Brands & Industrial Partners</span>
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 gap-md-5">
            @foreach($brands as $brand)
                <div class="px-4 py-2 rounded-xl border border-slate-100 bg-slate-50 text-slate-700 fw-bold fs-6 shadow-sm hover:shadow transition">
                    {{ $brand->name }}
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection