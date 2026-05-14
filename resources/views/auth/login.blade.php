@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="flex h-screen w-full overflow-hidden">
    <!-- Left Side: Image & Branding -->
    <div class="hidden md:flex md:w-1/2 relative bg-slate-900">
        <img src="{{ asset('images/login-bg.png') }}" alt="Office Background" class="absolute inset-0 w-full h-full object-cover opacity-50 grayscale">
        
        <!-- Logo at Top Left -->
        <div class="absolute top-16 left-16 z-10">
            <div class="text-white select-none">
                <div class="text-7xl font-bold tracking-tighter leading-[0.8] mb-1">LEAVE</div>
                <div class="text-7xl font-bold tracking-tighter leading-[0.8] flex items-center">
                    <span class="mr-2">.OS</span>
                </div>
                <div class="text-[10px] font-bold text-white/40 uppercase tracking-[0.4em] mt-8">ACME CORPORATION // HR OS</div>
            </div>
        </div>

        <!-- Demo Credentials at Bottom Left -->
        <div class="absolute bottom-16 left-16 z-10">
            <div class="bg-white p-10 w-[420px] shadow-2xl">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-6">DEMO CREDENTIALS</p>
                <div class="space-y-3 text-[11px] font-bold text-slate-800">
                    <div class="flex items-center gap-4">
                        <span class="text-slate-300 w-16">HR</span>
                        <span class="text-slate-400">—</span>
                        <span>hr@acme.com / hr12345</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-slate-300 w-16">Manager</span>
                        <span class="text-slate-400">—</span>
                        <span>manager.eng@acme.com / managert1k</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-slate-300 w-16">Employee</span>
                        <span class="text-slate-400">—</span>
                        <span>alice@acme.com / employee123</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full md:w-1/2 flex items-center justify-center bg-white p-12">
        <div class="w-full max-w-[360px]">
            <div class="mb-16">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] mb-6">SIGN IN</p>
                <h1 class="text-6xl font-bold text-slate-900 tracking-tight leading-[1.1]">Welcome</h1>
                <h1 class="text-6xl font-bold text-slate-900 tracking-tight leading-[1.1]">back.</h1>
                <p class="text-sm text-slate-400 mt-6 font-medium">Manage leaves, approvals and team time in one place.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-10">
                @csrf

                <div class="space-y-2">
                    <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">EMAIL</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        class="w-full px-4 py-4 border border-slate-200 rounded-none focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-all text-sm placeholder:text-slate-300"
                        placeholder="hr@acme.com">
                    @error('email')
                        <p class="text-[10px] text-red-500 font-bold uppercase tracking-wider mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">PASSWORD</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        class="w-full px-4 py-4 border border-slate-200 rounded-none focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-all text-sm placeholder:text-slate-300"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-[10px] text-red-500 font-bold uppercase tracking-wider mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 border-slate-200 rounded-none accent-slate-900">
                        <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider group-hover:text-slate-600 transition-colors">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[11px] font-black text-slate-900 uppercase tracking-widest hover:underline decoration-2">Forgot?</a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-bold py-5 rounded-none transition-all flex items-center justify-between px-8 group">
                    <span class="text-xs uppercase tracking-[0.2em]">Sign in</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </button>
            </form>

            <div class="mt-16 pt-8 border-t border-slate-100">
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest">
                    No account? 
                    <a href="{{ route('register') }}" class="text-slate-900 hover:underline decoration-2 ml-2">Create one</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection