<x-guest-layout>
    <div class="mb-4 text-center">
        <h3 class="fs-4 fw-extrabold text-slate-900 mb-1">Welcome Back</h3>
        <p class="text-slate-500 text-xs mb-0">Sign in to your account or access the admin panel</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label text-xs fw-bold text-slate-700">Email Address</label>
            <div class="position-relative">
                <input id="email" class="form-control rounded-xl border-slate-200 ps-4 text-sm" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label text-xs fw-bold text-slate-700 mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-blue-600 hover:text-blue-800 text-decoration-none fw-semibold" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input id="password" class="form-control rounded-xl border-slate-200 ps-4 text-sm" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label text-xs text-slate-600">Remember my session</label>
        </div>

        <button type="submit" class="btn btn-brand-primary w-100 rounded-xl py-2.5 fw-bold text-sm shadow-md mb-3">
            Sign In to Account
        </button>

        <!-- Quick 1-Click Demo Login Fill -->
        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 mb-3 text-center">
            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider d-block mb-2">⚡ Quick 1-Click Demo Fill:</span>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 text-xs fw-semibold" onclick="document.getElementById('email').value='admin@example.com'; document.getElementById('password').value='password';">
                    <i class="bi bi-shield-lock me-1"></i> Admin
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 text-xs fw-semibold" onclick="document.getElementById('email').value='customer@example.com'; document.getElementById('password').value='password';">
                    <i class="bi bi-person me-1"></i> Customer
                </button>
            </div>
        </div>

        <div class="text-center text-xs text-slate-500 pt-2 border-top">
            Don't have an account yet? 
            <a href="{{ route('register') }}" class="text-blue-600 fw-bold text-decoration-none ms-1">
                Create Account
            </a>
        </div>
    </form>
</x-guest-layout>
