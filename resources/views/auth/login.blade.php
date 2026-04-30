@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container-fluid px-0">
    <div class="row gx-0 vh-100">
        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center position-relative" style="background-image: url('https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,.65);"></div>
            <div class="position-relative text-white p-5" style="max-width: 450px; z-index: 1;">
                <div class="mb-5">
                    <span class="text-uppercase text-muted small">Leave Management</span>
                    <h1 class="display-5 fw-bold mt-3">Welcome back.</h1>
                    <p class="lead text-white-75">Manage leaves, approvals and team time in one place.</p>
                </div>

                <div class="card bg-white bg-opacity-10 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <p class="text-uppercase text-muted small mb-3">Demo credentials</p>
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2"><strong>HR Admin</strong> · hr@example.com / password</li>
                            <li class="mb-2"><strong>Manager</strong> · manager@example.com / password</li>
                            <li><strong>Employee</strong> · employee@example.com / password</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light">
            <div class="w-100" style="max-width: 420px;">
                <div class="text-center mb-4">
                    <span class="text-uppercase text-muted small">Sign in</span>
                    <h2 class="fw-bold mt-2">Welcome back.</h2>
                    <p class="text-muted">Sign in to access your leave dashboard.</p>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" name="password" required class="form-control">
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot your password?</a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-dark w-100 py-2">Sign in</button>
                        </form>
                    </div>
                </div>

                @if (Route::has('register'))
                    <div class="text-center mt-4">
                        <p class="mb-0 text-muted">No account? <a href="{{ route('register') }}" class="text-decoration-none">Create one</a></p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection