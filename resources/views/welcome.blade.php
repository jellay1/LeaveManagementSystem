@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="text-center py-5">
    <h1 class="display-5">Leave Management System</h1>
    <p class="lead">Manage employee leave requests, approvals, and leave type setup in a single dashboard.</p>

    @guest
        <a href="{{ route('login') }}" class="btn btn-primary">Sign in</a>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-outline-secondary ms-2">Register</a>
        @endif
    @else
        <a href="{{ route('leave-requests.index') }}" class="btn btn-primary">View leave requests</a>
    @endguest
</div>
@endsection
