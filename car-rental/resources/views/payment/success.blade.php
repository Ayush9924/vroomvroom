@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-brand-gray-light pt-28 pb-20 px-4">

    {{-- Confetti Canvas --}}
    <canvas id="confetti-canvas" style="position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:100;"></canvas>

    <div class="max-w-3xl mx-auto">

        {{-- Success Hero --}}
        <div class="bg-brand-black rounded-3xl overflow-hidden mb-8 relative" style="background:linear-gradient(135deg,#0a0a0a 0%,#1a1a2e 100%);">
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 20% 50%,#3B6FFF 0%,transparent 60%),radial-gradient(circle at 80% 20%,#22c55e 0%,transparent 50%);"></div>
            <div class="relative z-10 p-12 text-center">
                {{-- Animated Checkmark --}}
                <div class="w-24 h-24 mx-auto mb-6 rounded-full flex items-center justify-center" style="background:linear-gradient(135deg,#22c55e,#16a34a);box-shadow:0 0 60px rgba(34,197,94,0.4);">
                    <svg class="checkmark-icon w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" class="checkmark-path"/>
                    </svg>
                </div>
                <h1 class="font-display text-6xl text-white tracking-widest mb-3">PAYMENT CONFIRMED</h1>
                <p class="text-green-400 font-body text-lg">Your booking is now officially confirmed.</p>
                <div class="mt-4 inline-block bg-white/10 backdrop-blur-sm px-5 py-2 rounded-full">
                    <span class="font-body text-white/70 text-sm">Payment ID: </span>
                    <span class="font-body text-white font-semibold text-sm">{{ $booking->razorpay_payment_id }}</span>
                </div>
            </div>
        </div>

        {{-- Booking Summary Card --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-black/5 mb-6">
            <h2 class="font-display text-2xl text-brand-black tracking-wide mb-6">Booking Summary</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Car Info --}}
                <div class="bg-brand-gray-light rounded-2xl p-5">
                    <p class="text-brand-gray-mid text-xs font-body uppercase tracking-wider mb-1">Vehicle</p>
                    <p class="font-display text-2xl text-brand-black">{{ $booking->car->type ?? 'Car' }}</p>
                    <p class="text-brand-gray-mid text-sm font-body">Car ID: #{{ $booking->car->id }}</p>
                </div>

                {{-- Amount Paid --}}
                <div class="bg-green-50 rounded-2xl p-5 border border-green-100">
                    <p class="text-green-600 text-xs font-body uppercase tracking-wider mb-1">Amount Paid</p>
                    <p class="font-display text-3xl text-brand-black">₹{{ number_format($booking->total_price, 2) }}</p>
                    <p class="text-green-600 text-sm font-body">✓ Payment Successful</p>
                </div>

                {{-- Dates --}}
                <div class="bg-brand-gray-light rounded-2xl p-5">
                    <p class="text-brand-gray-mid text-xs font-body uppercase tracking-wider mb-2">Rental Period</p>
                    <div class="flex items-center gap-3">
                        <div>
                            <p class="text-brand-gray-mid text-xs font-body">From</p>
                            <p class="font-semibold text-brand-black font-body">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</p>
                        </div>
                        <svg class="w-4 h-4 text-brand-gray-mid flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        <div>
                            <p class="text-brand-gray-mid text-xs font-body">To</p>
                            <p class="font-semibold text-brand-black font-body">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Paid At --}}
                <div class="bg-brand-gray-light rounded-2xl p-5">
                    <p class="text-brand-gray-mid text-xs font-body uppercase tracking-wider mb-1">Confirmed On</p>
                    <p class="font-semibold text-brand-black font-body">
                        {{ \Carbon\Carbon::parse($booking->paid_at)->format('d M Y, h:i A') }}
                    </p>
                    <p class="text-brand-gray-mid text-sm font-body">Booking #{{ $booking->id }}</p>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('dashboard') }}"
                class="flex-1 bg-brand-black text-white text-center py-4 rounded-2xl font-semibold font-body hover:bg-black hover:shadow-xl transition-all duration-300">
                ← Back to Dashboard
            </a>
            <a href="/bookings/create"
                class="flex-1 bg-brand-accent text-white text-center py-4 rounded-2xl font-semibold font-body hover:shadow-lg hover:shadow-blue-200 transition-all duration-300"
                style="background:linear-gradient(135deg,#3B6FFF,#6366f1);">
                + Book Another Car
            </a>
        </div>

        {{-- Receipt Notice --}}
        <p class="text-center text-brand-gray-mid text-xs font-body mt-6">
            A confirmation has been recorded. Payment ID: <strong>{{ $booking->razorpay_payment_id }}</strong>
        </p>

    </div>
</div>

<style>
    @keyframes draw {
        to { stroke-dashoffset: 0; }
    }
    .checkmark-path {
        stroke-dasharray: 30;
        stroke-dashoffset: 30;
        animation: draw 0.6s ease 0.4s forwards;
    }
</style>

<script>
    // Simple confetti celebration
    (function() {
        const canvas = document.getElementById('confetti-canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const colors = ['#3B6FFF', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
        const particles = [];

        for (let i = 0; i < 120; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height - canvas.height,
                w: Math.random() * 10 + 4,
                h: Math.random() * 5 + 2,
                color: colors[Math.floor(Math.random() * colors.length)],
                speed: Math.random() * 3 + 2,
                angle: Math.random() * 360,
                spin: (Math.random() - 0.5) * 4,
                opacity: 1
            });
        }

        let frame = 0;
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            frame++;
            particles.forEach(function(p) {
                p.y += p.speed;
                p.angle += p.spin;
                if (frame > 80) p.opacity -= 0.01;
                if (p.opacity <= 0) return;
                ctx.save();
                ctx.globalAlpha = p.opacity;
                ctx.translate(p.x, p.y);
                ctx.rotate((p.angle * Math.PI) / 180);
                ctx.fillStyle = p.color;
                ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
                ctx.restore();
            });
            if (frame < 180) requestAnimationFrame(animate);
            else ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        animate();
    })();
</script>
@endsection
