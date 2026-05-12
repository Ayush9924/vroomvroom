@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-brand-gray-light pt-28 pb-20 px-4">
    <div class="max-w-[1200px] mx-auto px-6">
        
        {{-- PAGE HEADER --}}
        <div class="bg-brand-black rounded-[24px] p-[40px] mb-6">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div>
                    <a href="{{ route('categories.index') }}" class="font-body text-brand-gray-mid text-[13px] hover:text-white transition-colors mb-3 inline-block">
                        Categories / <span class="text-white">{{ $category->name }}</span>
                    </a>
                    <p class="font-body text-brand-accent text-[12px] uppercase tracking-[3px] font-bold mb-2">STEP 3 OF 3</p>
                    <h1 class="font-display text-[52px] text-white leading-none mb-2">{{ strtoupper($category->name) }} CARS</h1>
                    <p class="font-body text-brand-gray-mid text-[16px]">{{ $category->description }}</p>
                </div>
                
                <div class="flex flex-col items-end gap-3">
                    @if($start_date && $end_date)
                        <div class="bg-[#1a1a1a] border border-white/10 rounded-[50px] px-5 py-2.5 flex items-center gap-2">
                            <span class="text-white text-lg">📅</span>
                            <span class="font-body text-white font-medium text-[14px]">
                                {{ \Carbon\Carbon::parse($start_date)->format('M d') }} → {{ \Carbon\Carbon::parse($end_date)->format('M d') }}
                            </span>
                        </div>
                    @endif
                    <div class="bg-[#1a1a1a] border border-white/10 rounded-[50px] px-4 py-1.5 font-body text-brand-gray-mid text-[12px]">
                        {{ $cars->count() }} cars found
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div class="flex items-center gap-3 overflow-x-auto pb-6 scrollbar-hide" id="filterBar">
            <button class="filter-btn active whitespace-nowrap px-5 py-2 rounded-[50px] font-body text-[14px] bg-brand-accent text-white transition-colors" data-filter="all">All</button>
            <button class="filter-btn whitespace-nowrap px-5 py-2 rounded-[50px] font-body text-[14px] bg-white border border-gray-200 text-brand-black hover:bg-gray-50 transition-colors" data-filter="transmission:Automatic">Automatic</button>
            <button class="filter-btn whitespace-nowrap px-5 py-2 rounded-[50px] font-body text-[14px] bg-white border border-gray-200 text-brand-black hover:bg-gray-50 transition-colors" data-filter="transmission:Manual">Manual</button>
            <button class="filter-btn whitespace-nowrap px-5 py-2 rounded-[50px] font-body text-[14px] bg-white border border-gray-200 text-brand-black hover:bg-gray-50 transition-colors" data-filter="fuel:Petrol">Petrol</button>
            <button class="filter-btn whitespace-nowrap px-5 py-2 rounded-[50px] font-body text-[14px] bg-white border border-gray-200 text-brand-black hover:bg-gray-50 transition-colors" data-filter="fuel:Diesel">Diesel</button>
            <button class="filter-btn whitespace-nowrap px-5 py-2 rounded-[50px] font-body text-[14px] bg-white border border-gray-200 text-brand-black hover:bg-gray-50 transition-colors" data-filter="fuel:Electric">Electric</button>
        </div>

        {{-- CARS GRID --}}
        @if($cars->isEmpty())
            <div class="text-center py-24 bg-white rounded-[24px]">
                <div class="text-[64px] mb-4 opacity-50">{{ $category->icon }}</div>
                <h3 class="font-display text-[32px] text-brand-black mb-2">NO CARS IN THIS CATEGORY</h3>
                <p class="font-body text-brand-gray-mid mb-6">Check back soon or explore other categories</p>
                <a href="{{ route('categories.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#f0f0ec] hover:bg-gray-200 text-brand-black font-body text-[14px] font-medium rounded-[50px] transition-colors">
                    ← Back to Categories
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="carsGrid">
                @foreach($cars as $car)
                    <div class="car-card bg-white rounded-[20px] shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300"
                         data-transmission="{{ $car->transmission }}"
                         data-fuel="{{ $car->fuel_type }}">
                        
                        {{-- TOP IMAGE --}}
                        <div class="h-[220px] relative rounded-t-[20px] overflow-hidden bg-gray-100">
                            @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ $category->image_url ?? 'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=600' }}" class="w-full h-full object-cover">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                            {{-- Status Badge --}}
                            <div class="absolute top-4 left-4">
                                @if($start_date && $end_date)
                                    <div class="bg-green-500/90 backdrop-blur text-white px-3 py-1.5 rounded-[50px] font-body text-[12px] font-semibold">✓ Available for Dates</div>
                                @else
                                    @php $status = $car->getCurrentStatus(); @endphp
                                    @if($status === 'Sitting in Garage')
                                        <div class="bg-green-500/90 backdrop-blur text-white px-3 py-1.5 rounded-[50px] font-body text-[12px] font-semibold">✓ Available Now</div>
                                    @elseif($status === 'Rented Out Right Now')
                                        <div class="bg-red-500/90 backdrop-blur text-white px-3 py-1.5 rounded-[50px] font-body text-[12px] font-semibold">✗ Rented Currently</div>
                                    @elseif($status === 'Booked for Future')
                                        <div class="bg-amber-500/90 backdrop-blur text-white px-3 py-1.5 rounded-[50px] font-body text-[12px] font-semibold">⏳ Booked Soon</div>
                                    @endif
                                @endif
                            </div>

                            {{-- Category Badge --}}
                            <div class="absolute top-4 right-4">
                                <div class="bg-brand-accent/90 backdrop-blur text-white px-3 py-1.5 rounded-[50px] font-body text-[12px] font-semibold">
                                    {{ $category->name }}
                                </div>
                            </div>
                        </div>

                        {{-- CAR BODY --}}
                        <div class="p-[20px]">
                            <div class="mb-4">
                                <h2 class="font-display text-[22px] text-brand-black leading-none mb-1">
                                    {{ $car->car_name ?? $car->type }}
                                </h2>
                                <p class="font-body text-[13px] text-brand-gray-mid">
                                    {{ $car->brand ?? 'Brand' }} {{ $car->year ? '• '.$car->year : '' }}
                                </p>
                            </div>

                            {{-- Specs Chips --}}
                            <div class="flex flex-wrap gap-2 mb-5">
                                <div class="bg-[#f0f0ec] text-brand-black px-3 py-1 rounded-[50px] font-body text-[12px] flex items-center gap-1.5">
                                    🪑 {{ $car->seats ?? 5 }} Seats
                                </div>
                                @if($car->transmission)
                                <div class="bg-[#f0f0ec] text-brand-black px-3 py-1 rounded-[50px] font-body text-[12px] flex items-center gap-1.5">
                                    ⚙️ {{ $car->transmission }}
                                </div>
                                @endif
                                @if($car->fuel_type)
                                <div class="bg-[#f0f0ec] text-brand-black px-3 py-1 rounded-[50px] font-body text-[12px] flex items-center gap-1.5">
                                    ⛽ {{ $car->fuel_type }}
                                </div>
                                @endif
                            </div>

                            {{-- Pricing --}}
                            <div class="flex items-end justify-between mb-5">
                                <div>
                                    <div class="flex items-baseline gap-1">
                                        <span class="font-display text-[28px] text-brand-accent leading-none">₹{{ number_format($car->price_per_day, 0) }}</span>
                                        <span class="font-body text-[12px] text-brand-gray-mid">/day</span>
                                    </div>
                                    @if($start_date && $end_date)
                                        @php
                                            $days = \Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) + 1;
                                        @endphp
                                        <p class="font-body text-[14px] text-brand-black font-semibold mt-1">
                                            Total: ₹{{ number_format($car->price_per_day * $days, 0) }}
                                        </p>
                                        <p class="font-body text-[12px] text-brand-gray-mid">for {{ $days }} days</p>
                                    @endif
                                </div>
                            </div>

                            {{-- BOOK BUTTON --}}
                            @if($start_date && $end_date)
                                @if($car->is_available)
                                    <form action="/bookings" method="POST">
                                        @csrf
                                        <input type="hidden" name="car_id" value="{{ $car->id }}">
                                        {{-- Dates are already in session --}}
                                        <button type="submit" class="w-full h-[48px] bg-brand-black text-white font-display text-[16px] tracking-[1px] rounded-[12px] hover:bg-brand-accent transition-colors flex items-center justify-center gap-2 group shadow-lg shadow-black/10">
                                            BOOK THIS CAR 
                                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full h-[48px] bg-gray-200 text-gray-400 font-display text-[16px] tracking-[1px] rounded-[12px] cursor-not-allowed flex items-center justify-center">
                                        CAR IS OFFLINE
                                    </button>
                                @endif
                            @else
                                <a href="/bookings/create" class="w-full h-[48px] bg-brand-black text-white font-display text-[16px] tracking-[1px] rounded-[12px] hover:bg-brand-accent transition-colors flex items-center justify-center gap-2 group shadow-lg shadow-black/10">
                                    PICK DATES TO BOOK
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.car-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active state
            filterBtns.forEach(b => {
                b.classList.remove('bg-brand-accent', 'text-white', 'active');
                b.classList.add('bg-white', 'text-brand-black');
            });
            btn.classList.remove('bg-white', 'text-brand-black');
            btn.classList.add('bg-brand-accent', 'text-white', 'active');

            const filterValue = btn.dataset.filter;
            
            cards.forEach(card => {
                if (filterValue === 'all') {
                    card.style.display = 'block';
                    return;
                }

                const [type, val] = filterValue.split(':');
                if (card.dataset[type] === val) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush
