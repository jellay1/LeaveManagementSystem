@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container-fluid px-0">
    <div class="row gx-0 vh-100">
        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center position-relative" style="background-image: url('https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,.65);"></div>
            <div class="position-relative text-white p-5" style="max-width: 450px; z-index: 1;">
                <div class="mb-5">
                    <span class="text-uppercase text-muted small">Leave Management</span>
                    <h1 class="display-5 fw-bold mt-3">Join the team.</h1>
                    <p class="lead text-white-75">Create your account to request leave, view balances, and collaborate with your manager.</p>
                </div>

                <div class="card bg-white bg-opacity-10 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <p class="text-uppercase text-muted small mb-3">Quick start</p>
                        <p class="small mb-0">Register now to begin managing leave applications, tracking your balances, and keeping your team in sync.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light">
            <div class="w-100" style="max-width: 420px;">
                <div class="text-center mb-4">
                    <span class="text-uppercase text-muted small">Create account</span>
                    <h2 class="fw-bold mt-2">Register</h2>
                    <p class="text-muted">Sign up to manage your leave requests and approvals.</p>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" name="password" required class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required class="form-control @error('password_confirmation') is-invalid @enderror">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-dark w-100 py-2">Register</button>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="mb-0 text-muted">Already registered? <a href="{{ route('login') }}" class="text-decoration-none">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
