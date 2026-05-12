@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-brand-gray-light pt-28 pb-20 px-4">
    <div class="max-w-[1100px] mx-auto">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="flash-message mb-6 bg-green-50/50 border-l-4 border-green-500 rounded-[12px] p-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="font-body text-green-800 text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="flash-message mb-6 bg-red-50/50 border-l-4 border-red-500 rounded-[12px] p-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <p class="font-body text-red-800 text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- HERO HEADER SECTION --}}
        <div class="hero-header bg-brand-black rounded-[24px] p-10 md:py-10 md:px-12 mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-8 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 80% 20%,#3B6FFF 0%,transparent 50%);"></div>
            
            <div class="relative z-10">
                <p class="font-body text-brand-accent text-[12px] uppercase tracking-[0.1em] font-bold mb-2">MY ACCOUNT</p>
                <h1 class="font-display text-[56px] text-white leading-none tracking-wide mb-1">YOUR DASHBOARD</h1>
                <p class="font-body text-brand-gray-mid">Welcome back, <span class="text-brand-accent">{{ auth()->user()->name }}</span></p>
            </div>

            <div class="relative z-10 hidden md:flex items-center gap-4">
                <div class="bg-[#1a1a1a] rounded-[16px] py-4 px-6 text-center min-w-[110px] border border-white/5">
                    <p class="font-display text-[32px] text-white leading-none mb-1 stat-number" data-target="{{ $bookings->count() }}">0</p>
                    <p class="font-body text-brand-gray-mid text-[12px] uppercase tracking-wider">Total Bookings</p>
                </div>
                <div class="bg-[#1a1a1a] rounded-[16px] py-4 px-6 text-center min-w-[110px] border border-white/5">
                    <p class="font-display text-[32px] text-white leading-none mb-1 stat-number" data-target="{{ $bookings->where('status','paid')->count() }}">0</p>
                    <p class="font-body text-brand-gray-mid text-[12px] uppercase tracking-wider">Paid</p>
                </div>
                <div class="bg-[#1a1a1a] rounded-[16px] py-4 px-6 text-center min-w-[110px] border border-white/5">
                    <p class="font-display text-[32px] text-white leading-none mb-1 stat-number" data-target="{{ $bookings->where('status','pending')->count() }}">0</p>
                    <p class="font-body text-brand-gray-mid text-[12px] uppercase tracking-wider">Pending</p>
                </div>
            </div>
        </div>

        {{-- BOOKINGS SECTION HEADER --}}
        <div class="mb-6 flex items-center justify-between">
            <h2 class="font-display text-[36px] text-brand-black leading-none">YOUR BOOKINGS</h2>
            <a href="/bookings/create" class="bg-brand-accent text-white font-display text-[16px] tracking-[1px] rounded-[50px] py-3 px-7 hover:bg-[#2b56cc] hover:shadow-[0_0_15px_rgba(59,111,255,0.4)] transition-all duration-300">
                + NEW BOOKING
            </a>
        </div>

        {{-- BOOKINGS LIST --}}
        @if ($bookings->isEmpty())
            <div class="bg-white rounded-[24px] shadow-sm border border-black/5 p-16 text-center">
                <svg class="w-32 h-32 mx-auto text-brand-gray-mid/30 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                <h3 class="font-display text-[36px] text-brand-black mb-2">NO BOOKINGS YET</h3>
                <p class="font-body text-brand-gray-mid text-lg mb-8">Ready for your first drive? Browse our fleet.</p>
                <a href="/bookings/create" class="inline-block bg-brand-black text-white font-display text-[16px] tracking-[1px] rounded-[50px] py-3 px-8 hover:bg-brand-accent transition-colors duration-300">
                    BOOK YOUR FIRST CAR
                </a>
            </div>
        @else
            <div class="space-y-5">
                @foreach ($bookings as $index => $booking)
                    @php
                        $borderColor = '#f59e0b'; // pending
                        if($booking->status === 'paid') $borderColor = '#22c55e';
                        if($booking->status === 'cancelled') $borderColor = '#ef4444';
                    @endphp
                    
                    <div class="booking-card bg-white rounded-[20px] p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col md:flex-row md:items-center gap-6 relative overflow-hidden" style="animation-delay: {{ $index * 0.08 }}s;">
                        {{-- Left Accent Bar --}}
                        <div class="absolute left-0 top-0 bottom-0 w-[4px]" style="background-color: {{ $borderColor }};"></div>
                        
                        {{-- LEFT: Thumbnail --}}
                        <div class="w-20 h-20 rounded-[12px] bg-brand-gray-light flex-shrink-0 overflow-hidden relative border border-black/5">
                            @if($booking->car->image ?? false)
                                <img src="{{ asset('storage/' . $booking->car->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-brand-gray-mid">
                                    <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h10l2-2z"></path></svg>
                                </div>
                            @endif
                        </div>

                        {{-- CENTER: Details --}}
                        <div class="flex-1">
                            <div class="flex items-baseline gap-2 mb-1">
                                <h3 class="font-display text-[22px] text-brand-black leading-none">{{ $booking->car->name ?? $booking->car->type ?? 'Car' }}</h3>
                                <span class="font-body text-[12px] text-brand-gray-mid">#{{ $booking->car_id }}</span>
                            </div>
                            
                            <div class="flex items-center gap-2 text-brand-gray-mid font-body text-[14px] mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} &rarr; {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                            </div>
                            
                            <p class="font-display text-[20px] text-brand-accent leading-none">₹{{ number_format($booking->total_price, 2) }}</p>
                        </div>

                        {{-- RIGHT: Status & Actions --}}
                        <div class="flex flex-col md:items-end gap-4 min-w-[200px]">
                            {{-- Status Badge --}}
                            <div>
                                @if ($booking->status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-[50px] text-[13px] font-body font-semibold bg-green-100 text-green-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        Paid
                                    </span>
                                @elseif ($booking->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-[50px] text-[13px] font-body font-semibold bg-red-100 text-red-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Cancelled
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-[50px] text-[13px] font-body font-semibold bg-amber-100 text-amber-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Pending
                                    </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-3 w-full md:w-auto">
                                @if ($booking->status === 'pending')
                                    <button
                                        class="pay-btn flex-1 md:flex-none bg-brand-accent text-white font-display text-[15px] tracking-[1px] rounded-[50px] py-2.5 px-6 hover:bg-[#2b56cc] hover:shadow-[0_0_15px_rgba(59,111,255,0.4)] transition-all duration-300 whitespace-nowrap"
                                        data-booking-id="{{ $booking->id }}"
                                        data-amount="{{ $booking->total_price }}">
                                        PAY ₹{{ number_format($booking->total_price, 2) }}
                                    </button>
                                @endif

                                @if ($booking->status !== 'cancelled')
                                    <form method="POST" action="/bookings/{{ $booking->id }}/cancel" class="inline flex-1 md:flex-none">
                                        @csrf
                                        <button type="submit"
                                            class="w-full md:w-auto border-[1.5px] border-[#ef4444] text-[#ef4444] bg-transparent font-display text-[15px] tracking-[1px] rounded-[50px] py-2.5 px-6 hover:bg-[#ef4444] hover:text-white transition-all duration-300"
                                            onclick="return confirm('Are you sure you want to cancel this booking?')">
                                            CANCEL
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection

@push('styles')
<style>
    .hero-header {
        opacity: 0;
        animation: scaleFadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    @keyframes scaleFadeIn {
        from { opacity: 0; transform: scale(0.98); }
        to { opacity: 1; transform: scale(1); }
    }

    .booking-card {
        opacity: 0;
        animation: fadeInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .flash-message {
        animation: slideDownFade 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    @keyframes slideDownFade {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
{{-- Razorpay Checkout Script --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Stat Pills Count-up Animation
    const counters = document.querySelectorAll('.stat-number');
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        const duration = 1000; // 1 second
        const steps = 30;
        const stepTime = Math.abs(Math.floor(duration / steps));
        let current = 0;
        
        if (target > 0) {
            const timer = setInterval(() => {
                current += Math.ceil(target / steps);
                if (current >= target) {
                    counter.innerText = target;
                    clearInterval(timer);
                } else {
                    counter.innerText = current;
                }
            }, stepTime);
        } else {
            counter.innerText = 0;
        }
    });

    // Auto-dismiss Flash Messages
    const flashes = document.querySelectorAll('.flash-message');
    flashes.forEach(flash => {
        setTimeout(() => {
            flash.style.transition = 'opacity 0.4s ease, transform 0.4s ease, margin 0.4s ease, padding 0.4s ease, height 0.4s ease';
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-10px)';
            flash.style.margin = '0';
            flash.style.padding = '0';
            flash.style.height = '0';
            flash.style.overflow = 'hidden';
            setTimeout(() => flash.remove(), 400);
        }, 4000);
    });
});

// Razorpay Logic (untouched)
document.querySelectorAll('.pay-btn').forEach(function(button) {
    button.addEventListener('click', async function() {
        const bookingId  = this.dataset.bookingId;
        const originalText = this.textContent;
        const btn = this;

        btn.textContent = 'PROCESSING...';
        btn.disabled = true;
        btn.style.opacity = '0.7';

        try {
            const response = await fetch(`/payment/create-order/${bookingId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const order = await response.json();

            if (!order.order_id) {
                alert(order.message || 'Could not initiate payment. Please try again.');
                btn.textContent = originalText;
                btn.disabled = false;
                btn.style.opacity = '1';
                return;
            }

            const options = {
                key: order.key_id,
                amount: order.amount,
                currency: order.currency,
                name: order.company_name,
                description: `Booking #${order.booking_id}`,
                order_id: order.order_id,
                handler: async function(paymentResponse) {
                    try {
                        const verifyResponse = await fetch('/payment/verify', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                razorpay_order_id:   paymentResponse.razorpay_order_id,
                                razorpay_payment_id: paymentResponse.razorpay_payment_id,
                                razorpay_signature:  paymentResponse.razorpay_signature,
                                booking_id:          order.booking_id
                            })
                        });

                        const result = await verifyResponse.json();

                        if (result.success) {
                            showPaymentSuccess();
                            setTimeout(function() {
                                window.location.href = result.redirect;
                            }, 2000);
                        } else {
                            showPaymentError(result.message || 'Payment verification failed.');
                            btn.textContent = originalText;
                            btn.disabled = false;
                            btn.style.opacity = '1';
                        }
                    } catch (verifyErr) {
                        showPaymentError('Verification request failed. Contact support.');
                        btn.textContent = originalText;
                        btn.disabled = false;
                        btn.style.opacity = '1';
                    }
                },
                prefill: { name: order.user_name, email: order.user_email },
                theme: { color: '#3B6FFF' },
                modal: { ondismiss: function() { btn.textContent = originalText; btn.disabled = false; btn.style.opacity = '1'; } }
            };

            const rzp = new Razorpay(options);
            rzp.on('payment.failed', function(response) {
                showPaymentError('Payment failed: ' + response.error.description);
                btn.textContent = originalText;
                btn.disabled = false;
                btn.style.opacity = '1';
            });
            rzp.open();

        } catch (error) {
            console.error('Payment error:', error);
            alert('Something went wrong. Please try again.');
            btn.textContent = originalText;
            btn.disabled = false;
            btn.style.opacity = '1';
        }
    });
});

