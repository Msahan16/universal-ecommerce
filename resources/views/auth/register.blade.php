<x-guest-layout>
    <div class="mb-4 text-center">
        <h3 class="fs-4 fw-extrabold text-slate-900 mb-1">Create Account</h3>
        <p class="text-slate-500 text-xs mb-0">Join our universal platform to track orders and save details</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label text-xs fw-bold text-slate-700">Full Name</label>
            <input id="name" class="form-control rounded-xl border-slate-200 ps-4 text-sm" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Kasun Perera">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label text-xs fw-bold text-slate-700">Email Address</label>
            <input id="email" class="form-control rounded-xl border-slate-200 ps-4 text-sm" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label text-xs fw-bold text-slate-700">Password</label>
            <input id="password" class="form-control rounded-xl border-slate-200 ps-4 text-sm" type="password" name="password" required autocomplete="new-password" placeholder="Minimum 8 characters">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label text-xs fw-bold text-slate-700">Confirm Password</label>
            <input id="password_confirmation" class="form-control rounded-xl border-slate-200 ps-4 text-sm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <button type="submit" class="btn btn-brand-primary w-100 rounded-xl py-2.5 fw-bold text-sm shadow-md mb-3">
            Create Free Customer Account
        </button>

        <div class="text-center text-xs text-slate-500 pt-2 border-top">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-blue-600 fw-bold text-decoration-none ms-1">
                Sign In
            </a>
        </div>
    </form>
</x-guest-layout>
