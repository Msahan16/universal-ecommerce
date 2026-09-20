<x-guest-layout>
    <div class="mb-4 text-center">
        <h3 class="fs-4 fw-extrabold text-slate-900 mb-1">Reset Password</h3>
        <p class="text-slate-500 text-xs mb-0">Enter your account email and we'll send you a password reset link.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label text-xs fw-bold text-slate-700">Email Address</label>
            <input id="email" class="form-control rounded-xl border-slate-200 ps-4 text-sm" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <button type="submit" class="btn btn-brand-primary w-100 rounded-xl py-2.5 fw-bold text-sm shadow-md mb-3">
            Send Reset Link
        </button>

        <div class="text-center text-xs text-slate-500 pt-2 border-top">
            Remembered your password? 
            <a href="{{ route('login') }}" class="text-blue-600 fw-bold text-decoration-none ms-1">
                Back to Sign In
            </a>
        </div>
    </form>
</x-guest-layout>
