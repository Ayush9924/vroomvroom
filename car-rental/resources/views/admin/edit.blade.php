@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-brand-gray-light pt-28 pb-20 px-4">
    <div class="max-w-[1200px] mx-auto px-6">

        {{-- Flash / Validation Errors --}}
        @if ($errors->any())
            <div class="flash-message mb-6 bg-red-50/50 border-l-4 border-red-500 rounded-[12px] p-4 flex items-start gap-3 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <p class="font-body text-red-800 text-sm font-semibold mb-1">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li class="font-body text-red-700 text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- HERO HEADER --}}
        <div class="hero-header bg-brand-black rounded-[24px] p-[40px] md:px-[48px] mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-8 relative overflow-hidden shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 80% 20%,#3B6FFF 0%,transparent 50%);"></div>

            <div class="relative z-10 flex-1">
                <p class="font-body text-brand-accent text-[12px] uppercase tracking-[3px] font-bold mb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-brand-accent animate-pulse"></span>
                    FLEET MANAGEMENT
                </p>
                <h1 class="font-display text-[48px] md:text-[56px] text-white leading-none tracking-wide mb-1">EDIT CAR #{{ str_pad($car->id, 4, '0', STR_PAD_LEFT) }}</h1>
                <p class="font-body text-brand-gray-mid text-[16px]">
                    {{ $car->brand }} {{ $car->car_name ?? $car->type }} &mdash; Modify vehicle details below.
                </p>
            </div>

            <div class="relative z-10 flex items-center gap-4">
                {{-- Live Status Badge --}}
                @php $status = $car->getCurrentStatus(); @endphp
                <div class="bg-[#1a1a1a] rounded-[16px] py-[20px] px-[28px] text-center min-w-[140px] border border-white/5 shadow-inner">
                    @if($status === 'Sitting in Garage')
                        <p class="font-display text-[22px] text-[#22c55e] leading-none mb-1">GARAGE</p>
                        <p class="font-body text-brand-gray-mid text-[11px] uppercase tracking-wider">Current Status</p>
                    @elseif($status === 'Rented Out Right Now')
                        <p class="font-display text-[22px] text-[#ef4444] leading-none mb-1">RENTED</p>
                        <p class="font-body text-brand-gray-mid text-[11px] uppercase tracking-wider">Current Status</p>
                    @else
                        <p class="font-display text-[22px] text-[#f59e0b] leading-none mb-1">BOOKED</p>
                        <p class="font-body text-brand-gray-mid text-[11px] uppercase tracking-wider">Current Status</p>
                    @endif
                </div>
                <div class="bg-[#1a1a1a] rounded-[16px] py-[20px] px-[28px] text-center min-w-[140px] border border-white/5 shadow-inner">
                    <p class="font-display text-[22px] text-brand-accent leading-none mb-1">₹{{ number_format($car->price_per_day, 0) }}</p>
                    <p class="font-body text-brand-gray-mid text-[11px] uppercase tracking-wider">Per Day</p>
                </div>
            </div>
        </div>

        {{-- BREADCRUMB --}}
        <div class="flex items-center gap-2 mb-8 font-body text-[13px] text-brand-gray-mid">
            <a href="/admin" class="hover:text-brand-accent transition-colors">Fleet Command Center</a>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-brand-black font-semibold">Edit Car #{{ str_pad($car->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>

        {{-- FORM CARD --}}
        <form method="POST" action="/admin/cars/{{ $car->id }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-[24px] p-[32px] shadow-sm border border-gray-100 mb-6">

                {{-- Section Header --}}
                <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-5">
                    <div>
                        <h2 class="font-display text-[32px] text-brand-black leading-none mb-1">CLASSIFICATION & IDENTITY</h2>
                        <p class="font-body text-brand-gray-mid text-[14px]">Core vehicle identification and category</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-brand-accent/10 flex items-center justify-center text-brand-accent">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-[20px] mb-6">
                    {{-- Category --}}
                    <div class="flex flex-col">
                        <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                            <span class="text-brand-accent text-lg leading-none">•</span> Category
                        </label>
                        <select name="category_id" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors bg-white hover:border-gray-300">
                            <option value="">Uncategorized</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $car->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->icon }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Type --}}
                    <div class="flex flex-col">
                        <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                            <span class="text-brand-accent text-lg leading-none">•</span> Vehicle Class (Type)
                        </label>
                        <input type="text" name="type" value="{{ old('type', $car->type) }}" required placeholder="e.g. SUV, Sedan"
                            class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                    </div>

                    {{-- Brand --}}
                    <div class="flex flex-col">
                        <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                            <span class="text-brand-accent text-lg leading-none">•</span> Brand Make
                        </label>
                        <input type="text" name="brand" value="{{ old('brand', $car->brand) }}" placeholder="e.g. Mercedes-Benz"
                            class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                    </div>

                    {{-- Model Name --}}
                    <div class="flex flex-col">
                        <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                            <span class="text-brand-accent text-lg leading-none">•</span> Model Name
                        </label>
                        <input type="text" name="car_name" value="{{ old('car_name', $car->car_name) }}" placeholder="e.g. G-Class G63"
                            class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                    </div>
                </div>

                <div class="w-full h-px bg-gray-100 mb-6"></div>

                {{-- Technical Specs --}}
                <div class="mb-2">
                    <h3 class="font-body text-[11px] uppercase tracking-[2px] text-brand-gray-mid font-bold mb-4">Technical Specifications</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-[20px]">
                        {{-- Year --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2">Year</label>
                            <input type="number" name="year" value="{{ old('year', $car->year) }}" placeholder="2024" min="2000" max="2030"
                                class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                        </div>

                        {{-- Transmission --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2">Transmission</label>
                            <select name="transmission" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors bg-white hover:border-gray-300">
                                <option value="">Select...</option>
                                <option value="Automatic" {{ old('transmission', $car->transmission) == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                <option value="Manual"    {{ old('transmission', $car->transmission) == 'Manual'    ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>

                        {{-- Fuel Type --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2">Fuel Type</label>
                            <select name="fuel_type" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors bg-white hover:border-gray-300">
                                <option value="">Select...</option>
                                <option value="Petrol"   {{ old('fuel_type', $car->fuel_type) == 'Petrol'   ? 'selected' : '' }}>Petrol</option>
                                <option value="Diesel"   {{ old('fuel_type', $car->fuel_type) == 'Diesel'   ? 'selected' : '' }}>Diesel</option>
                                <option value="Electric" {{ old('fuel_type', $car->fuel_type) == 'Electric' ? 'selected' : '' }}>Electric</option>
                                <option value="Hybrid"   {{ old('fuel_type', $car->fuel_type) == 'Hybrid'   ? 'selected' : '' }}>Hybrid</option>
                            </select>
                        </div>

                        {{-- Seats --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2">Seats</label>
                            <input type="number" name="seats" value="{{ old('seats', $car->seats ?? 5) }}" min="2" max="8"
                                class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                        </div>

                        {{-- Mileage --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2">Mileage / Range</label>
                            <input type="text" name="mileage" value="{{ old('mileage', $car->mileage) }}" placeholder="e.g. 15 km/l"
                                class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rental Terms Card --}}
            <div class="bg-white rounded-[24px] p-[32px] shadow-sm border border-gray-100 mb-6">
                <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-5">
                    <div>
                        <h2 class="font-display text-[32px] text-brand-black leading-none mb-1">RENTAL TERMS & AVAILABILITY</h2>
                        <p class="font-body text-brand-gray-mid text-[14px]">Pricing, location and manual availability override</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#22c55e]/10 flex items-center justify-center text-[#22c55e]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-[20px]">
                    {{-- Price --}}
                    <div class="flex flex-col">
                        <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                            <span class="text-[#22c55e] text-lg leading-none">•</span> Price Per Day
                        </label>
                        <div class="relative">
                            <span class="absolute left-[16px] top-1/2 -translate-y-1/2 font-body text-brand-gray-mid font-semibold">₹</span>
                            <input type="number" step="0.01" name="price_per_day" value="{{ old('price_per_day', $car->price_per_day) }}" required placeholder="55000"
                                class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] pl-[32px] pr-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="flex flex-col">
                        <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                            <span class="text-[#ef4444] text-lg leading-none">•</span> Primary Location
                        </label>
                        <select name="location_id" required class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors bg-white hover:border-gray-300">
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ $car->location_id == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Availability --}}
                    <div class="flex flex-col">
                        <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                            <span class="text-[#f59e0b] text-lg leading-none">•</span> Manual Availability
                        </label>
                        <select name="is_available" required class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors bg-white hover:border-gray-300">
                            <option value="1" {{ $car->is_available ? 'selected' : '' }}>✅ Available (Online)</option>
                            <option value="0" {{ !$car->is_available ? 'selected' : '' }}>🔴 Unavailable (Manual Offline)</option>
                        </select>
                        <p class="font-body text-[11px] text-brand-gray-mid mt-2 ml-1">Use "Offline" if car is broken or needs service.</p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <button type="submit"
                    class="w-full sm:w-auto flex-1 h-[56px] bg-brand-black text-white font-display text-[18px] tracking-[2px] rounded-[14px] hover:bg-brand-accent hover:shadow-[0_4px_20px_rgba(59,111,255,0.4)] transition-all duration-300 flex items-center justify-center gap-3 group px-10">
                    SAVE CHANGES
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>

                <a href="/admin"
                    class="w-full sm:w-auto flex-shrink-0 h-[56px] px-10 border-[1.5px] border-[#e0e0e0] text-brand-gray-mid font-body text-[14px] font-semibold rounded-[14px] hover:border-brand-black hover:text-brand-black transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Cancel & Go Back
                </a>

                {{-- Danger Zone: Delete --}}
                <form method="POST" action="/admin/cars/{{ $car->id }}" class="w-full sm:w-auto ml-auto"
                      onsubmit="return confirm('⚠️ Permanently delete {{ addslashes($car->brand . ' ' . ($car->car_name ?? $car->type)) }}? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full h-[56px] px-8 bg-red-50 border border-red-200 text-red-500 font-body text-[13px] font-semibold rounded-[14px] hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete Vehicle
                    </button>
                </form>
            </div>
        </form>

    </div>
</div>
@endsection

@push('styles')
<style>
    .hero-header {
        opacity: 0;
        animation: slideUpFade 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    .flash-message {
        animation: slideDownFade 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideDownFade {
        from { opacity: 0; transform: translateY(-16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    input:focus, select:focus {
        box-shadow: 0 0 0 3px rgba(59,111,255,0.08);
    }
</style>
@endpush
