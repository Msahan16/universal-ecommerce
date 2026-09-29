<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Models\SiteSetting::get('site_name', 'Universal E-Commerce') }} - {{ \App\Models\SiteSetting::get('site_tagline', 'The Universal Commerce Engine') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary-color: #0f172a;
            --accent-color: #f59e0b;
            --font-main: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-main);
            color: #1e293b;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        .bg-brand-primary {
            background-color: var(--primary-color) !important;
        }

        .text-brand-primary {
            color: var(--primary-color) !important;
        }

        .btn-brand-primary {
            background-color: var(--primary-color);
            color: #ffffff;
            border: none;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        .btn-brand-primary:hover {
            background-color: var(--primary-hover);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .navbar-blur {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .badge-pill-soft {
            background-color: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
        }

        .card-product {
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            transition: all 0.25s ease;
            background: #ffffff;
            overflow: hidden;
        }

        .card-product:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        .cart-badge {
            font-size: 0.7rem;
            padding: 0.25em 0.55em;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Top Announcement Bar -->
    <div class="bg-slate-900 text-white py-2 px-4 text-xs sm:text-sm font-medium">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-amber-500 text-black font-bold">HOT DEAL</span>
                <span>{{ \App\Models\SiteSetting::get('hero_badge', '⚡ Quality & Fast Delivery Islandwide') }}</span>
            </div>
            <div class="d-none d-md-flex align-items-center gap-4 text-slate-300">
                <span><i class="bi bi-telephone-fill me-1 text-amber-400"></i> {{ \App\Models\SiteSetting::get('contact_phone', '+94 11 234 5678') }}</span>
                <a href="{{ route('orders.track') }}" class="text-slate-300 hover:text-white text-decoration-none">
                    <i class="bi bi-geo-alt-fill me-1 text-emerald-400"></i> Track Order
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-blur sticky-top py-3">
        <div class="container">
            <!-- Brand Logo / Name -->
            <a class="navbar-brand d-flex align-items-center gap-2 fw-extrabold text-slate-900" href="{{ route('home') }}">
                @php
                    $siteLogo = \App\Models\SiteSetting::get('logo');
                @endphp
                @if($siteLogo)
                    <img src="{{ str_starts_with($siteLogo, 'http') || str_starts_with($siteLogo, '/') ? asset($siteLogo) : asset('storage/' . $siteLogo) }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'MOTOX') }}" class="h-10 w-auto object-contain">
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white d-flex align-items-center justify-content-center shadow-sm">
                        <i class="bi bi-speedometer2 text-xl"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fs-4 fw-bold tracking-tight text-slate-900 leading-none">
                            {{ \App\Models\SiteSetting::get('site_name', 'MOTOX SPARES') }}
                        </span>
                        <span class="text-xs text-slate-500 font-semibold tracking-wider uppercase">
                            {{ \App\Models\SiteSetting::get('site_tagline', 'GENUINE MOTO PARTS') }}
                        </span>
                    </div>
                @endif
            </a>

            <!-- Search Bar (Desktop) -->
            <div class="d-none d-lg-block mx-4 flex-grow-1" style="max-width: 480px;">
                <form action="{{ route('shop.index') }}" method="GET" class="position-relative">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-lg rounded-pill ps-4 pe-5 border-slate-200 fs-6 shadow-sm" placeholder="Search products, profiles, parts, SKUs...">
                    <button type="submit" class="btn btn-brand-primary rounded-circle position-absolute end-0 top-50 translate-middle-y me-2 p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-search text-sm"></i>
                    </button>
                </form>
            </div>

            <!-- Navbar Actions -->
            <div class="d-flex align-items-center gap-3">
                <!-- Navigation Links -->
                <div class="d-none d-md-flex align-items-center gap-3 me-2">
                    <a href="{{ route('home') }}" class="nav-link fw-semibold px-2 {{ request()->routeIs('home') ? 'text-blue-600' : 'text-slate-700' }}">Home</a>
                    <a href="{{ route('shop.index') }}" class="nav-link fw-semibold px-2 {{ request()->routeIs('shop.*') ? 'text-blue-600' : 'text-slate-700' }}">Shop Catalog</a>
                    @if(\App\Models\SiteSetting::get('section_quotation_enabled', '1') == '1')
                        <a href="{{ route('quotations.create') }}" class="nav-link fw-semibold px-2 {{ request()->routeIs('quotations.*') ? 'text-blue-600' : 'text-slate-700' }}">Ask Quotation</a>
                    @endif
                    <a href="{{ route('orders.track') }}" class="nav-link fw-semibold px-2 {{ request()->routeIs('orders.track') ? 'text-blue-600' : 'text-slate-700' }}">Track</a>
                </div>

                <!-- Shopping Cart Icon -->
                @php
                    $cartService = app(\App\Services\CartService::class);
                    $cartCount = $cartService->getCount();
                @endphp
                <a href="{{ route('cart.index') }}" class="btn btn-light rounded-pill position-relative border px-3 py-2 d-flex align-items-center gap-2 hover:bg-slate-100 shadow-sm text-slate-800 text-decoration-none">
                    <i class="bi bi-cart3 fs-5 text-blue-600"></i>
                    <span class="d-none d-sm-inline fw-bold text-sm">Cart</span>
                    <span class="badge bg-blue-600 text-white rounded-pill cart-badge">
                        {{ $cartCount }}
                    </span>
                </a>

                <!-- User Account Dropdown -->
                @auth
                    <div class="dropdown">
                        <button class="btn btn-light rounded-pill border px-3 py-2 d-flex align-items-center gap-2 dropdown-toggle shadow-sm text-slate-800" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5 text-slate-600"></i>
                            <span class="fw-semibold text-sm d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-slate-100 rounded-3 p-2">
                            @if(Auth::user()->is_admin)
                                <li>
                                    <a class="dropdown-item py-2 fw-semibold text-blue-600 rounded-2" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> Admin Panel
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item py-2 rounded-2" href="{{ route('customer.orders') }}">
                                    <i class="bi bi-box-seam me-2 text-slate-500"></i> My Orders
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 rounded-2" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-gear me-2 text-slate-500"></i> Account Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger rounded-2">
                                        <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold text-sm">
                        <i class="bi bi-person me-1"></i> Sign In
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Global Flash Messages / Notifications -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <div class="fw-semibold">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <div class="fw-semibold">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Modern Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-5 pb-4 mt-5 border-t border-slate-800">
        <div class="container">
            <div class="row g-4 mb-5">
                <!-- Column 1: Brand & Bio -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        @php
                            $footerLogo = \App\Models\SiteSetting::get('logo');
                        @endphp
                        @if(file_exists(public_path('images/logo-white.svg')))
                            <img src="{{ asset('images/logo-white.svg') }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'MOTOX') }}" class="h-10 w-auto object-contain">
                        @elseif($footerLogo)
                            <img src="{{ str_starts_with($footerLogo, 'http') || str_starts_with($footerLogo, '/') ? asset($footerLogo) : asset('storage/' . $footerLogo) }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'MOTOX') }}" class="h-10 w-auto object-contain bg-white rounded-lg p-1">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white d-flex align-items-center justify-content-center shadow-sm">
                                <i class="bi bi-speedometer2 text-xl"></i>
                            </div>
                            <span class="fs-4 fw-bold text-white tracking-tight">
                                {{ \App\Models\SiteSetting::get('site_name', 'MOTOX SPARES') }}
                            </span>
                        @endif
                    </div>
                    <p class="text-slate-400 text-sm pe-lg-4 leading-relaxed">
                        {{ \App\Models\SiteSetting::get('hero_subtitle', 'Leading multi-industry e-commerce platform offering top-tier architectural systems, materials, and precision hardware solutions.') }}
                    </p>
                    <div class="d-flex gap-3 mt-4 text-lg">
                        <a href="#" class="w-9 h-9 rounded-full bg-slate-800 text-slate-400 hover:text-white hover:bg-blue-600 transition flex items-center justify-center text-decoration-none"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full bg-slate-800 text-slate-400 hover:text-white hover:bg-blue-600 transition flex items-center justify-center text-decoration-none"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full bg-slate-800 text-slate-400 hover:text-white hover:bg-blue-600 transition flex items-center justify-center text-decoration-none"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full bg-slate-800 text-slate-400 hover:text-white hover:bg-blue-600 transition flex items-center justify-center text-decoration-none"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white font-bold mb-3 uppercase tracking-wider text-xs">Catalog</h6>
                    <ul class="list-unstyled space-y-2 text-sm">
                        <li><a href="{{ route('shop.index') }}" class="text-slate-400 hover:text-white text-decoration-none">All Products</a></li>
                        <li><a href="{{ route('shop.index', ['sort' => 'latest']) }}" class="text-slate-400 hover:text-white text-decoration-none">New Arrivals</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-slate-400 hover:text-white text-decoration-none">Shopping Cart</a></li>
                        <li><a href="{{ route('orders.track') }}" class="text-slate-400 hover:text-white text-decoration-none">Order Tracking</a></li>
                    </ul>
                </div>

                <!-- Column 3: Customer Care -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white font-bold mb-3 uppercase tracking-wider text-xs">Customer Help</h6>
                    <ul class="list-unstyled space-y-2 text-sm">
                        <li><a href="{{ route('customer.orders') }}" class="text-slate-400 hover:text-white text-decoration-none">My Account</a></li>
                        <li><a href="{{ route('orders.track') }}" class="text-slate-400 hover:text-white text-decoration-none">Shipping & Delivery</a></li>
                        <li><a href="{{ route('checkout.index') }}" class="text-slate-400 hover:text-white text-decoration-none">Payment Options</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white text-decoration-none">Warranty & Returns</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact & Newsletter -->
                <div class="col-lg-4 col-md-6">
                    <h6 class="text-white font-bold mb-3 uppercase tracking-wider text-xs">Direct Support</h6>
                    <ul class="list-unstyled space-y-3 text-sm text-slate-400">
                        <li class="d-flex gap-2">
                            <i class="bi bi-geo-alt text-blue-500 fs-6"></i>
                            <span>{{ \App\Models\SiteSetting::get('contact_address', '45 Tech Avenue, Industrial Zone, Colombo, Sri Lanka') }}</span>
                        </li>
                        <li class="d-flex gap-2">
                            <i class="bi bi-telephone text-blue-500 fs-6"></i>
                            <span>{{ \App\Models\SiteSetting::get('contact_phone', '+94 11 234 5678') }}</span>
                        </li>
                        <li class="d-flex gap-2">
                            <i class="bi bi-envelope text-blue-500 fs-6"></i>
                            <span>{{ \App\Models\SiteSetting::get('contact_email', 'support@universal-store.lk') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-slate-800 my-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-xs text-slate-500">
                <p class="mb-2 mb-md-0">&copy; {{ date('Y') }} {{ \App\Models\SiteSetting::get('site_name', 'Universal Store') }}. All rights reserved.</p>
                <div class="d-flex gap-3">
                    <span>Cash on Delivery</span>
                    <span>•</span>
                    <span>Credit & Debit Cards</span>
                    <span>•</span>
                    <span>Bank Transfer</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
