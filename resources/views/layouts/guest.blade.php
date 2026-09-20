<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Models\SiteSetting::get('site_name', 'Universal Commerce') }} - Authentication</title>

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
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(135% 100% at 50% 0%, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }

        .btn-brand-primary {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: 600;
            border: none;
            transition: all 0.2s;
        }

        .btn-brand-primary:hover {
            background-color: #1d4ed8;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5 min-vh-100">

    <div class="container" style="max-width: 520px;">
        <!-- Brand Logo & Header -->
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none mb-2">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white d-flex align-items-center justify-content-center shadow-lg">
                    <i class="bi bi-box-seam-fill fs-4"></i>
                </div>
                <div class="text-start">
                    <span class="fs-4 fw-extrabold text-white tracking-tight d-block leading-none">
                        {{ \App\Models\SiteSetting::get('site_name', 'UNIVERSAL') }}
                    </span>
                    <span class="text-xs text-blue-400 font-bold tracking-widest uppercase">
                        {{ \App\Models\SiteSetting::get('site_tagline', 'E-COMMERCE') }}
                    </span>
                </div>
            </a>
        </div>

        <!-- Auth Card Container -->
        <div class="auth-card p-4 p-sm-5">
            {{ $slot }}
        </div>

        <!-- Back to Store Link -->
        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="text-slate-400 hover:text-white text-xs font-semibold text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Back to Homepage & Catalog
            </a>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
