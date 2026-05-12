@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-brand-gray-light pt-28 pb-20 px-4">
    <div class="max-w-[1100px] mx-auto flex flex-col lg:flex-row gap-8">
        
        {{-- LEFT SIDEBAR: DATE SELECTION --}}
        <div class="w-full lg:w-[360px] flex-shrink-0">
            <div class="bg-white rounded-[24px] shadow-sm p-6 sticky top-28 fade-in-left">
                <h2 class="font-display text-[32px] text-brand-black tracking-wide mb-1">PLAN YOUR TRIP</h2>
                <p class="font-body text-brand-gray-mid mb-6">Select your rental dates</p>

                <form method="POST" action="/bookings/dates">
                    @csrf
                    
                    {{-- Start Date --}}
                    <div class="mb-4">
                        <label for="start_date" class="flex items-center gap-2 font-body text-brand-black mb-2 text-sm font-semibold">
                            <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Pick Up Date
                        </label>
                        <input id="start_date" name="start_date" type="date" value="{{ old('start_date', $start ?? '') }}" required
                            class="w-full h-[52px] border-[1.5px] border-[#e0e0e0] rounded-[12px] px-4 font-body focus:outline-none focus:border-brand-accent focus:ring-4 focus:ring-brand-accent/20 transition-all duration-300">
                        @error('start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- End Date --}}
                    <div class="mb-6">
                        <label for="end_date" class="flex items-center gap-2 font-body text-brand-black mb-2 text-sm font-semibold">
                            <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Return Date
                        </label>
                        <input id="end_date" name="end_date" type="date" value="{{ old('end_date', $end ?? '') }}" required
                            class="w-full h-[52px] border-[1.5px] border-[#e0e0e0] rounded-[12px] px-4 font-body focus:outline-none focus:border-brand-accent focus:ring-4 focus:ring-brand-accent/20 transition-all duration-300">
                        @error('end_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Trip Summary --}}
                    <div id="trip-summary" class="hidden bg-brand-accent/10 rounded-[12px] p-4 mb-6 transition-all duration-500">
                        <p class="font-body text-brand-accent font-semibold text-center" id="trip-days-text"></p>
                    </div>

                    <button type="submit" class="w-full h-[52px] bg-brand-accent text-white font-display text-[18px] tracking-[2px] rounded-[12px] hover:bg-[#2b56cc] hover:shadow-[0_0_20px_rgba(59,111,255,0.4)] transition-all duration-300">
                        FIND AVAILABLE CARS &rarr;
                    </button>
                </form>

                <div class="mt-6 text-center text-brand-gray-mid font-body text-xs flex flex-col gap-2">
                    <span class="flex items-center justify-center gap-1"><svg class="w-3 h-3 text-brand-gray-mid" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Free cancellation</span>
                    <span class="flex items-center justify-center gap-1"><svg class="w-3 h-3 text-brand-gray-mid" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> No hidden fees</span>
                    <span class="flex items-center justify-center gap-1"><svg class="w-3 h-3 text-brand-gray-mid" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Instant confirmation</span>
                </div>
            </div>
        </div>

        {{-- RIGHT MAIN AREA: CARS GRID OR PLACEHOLDER --}}
        <div class="flex-1">
            @if(isset($cars) && $cars->count() > 0)
                <div class="flex items-end justify-between mb-6">
                    <div>
                        <h2 class="font-display text-[40px] text-brand-black leading-none">AVAILABLE CARS</h2>
                        <p class="font-body text-brand-gray-mid mt-1">{{ \Carbon\Carbon::parse($start)->format('d M Y') }} &rarr; {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</p>
                    </div>
                    <span class="bg-brand-accent text-white font-body text-sm font-semibold px-4 py-1.5 rounded-full">
                        {{ $cars->count() }} cars found
                    </span>
                </div>

                @error('car_id')
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 font-body text-sm">
                        ❌ {{ $message }}
                    </div>
                @enderror

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($cars as $index => $car)
                        <div class="bg-white rounded-[20px] shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-[6px] overflow-hidden car-card flex flex-col" style="animation-delay: {{ $index * 0.1 }}s;">
                            <div class="relative h-[220px] w-full bg-gray-100">
                                @if($car->image)
                                    <img src="{{ asset('storage/' . $car->image) }}" class="w-full h-full object-cover" alt="{{ $car->name ?? $car->type }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?w=400" class="w-full h-full object-cover" alt="{{ $car->name ?? $car->type }}">
                                @endif
                                <span class="absolute top-4 right-4 bg-green-500 text-white font-body text-xs font-semibold px-3 py-1 rounded-full shadow-md">
                                    Available
                                </span>
                            </div>
                            
                            <div class="p-5 flex-1 flex flex-col">
                                <h3 class="font-display text-[24px] text-brand-black leading-none">{{ $car->name ?? $car->type }}</h3>
                                <div class="flex items-center gap-1 text-brand-gray-mid font-body text-[14px] mt-1 mb-4">
                                    <svg class="w-4 h-4 text-brand-gray-mid" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $car->location->name ?? 'Main Garage' }}
                                </div>

                                {{-- Pricing --}}
                                <div class="mt-auto pt-4 border-t border-gray-100 flex items-end justify-between mb-4">
                                    <div>
                                        <p class="font-display text-[28px] text-brand-accent leading-none">₹{{ number_format($car->price_per_day, 0) }}<span class="font-body text-[14px] text-brand-gray-mid"> / day</span></p>
                                        @php
                                            $days = \Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($end)) + 1;
                                            $total = $days * $car->price_per_day;
                                        @endphp
                                        <p class="font-body text-[14px] text-brand-black font-semibold mt-1">Total: ₹{{ number_format($total, 0) }}</p>
                                    </div>
                                </div>

                                <form method="POST" action="/bookings" class="w-full">
                                    @csrf
                                    <input type="hidden" name="car_id" value="{{ $car->id }}">
                                    <button type="submit" class="w-full h-[48px] bg-brand-black text-white font-display text-[16px] tracking-[1px] rounded-[12px] hover:bg-brand-accent transition-colors duration-300">
                                        BOOK THIS CAR
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

            @elseif(isset($cars) && $cars->count() === 0)
                <div class="h-full flex flex-col items-center justify-center py-20 text-center">
                    <div class="text-[80px] mb-4">🚗</div>
                    <h2 class="font-display text-[40px] text-brand-black">NO CARS AVAILABLE</h2>
                    <p class="font-body text-brand-gray-mid mt-2 mb-8 text-lg">Try different dates — we update our fleet daily</p>
                    <button onclick="document.getElementById('start_date').focus()" class="bg-brand-accent text-white px-8 py-3 rounded-full font-body font-semibold hover:shadow-lg hover:shadow-blue-200 transition-all">
                        Try Different Dates
                    </button>
                </div>

            @else
                <div class="h-full flex flex-col items-center justify-center py-20 text-center relative overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center z-0 pointer-events-none">
                        <div class="w-[400px] h-[400px] bg-brand-accent/5 rounded-full pulse-ring"></div>
                        <div class="absolute w-[300px] h-[300px] bg-brand-accent/10 rounded-full pulse-ring" style="animation-delay: -1.5s;"></div>
                    </div>
                    
                    <div class="relative z-10">
                        <svg class="w-40 h-40 mx-auto text-brand-gray-mid mb-6 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <h2 class="font-display text-[48px] text-brand-gray-mid/60 tracking-wider">YOUR PERFECT RIDE IS WAITING</h2>
                        <p class="font-body text-brand-gray-mid mt-2 text-lg">Enter your dates on the left to see available cars</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .fade-in-left {
        animation: fadeInLeft 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    @keyframes fadeInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    .car-card {
        opacity: 0;
        animation: fadeInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .pulse-ring {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0% { transform: scale(0.8); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(0.8); opacity: 0.5; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const summaryBox = document.getElementById('trip-summary');
    const summaryText = document.getElementById('trip-days-text');

    // Set min date for start_date to today
    const today = new Date().toISOString().split('T')[0];
    startInput.min = today;

    function updateEndDateMin() {
        if (startInput.value) {
            const startDate = new Date(startInput.value);
            const minEndDate = new Date(startDate);
            minEndDate.setDate(startDate.getDate() + 1);
            endInput.min = minEndDate.toISOString().split('T')[0];
            
            // If end date is before new min date, clear it
            if (endInput.value && new Date(endInput.value) < minEndDate) {
                endInput.value = '';
            }
        }
        updateSummary();
    }

    function updateSummary() {
        if (startInput.value && endInput.value) {
            const start = new Date(startInput.value);
            const end = new Date(endInput.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            summaryText.textContent = `${diffDays} days rental`;
            summaryBox.classList.remove('hidden');
            // Force reflow for fade in
            void summaryBox.offsetWidth;
            summaryBox.style.opacity = '1';
        } else {
            summaryBox.classList.add('hidden');
        }
    }

    startInput.addEventListener('change', updateEndDateMin);
    endInput.addEventListener('change', updateSummary);
    
    // Initial calls if values exist
    if (startInput.value) updateEndDateMin();
    if (startInput.value && endInput.value) updateSummary();
});
</script>
@endpush
