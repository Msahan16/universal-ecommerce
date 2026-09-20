@extends('layouts.admin')

@section('page_title', 'Website Customization & CMS')
@section('page_subtitle', 'Control live store content, hero graphics, contact details, and homepage sections')

@section('content')

@php
    $currency = $settings['currency_symbol'] ?? 'Rs. ';
@endphp

<form action="{{ route('admin.cms.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <!-- Main Customization Columns -->
        <div class="col-lg-8">
            <!-- 1. Hero Banner Settings -->
            <div class="admin-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-slate-900 mb-0 text-sm uppercase tracking-wider">
                        <i class="bi bi-image text-blue-600 me-2"></i>Homepage Hero Section
                    </h6>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="section_hero_enabled" id="heroToggle" {{ ($settings['section_hero_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-xs fw-bold text-slate-700" for="heroToggle">Enable Hero</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Hero Badge Pill</label>
                    <input type="text" name="hero_badge" value="{{ $settings['hero_badge'] ?? '' }}" class="form-control rounded-xl border-slate-200 text-sm">
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Hero Headline Title *</label>
                    <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? '' }}" class="form-control rounded-xl border-slate-200 fw-bold" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Hero Subtitle / Description</label>
                    <textarea name="hero_subtitle" rows="3" class="form-control rounded-xl border-slate-200 text-sm">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-xs fw-bold text-slate-700">Primary CTA Button Text</label>
                        <input type="text" name="hero_cta_text" value="{{ $settings['hero_cta_text'] ?? 'Shop Full Catalog' }}" class="form-control rounded-xl border-slate-200 text-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-xs fw-bold text-slate-700">Primary CTA Link</label>
                        <input type="text" name="hero_cta_link" value="{{ $settings['hero_cta_link'] ?? '/shop' }}" class="form-control rounded-xl border-slate-200 text-sm">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Hero Graphic / Image URL</label>
                    <input type="url" name="hero_image" value="{{ $settings['hero_image'] ?? '' }}" class="form-control rounded-xl border-slate-200 text-sm" placeholder="https://images.unsplash.com/...">
                </div>

                <div class="mb-0">
                    <label class="form-label text-xs fw-bold text-slate-700">Or Upload Hero Image File</label>
                    <input type="file" name="hero_image_file" class="form-control rounded-xl border-slate-200 text-sm" accept="image/*">
                </div>
            </div>

            <!-- 2. Promotional Banner Settings -->
            <div class="admin-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-slate-900 mb-0 text-sm uppercase tracking-wider">
                        <i class="bi bi-megaphone text-blue-600 me-2"></i>Promotional Campaign Banner
                    </h6>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="section_promo_enabled" id="promoToggle" {{ ($settings['section_promo_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-xs fw-bold text-slate-700" for="promoToggle">Enable Promo</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Banner Title</label>
                    <input type="text" name="promo_banner_title" value="{{ $settings['promo_banner_title'] ?? '' }}" class="form-control rounded-xl border-slate-200 text-sm">
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Banner Subtitle</label>
                    <input type="text" name="promo_banner_subtitle" value="{{ $settings['promo_banner_subtitle'] ?? '' }}" class="form-control rounded-xl border-slate-200 text-sm">
                </div>

                <div class="mb-0">
                    <label class="form-label text-xs fw-bold text-slate-700">Featured Promo Coupon Code</label>
                    <input type="text" name="promo_banner_code" value="{{ $settings['promo_banner_code'] ?? 'WELCOME10' }}" class="form-control rounded-xl border-slate-200 font-mono text-sm uppercase" style="max-width: 250px;">
                </div>
            </div>

            <!-- 3. Contact & Business Details -->
            <div class="admin-card p-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">
                    <i class="bi bi-geo-alt text-blue-600 me-2"></i>Business Contact & Direct Lines
                </h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-xs fw-bold text-slate-700">Customer Support Phone</label>
                        <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+94 11 234 5678' }}" class="form-control rounded-xl border-slate-200 text-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-xs fw-bold text-slate-700">Support Email Address</label>
                        <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'support@universal-store.lk' }}" class="form-control rounded-xl border-slate-200 text-sm">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-xs fw-bold text-slate-700">Official Physical / Factory Address</label>
                        <input type="text" name="contact_address" value="{{ $settings['contact_address'] ?? '45 Tech Avenue, Industrial Zone, Colombo' }}" class="form-control rounded-xl border-slate-200 text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="col-lg-4">
            <!-- General Branding -->
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Store Identity</h6>
                
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Website Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Universal Store' }}" class="form-control rounded-xl border-slate-200 text-sm" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Website Tagline</label>
                    <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? 'Universal Commerce' }}" class="form-control rounded-xl border-slate-200 text-sm">
                </div>
            </div>

            <!-- Homepage Sections Controller (Enable/Disable dynamically) -->
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Homepage Section Control</h6>

                <div class="space-y-2 text-sm">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="section_categories_enabled" id="sec_cat" {{ ($settings['section_categories_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-xs fw-bold text-slate-800" for="sec_cat">Categories Grid</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="section_featured_enabled" id="sec_feat" {{ ($settings['section_featured_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-xs fw-bold text-slate-800" for="sec_feat">Featured Products</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="section_features_enabled" id="sec_val" {{ ($settings['section_features_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-xs fw-bold text-slate-800" for="sec_val">Value Proposition Strip</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="section_quotation_enabled" id="sec_quo" {{ ($settings['section_quotation_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-xs fw-bold text-slate-800" for="sec_quo">Ask for Quotation Section</label>
                    </div>
                </div>
            </div>

            <!-- Shipping Rules -->
            <div class="admin-card p-4 mb-4">
                <h6 class="fw-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Shipping Rules</h6>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Currency Symbol</label>
                    <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? 'Rs. ' }}" class="form-control rounded-xl border-slate-200 text-sm" style="max-width: 120px;">
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-700">Flat Shipping Fee</label>
                    <input type="number" step="0.01" name="flat_shipping_rate" value="{{ $settings['flat_shipping_rate'] ?? 450 }}" class="form-control rounded-xl border-slate-200 font-mono text-sm">
                </div>

                <div class="mb-0">
                    <label class="form-label text-xs fw-bold text-slate-700">Free Shipping Minimum Threshold</label>
                    <input type="number" step="0.01" name="free_shipping_threshold" value="{{ $settings['free_shipping_threshold'] ?? 15000 }}" class="form-control rounded-xl border-slate-200 font-mono text-sm">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-lg">
                Save & Update Live Store
            </button>
        </div>
    </div>
</form>

@endsection