function showPaymentSuccess() {
    const overlay = document.createElement('div');
    overlay.id = 'payment-success-overlay';
    overlay.innerHTML = `
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.85);display:flex;align-items:center;justify-content:center;z-index:9999;backdrop-filter:blur(8px);">
            <div style="background:white;border-radius:28px;padding:52px 48px;text-align:center;max-width:420px;width:90%;animation:scaleIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards;">
                <div style="width:84px;height:84px;background:linear-gradient(135deg,#22c55e,#16a34a);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 8px 30px rgba(34,197,94,0.35);">
                    <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <h2 style="font-family:'Bebas Neue',sans-serif;font-size:34px;letter-spacing:0.08em;color:#0a0a0a;margin:0 0 8px;text-transform:uppercase;">Payment Successful</h2>
                <p style="color:#888;font-family:'Syne',sans-serif;font-size:14px;margin:0;">Redirecting to confirmation page...</p>
                <div style="margin-top:24px;height:3px;background:#f0f0ec;border-radius:99px;overflow:hidden;">
                    <div style="height:100%;background:linear-gradient(90deg,#3B6FFF,#22c55e);border-radius:99px;animation:loadBar 2s linear forwards;"></div>
                </div>
            </div>
        </div>
        <style>@keyframes scaleIn{from{transform:scale(0.8);opacity:0}to{transform:scale(1);opacity:1}} @keyframes loadBar{from{width:0%}to{width:100%}}</style>
    `;
    document.body.appendChild(overlay);
}

function showPaymentError(message) {
    const overlay = document.createElement('div');
    overlay.innerHTML = `
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;z-index:9999;backdrop-filter:blur(6px);" onclick="this.parentElement.remove()">
            <div style="background:white;border-radius:24px;padding:40px;text-align:center;max-width:380px;width:90%;animation:scaleIn 0.3s ease forwards;">
                <div style="width:70px;height:70px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <h3 style="font-family:'Bebas Neue',sans-serif;font-size:26px;color:#0a0a0a;margin:0 0 8px;text-transform:uppercase;">Payment Failed</h3>
                <p style="color:#888;font-family:'Syne',sans-serif;font-size:13px;margin:0 0 20px;">${message}</p>
                <button onclick="this.closest('[onclick]').click()" style="background:#0a0a0a;color:white;border:none;padding:10px 28px;border-radius:99px;font-family:'Syne',sans-serif;font-size:14px;font-weight:600;cursor:pointer;">Close</button>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);
}
</script>
@endpush
