@extends('layouts.app')

@section('content')
@push('styles')
<style>
    /* Modal Entry Animations */
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-40px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(40px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-slide-left {
        animation: slideInLeft 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .animate-slide-right {
        opacity: 0;
        animation: slideInRight 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards;
    }
    .car-float {
        animation: float 4s ease-in-out infinite;
    }
    .stagger-fade-up {
        opacity: 0;
        animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    /* Form Focus Styles */
    .premium-input {
        transition: all 0.25s ease;
    }
    .premium-input:focus {
        border-color: #3B6FFF;
        box-shadow: 0 0 0 4px rgba(59,111,255,0.15);
        outline: none;
    }
    
    /* Custom Checkbox */
    .premium-checkbox {
        accent-color: #3B6FFF;
        width: 18px;
        height: 18px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
</style>
@endpush

<!-- Full page background (Simulates being on the same page with a dark overlay) -->
<div class="fixed inset-0 flex items-center justify-center overflow-hidden bg-brand-black z-50 py-12 px-4 sm:px-6 lg:px-8">
    
    <!-- Background Blur Effect -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1600" alt="Background" class="w-full h-full object-cover opacity-20 blur-xl">
        <div class="absolute inset-0 bg-brand-black/70"></div>
    </div>

    <!-- Close Button (Simulates modal close, returns to home) -->
    <a href="{{ url('/') }}" class="absolute top-6 right-6 md:top-10 md:right-10 z-50 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md flex items-center justify-center text-white transition-all hover:scale-110">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </a>

    <!-- Modal Container -->
    <div class="relative z-10 w-full max-w-[1000px] h-[600px] bg-brand-white rounded-3xl shadow-[0_20px_60px_rgba(0,0,0,0.5)] overflow-hidden flex flex-col md:flex-row">
        
        <!-- LEFT PANEL (Illustration) -->
        <div class="hidden md:flex md:w-[45%] relative flex-col justify-between p-12 overflow-hidden animate-slide-left border-r border-white/5">
            
            <!-- Full Panel Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800" 
                     alt="Luxury Car" 
                     class="w-full h-full object-cover"
                     style="object-position: center;">
                <!-- Cinematic gradient overlay for text readability -->
                <div class="absolute inset-0 bg-gradient-to-b from-[#0d0d1a]/90 via-[#0d0d1a]/40 to-[#0d0d1a]/90"></div>
                <div class="absolute inset-0 bg-brand-accent/10 mix-blend-overlay"></div>
            </div>

            <!-- Logo -->
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 rounded-full bg-brand-accent flex items-center justify-center shadow-[0_0_20px_rgba(59,111,255,0.4)]">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="font-display text-2xl tracking-wider text-white">VRooMVRooM</span>
            </div>

            <!-- Tagline -->
            <div class="relative z-10 mt-auto">
                <h2 class="font-display text-5xl text-white leading-[0.9] mb-3">YOUR NEXT<br>DRIVE AWAITS</h2>
                <p class="font-body text-brand-gray-light text-[15px] opacity-90">Premium cars. Seamless experience.</p>
            </div>
        </div>

        <!-- RIGHT PANEL (Form) -->
        <div class="w-full md:w-[55%] h-full bg-brand-white p-8 sm:p-12 animate-slide-right flex flex-col justify-center overflow-y-auto">
            
            <div class="max-w-[400px] w-full mx-auto">
                <!-- Mobile Logo (Hidden on Desktop) -->
                <div class="md:hidden flex items-center justify-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-full bg-brand-accent flex items-center justify-center shadow-[0_0_15px_rgba(59,111,255,0.3)]">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="font-display text-2xl tracking-wider text-brand-black">VRooMVRooM</span>
                </div>

                <div class="mb-10 text-center md:text-left">
                    <h2 class="font-display text-[48px] text-brand-black leading-none mb-2">Welcome Back</h2>
                    <p class="font-body text-brand-gray-mid text-[15px]">Sign in to your VRooMVRooM account</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="/login" class="space-y-6">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="space-y-2 stagger-fade-up" style="animation-delay: 0.2s;">
                        <label for="email" class="block font-body text-[14px] font-semibold text-brand-black ml-1">Email Address</label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               value="{{ old('email') }}" 
                               required
                               class="premium-input w-full h-[52px] px-4 font-body text-[15px] text-brand-black bg-[#f0f0ec] border-[1.5px] border-transparent rounded-xl focus:bg-white focus:border-brand-accent transition-all placeholder:text-[#a0a0a0]"
                               placeholder="name@example.com">
                        @error('email')
                            <p class="font-body text-[13px] text-red-500 mt-1 ml-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2 stagger-fade-up" style="animation-delay: 0.3s;">
                        <label for="password" class="block font-body text-[14px] font-semibold text-brand-black ml-1">Password</label>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required
                               class="premium-input w-full h-[52px] px-4 font-body text-[15px] text-brand-black bg-[#f0f0ec] border-[1.5px] border-transparent rounded-xl focus:bg-white focus:border-brand-accent transition-all placeholder:text-[#a0a0a0]"
                               placeholder="••••••••">
                        @error('password')
                            <p class="font-body text-[13px] text-red-500 mt-1 ml-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember & Forgot Password -->
                    <div class="flex items-center justify-between stagger-fade-up" style="animation-delay: 0.4s;">
                        <div class="flex items-center gap-2">
                            <input id="remember" name="remember" type="checkbox" class="premium-checkbox rounded-[4px] border-gray-300 text-brand-accent focus:ring-brand-accent/30 bg-[#f0f0ec]">
                            <label for="remember" class="font-body text-[14px] font-medium text-brand-gray-mid select-none cursor-pointer">Remember me</label>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-body text-[14px] font-semibold text-brand-accent hover:text-[#2952cc] hover:underline transition-all">Forgot Password?</a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2 stagger-fade-up" style="animation-delay: 0.5s;">
                        <button type="submit" 
                                class="w-full h-[52px] bg-brand-black hover:bg-brand-accent text-white rounded-xl font-display text-[22px] tracking-[2px] shadow-[0_4px_14px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_24px_rgba(59,111,255,0.4)] hover:-translate-y-1 transition-all duration-300 ease-out flex items-center justify-center gap-3 group">
                            SIGN IN
                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>

                <!-- Register Link -->
                <div class="mt-8 text-center stagger-fade-up" style="animation-delay: 0.6s;">
                    <p class="font-body text-[15px] text-brand-gray-mid">
                        Don't have an account? 
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="text-brand-accent font-bold hover:underline ml-1">Sign Up</a>
                        @else
                            <a href="/register" class="text-brand-accent font-bold hover:underline ml-1">Sign Up</a>
                        @endif
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
