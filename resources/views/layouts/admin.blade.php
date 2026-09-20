<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Portal - {{ \App\Models\SiteSetting::get('site_name', 'Universal Commerce') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --admin-sidebar-bg: #0f172a;
            --admin-primary: #3b82f6;
        }

        body {
            font-family: var(--font-main);
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 260px;
            background-color: var(--admin-sidebar-bg);
            min-height: 100vh;
            transition: all 0.3s;
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #94a3b8;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .admin-nav-link:hover, .admin-nav-link.active {
            color: #ffffff;
            background-color: rgba(59, 130, 246, 0.15);
            border-left: 3px solid #3b82f6;
        }

        .admin-nav-link i {
            font-size: 1.15rem;
        }

        .admin-content {
            flex-grow: 1;
            min-width: 0;
            background-color: #f8fafc;
        }

        .admin-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="d-flex">

    <!-- Admin Sidebar -->
    <aside class="admin-sidebar d-flex flex-column p-3 text-white flex-shrink-0">
        <!-- Brand Title -->
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 pb-3 mb-3 border-bottom border-slate-800 text-white text-decoration-none">
            <div class="w-9 h-9 rounded-lg bg-blue-600 text-white d-flex align-items-center justify-content-center shadow">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <span class="fs-6 fw-bold tracking-tight d-block leading-tight">Admin Console</span>
                <span class="text-xs text-blue-400 font-semibold">{{ \App\Models\SiteSetting::get('site_name', 'Universal E-Com') }}</span>
            </div>
        </a>

        <!-- Navigation Menu Items -->
        <ul class="nav nav-pills flex-column mb-auto space-y-1">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="pt-3 pb-1 text-slate-500 text-xs uppercase tracking-wider font-bold">Catalog & Inventory</li>

            <li>
                <a href="{{ route('admin.products.index') }}" class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Products</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i>
                    <span>Categories</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.brands.index') }}" class="admin-nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <i class="bi bi-tag"></i>
                    <span>Brands</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.attributes.index') }}" class="admin-nav-link {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                    <i class="bi bi-sliders2"></i>
                    <span>Dynamic Attributes</span>
                </a>
            </li>

            <li class="pt-3 pb-1 text-slate-500 text-xs uppercase tracking-wider font-bold">Sales & Orders</li>

            <li>
                <a href="{{ route('admin.orders.index') }}" class="admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span class="flex-grow-1">Orders</span>
                    @php
                        $pendingCount = \App\Models\Order::whereIn('status', ['new', 'processing', 'confirmed'])->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-amber-500 text-black rounded-pill font-bold">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.quotations.index') }}" class="admin-nav-link {{ request()->routeIs('admin.quotations.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span class="flex-grow-1">Quotations</span>
                    @php
                        $pendingQuotes = \App\Models\Quotation::where('status', 'pending')->count();
                    @endphp
                    @if($pendingQuotes > 0)
                        <span class="badge bg-blue-500 text-white rounded-pill font-bold">{{ $pendingQuotes }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.coupons.index') }}" class="admin-nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>Coupons & Deals</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customers.index') }}" class="admin-nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Customers</span>
                </a>
            </li>

            <li class="pt-3 pb-1 text-slate-500 text-xs uppercase tracking-wider font-bold">Store Customizer</li>

            <li>
                <a href="{{ route('admin.cms.index') }}" class="admin-nav-link {{ request()->routeIs('admin.cms.*') ? 'active' : '' }}">
                    <i class="bi bi-palette"></i>
                    <span>Website CMS & Hero</span>
                </a>
            </li>
        </ul>

        <!-- Bottom Actions -->
        <div class="pt-3 border-top border-slate-800">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-light btn-sm w-100 mb-2 rounded-lg d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-box-arrow-up-right"></i> View Live Store
            </a>
            <div class="d-flex align-items-center justify-content-between text-xs text-slate-400 mt-2">
                <span>{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-rose-400 hover:text-rose-300 bg-transparent border-0 p-0 text-xs">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-content d-flex flex-column">
        <!-- Top Bar -->
        <header class="bg-white border-bottom border-slate-200 px-4 py-3 d-flex justify-content-between align-items-center sticky-top">
            <div>
                <h5 class="fw-bold mb-0 text-slate-800">@yield('page_title', 'Dashboard')</h5>
                <small class="text-slate-500">@yield('page_subtitle', 'System Administration')</small>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3">
                    <i class="bi bi-globe me-1 text-blue-600"></i> Storefront
                </a>
                <span class="badge bg-emerald-100 text-emerald-800 font-semibold px-2 py-1 rounded-pill">
                    ● System Online
                </span>
            </div>
        </header>

        <!-- Flash messages -->
        <div class="px-4 pt-3">
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

        <main class="p-4 flex-grow-1">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
