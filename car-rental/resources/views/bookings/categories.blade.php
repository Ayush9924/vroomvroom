@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-brand-gray-light pt-28 pb-20 px-4">
    <div class="max-w-[1200px] mx-auto px-6">
        
        {{-- PAGE HEADER --}}
        <div class="bg-brand-black rounded-[24px] p-[40px] mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="font-body text-brand-accent text-[12px] uppercase tracking-[3px] font-bold mb-2">STEP 2 OF 3</p>
                    <h1 class="font-display text-[52px] text-white leading-none mb-2">CHOOSE YOUR CATEGORY</h1>
                    <p class="font-body text-brand-gray-mid text-[16px]">What kind of drive are you looking for?</p>
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
                    <a href="/bookings/create" class="font-body text-brand-gray-mid text-[13px] hover:text-white transition-colors">
                        ← Change Dates
                    </a>
                </div>
            </div>
        </div>

        {{-- CATEGORY GRID --}}
        @if($categories->isEmpty())
            <div class="text-center py-20 bg-white rounded-[24px]">
                <h3 class="font-display text-[32px] text-brand-black mb-2">No categories found</h3>
                <a href="/admin" class="text-brand-accent font-body hover:underline">Add categories in Admin Panel</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $index => $category)
                    <a href="{{ route('categories.show', $category->slug) }}" 
                       class="category-card block bg-white rounded-[24px] overflow-hidden group hover:-translate-y-2 hover:shadow-xl transition-all duration-350 cursor-pointer"
                       style="animation-delay: {{ $index * 0.1 }}s;">
                        
                        {{-- TOP IMAGE AREA --}}
                        <div class="h-[200px] relative bg-gray-200">
                            @if($category->image_url)
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/70"></div>
                            
                            {{-- Availability Badge --}}
                            <div class="absolute top-4 right-4">
                                @if($start_date && $end_date)
                                    @php $availableCount = $category->getAvailableCarsCount($start_date, $end_date); @endphp
                                    @if($availableCount > 0)
                                        <div class="bg-green-500/90 backdrop-blur text-white px-3.5 py-1.5 rounded-[50px] font-body text-[13px] font-semibold shadow-sm">
                                            {{ $availableCount }} Available
                                        </div>
                                    @else
                                        <div class="bg-red-500/90 backdrop-blur text-white px-3.5 py-1.5 rounded-[50px] font-body text-[13px] font-semibold shadow-sm">
                                            Unavailable
                                        </div>
                                    @endif
                                @else
                                    <div class="bg-brand-accent/90 backdrop-blur text-white px-3.5 py-1.5 rounded-[50px] font-body text-[13px] font-semibold shadow-sm">
                                        {{ $category->cars_count }} Cars
                                    </div>
                                @endif
                            </div>

                            {{-- Icon --}}
                            <div class="absolute bottom-4 left-5 text-[40px] leading-none drop-shadow-lg">
                                {{ $category->icon }}
                            </div>
                        </div>

                        {{-- BOTTOM CONTENT --}}
                        <div class="p-[24px]">
                            <h2 class="font-display text-[28px] text-brand-black leading-none mb-1">{{ $category->name }}</h2>
                            <p class="font-body text-brand-gray-mid text-[14px] line-clamp-2 mb-4 h-10">{{ $category->description }}</p>
                            
                            <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                                <span class="font-body text-brand-gray-mid text-[13px]">{{ $category->cars_count }} cars in fleet</span>
                                <span class="font-body text-brand-accent text-[13px] font-semibold group-hover:translate-x-1 transition-transform">Explore →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection

@push('styles')
<style>
    .category-card {
        opacity: 0;
        animation: slideUpFade 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
