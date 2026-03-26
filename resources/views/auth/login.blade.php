@extends('layouts.login')

@section('content')
<div class="login-card">

    {{-- Brand --}}
    <div class="brand-logo">
        <img src="{{ asset('siloam.png') }}" alt="Logo">
        <span class="brand-name">{{ config('app.name', 'Finance App') }}</span>
    </div>
    <p class="login-subtitle">Masuk ke akun Anda untuk melanjutkan</p>

    {{-- Microsoft SSO --}}
    <a href="{{ route('login.microsoft') }}" class="btn-microsoft">
        <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft">
        Login dengan Microsoft
    </a>

    <div class="divider">atau masuk dengan email</div>

    {{-- Email / Password form --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group-icon">
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="nama@domain.com"
                       required
                       autocomplete="email"
                       autofocus>
                <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group-icon">
                <input id="password"
                       type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••"
                       required
                       autocomplete="current-password">
                <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk
        </button>
    </form>

    <p class="form-footer">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </p>
</div>
@endsection
