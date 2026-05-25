@extends('layouts.guest')

@section('title', 'Register')

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

            </div>
        </div>

        <!-- Quote/Branding at Bottom Left -->
        <div class="absolute bottom-16 left-16 z-10">
            <div class="max-w-[420px]">
                <p class="text-2xl font-bold text-white tracking-tight leading-tight italic opacity-90">
                    "The simplest way to manage your team's presence and focus on what matters."
                </p>
                <div class="h-1 w-12 bg-white mt-6"></div>
            </div>
        </div>
    </div>

    <!-- Right Side: Register Form -->
    <div class="w-full md:w-1/2 flex items-center justify-center bg-white p-12 overflow-y-auto">
        <div class="w-full max-w-[360px] py-12">
            <div class="mb-16">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] mb-6">REGISTRATION</p>
                <h1 class="text-6xl font-bold text-slate-900 tracking-tight leading-[1.1]">Join</h1>
                <h1 class="text-6xl font-bold text-slate-900 tracking-tight leading-[1.1]">us.</h1>
                <p class="text-sm text-slate-400 mt-6 font-medium">Create your account to start managing leave requests.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-8">
                @csrf

                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">FULL NAME</label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        class="w-full px-4 py-4 border border-slate-200 rounded-none focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-all text-sm placeholder:text-slate-300"
                        placeholder="John Doe">
                    @error('name')
                        <p class="text-[10px] text-red-500 font-bold uppercase tracking-wider mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">EMAIL</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        class="w-full px-4 py-4 border border-slate-200 rounded-none focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-all text-sm placeholder:text-slate-300"
                        placeholder="john@acme.com">
                    @error('email')
                        <p class="text-[10px] text-red-500 font-bold uppercase tracking-wider mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
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

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">CONFIRM PASSWORD</label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        class="w-full px-4 py-4 border border-slate-200 rounded-none focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-all text-sm placeholder:text-slate-300"
                        placeholder="••••••••">
                </div>

                <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-bold py-5 rounded-none transition-all flex items-center justify-between px-8 group">
                    <span class="text-xs uppercase tracking-[0.2em]">Create account</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </button>
            </form>

            <div class="mt-16 pt-8 border-t border-slate-100">
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-slate-900 hover:underline decoration-2 ml-2">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
