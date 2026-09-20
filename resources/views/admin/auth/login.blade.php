<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal Login - {{ \App\Models\SiteSetting::get('site_name', 'Universal Commerce') }}</title>

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
            background-color: #0b0f19;
            background-image: radial-gradient(at 50% 0%, #1e293b 0%, #0b0f19 80%);
            min-height: 100vh;
            color: #f8fafc;
        }

        .admin-login-box {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5 min-vh-100">

    <div class="container" style="max-width: 440px;">
        <div class="text-center mb-4">
            <div class="w-16 h-16 rounded-2xl bg-blue-600 text-white d-flex align-items-center justify-content-center mx-auto mb-3 shadow-lg fs-2">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h4 class="fw-extrabold text-white tracking-tight mb-1">Admin Console</h4>
            <span class="badge bg-slate-800 text-blue-400 font-mono text-xs px-3 py-1 rounded-pill border border-slate-700">
                RESTRICTED SYSTEM ACCESS
            </span>
        </div>

        <div class="admin-login-box p-4 p-sm-5">
            @if(session('success'))
                <div class="alert alert-success text-xs py-2 rounded-xl mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger text-xs py-2 rounded-xl mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-300">Admin Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control bg-slate-900 border-slate-700 text-white rounded-xl text-sm" placeholder="admin@example.com" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-slate-300">Password</label>
                    <input type="password" name="password" class="form-control bg-slate-900 border-slate-700 text-white rounded-xl text-sm" placeholder="••••••••" required>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input bg-slate-800 border-slate-700" type="checkbox" name="remember" id="rememberMe">
                    <label class="form-check-label text-xs text-slate-400" for="rememberMe">
                        Keep me signed in
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-xl py-2.5 fw-bold text-sm shadow-lg mb-2">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Authenticate to Dashboard
                </button>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-300 text-xs font-semibold text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Return to Main Website
            </a>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
