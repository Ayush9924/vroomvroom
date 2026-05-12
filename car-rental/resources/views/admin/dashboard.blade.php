@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-brand-gray-light pt-28 pb-20 px-4">
    <div class="max-w-[1200px] mx-auto px-6">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="flash-message mb-6 bg-green-50/50 border-l-4 border-green-500 rounded-[12px] p-4 flex items-center gap-3 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="font-body text-green-800 text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="flash-message mb-6 bg-red-50/50 border-l-4 border-red-500 rounded-[12px] p-4 flex items-center gap-3 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <p class="font-body text-red-800 text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- SECTION 1 — ADMIN HERO HEADER --}}
        <div class="hero-header bg-brand-black rounded-[24px] p-[40px] md:px-[48px] mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-8 relative overflow-hidden shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 80% 20%,#3B6FFF 0%,transparent 50%);"></div>
            
            <div class="relative z-10 flex-1">
                <p class="font-body text-brand-accent text-[12px] uppercase tracking-[3px] font-bold mb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-brand-accent animate-pulse"></span>
                    CONTROL ROOM
                </p>
                <h1 class="font-display text-[56px] text-white leading-none tracking-wide mb-1">FLEET COMMAND CENTER</h1>
                <p class="font-body text-brand-gray-mid text-[16px]">Manage your entire fleet and categories from one place.</p>
            </div>

            <div class="relative z-10 hidden md:flex items-center gap-4">
                <div class="bg-[#1a1a1a] rounded-[16px] py-[20px] px-[28px] text-center min-w-[120px] border border-white/5 stat-card shadow-inner">
                    <p class="font-display text-[36px] text-white leading-none mb-1">{{ $cars->count() }}</p>
                    <p class="font-body text-brand-gray-mid text-[12px] uppercase tracking-wider">TOTAL FLEET</p>
                </div>
                <div class="bg-[#1a1a1a] rounded-[16px] py-[20px] px-[28px] text-center min-w-[120px] border border-white/5 stat-card shadow-inner" style="animation-delay: 0.1s;">
                    <p class="font-display text-[36px] text-white leading-none mb-1">{{ $cars->where('is_available', true)->count() }}</p>
                    <p class="font-body text-brand-gray-mid text-[12px] uppercase tracking-wider">AVAILABLE</p>
                </div>
                <div class="bg-[#1a1a1a] rounded-[16px] py-[20px] px-[28px] text-center min-w-[120px] border border-white/5 stat-card shadow-inner" style="animation-delay: 0.2s;">
                    @php
                        $rentedCount = 0;
                        foreach($cars as $c) {
                            if($c->getCurrentStatus() === 'Rented Out Right Now') $rentedCount++;
                        }
                    @endphp
                    <p class="font-display text-[36px] text-white leading-none mb-1">{{ $rentedCount }}</p>
                    <p class="font-body text-brand-gray-mid text-[12px] uppercase tracking-wider">RENTED NOW</p>
                </div>
            </div>
        </div>

        {{-- SECTION 2 — FLEET STATS BAR --}}
        @php
            $garageCount = 0;
            $bookedCount = 0;
            foreach($cars as $c) {
                $st = $c->getCurrentStatus();
                if($st === 'Sitting in Garage') $garageCount++;
                if($st === 'Booked for Future') $bookedCount++;
            }
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-[16px] p-[20px] px-[24px] shadow-sm hover:shadow-md transition-shadow border-l-[4px] border-[#22c55e] flex items-center justify-between group" style="background-color: rgba(34,197,94,0.02);">
                <div>
                    <p class="font-display text-[32px] text-[#22c55e] leading-none mb-1">{{ $garageCount }}</p>
                    <p class="font-body text-brand-gray-mid text-[13px]">Sitting in Garage</p>
                </div>
                <div class="text-[#22c55e] opacity-80 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-[16px] p-[20px] px-[24px] shadow-sm hover:shadow-md transition-shadow border-l-[4px] border-[#ef4444] flex items-center justify-between group" style="background-color: rgba(239,68,68,0.02);">
                <div>
                    <p class="font-display text-[32px] text-[#ef4444] leading-none mb-1">{{ $rentedCount }}</p>
                    <p class="font-body text-brand-gray-mid text-[13px]">Rented Out Right Now</p>
                </div>
                <div class="text-[#ef4444] opacity-80 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-[16px] p-[20px] px-[24px] shadow-sm hover:shadow-md transition-shadow border-l-[4px] border-[#f59e0b] flex items-center justify-between group" style="background-color: rgba(245,158,11,0.02);">
                <div>
                    <p class="font-display text-[32px] text-[#f59e0b] leading-none mb-1">{{ $bookedCount }}</p>
                    <p class="font-body text-brand-gray-mid text-[13px]">Booked for Future</p>
                </div>
                <div class="text-[#f59e0b] opacity-80 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>

        {{-- SECTION 3 — ADD NEW CAR FORM CARD --}}
        <div class="bg-white rounded-[24px] p-[32px] shadow-sm mb-10 border border-gray-100">
            <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-5">
                <div>
                    <h2 class="font-display text-[32px] text-brand-black leading-none mb-1">ADD NEW CAR</h2>
                    <p class="font-body text-brand-gray-mid text-[14px]">Expand your fleet with detailed specifications</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-brand-accent/10 flex items-center justify-center text-brand-accent">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
            </div>

            <form method="POST" action="/admin/cars">
                @csrf
                
                {{-- Group 1: Classification & Basic Info --}}
                <div class="mb-6">
                    <h3 class="font-body text-[11px] uppercase tracking-[2px] text-brand-gray-mid font-bold mb-4">1. Classification & Basic Info</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-[20px]">
                        {{-- Category --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                                <span class="text-brand-accent text-lg leading-none">•</span> Category
                            </label>
                            <select name="category_id" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors bg-white hover:border-gray-300">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Type/Class --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                                <span class="text-brand-accent text-lg leading-none">•</span> Vehicle Class (Type)
                            </label>
                            <input type="text" name="type" required placeholder="e.g. SUV, Sedan" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                        </div>

                        {{-- Brand --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                                <span class="text-brand-accent text-lg leading-none">•</span> Brand Make
                            </label>
                            <input type="text" name="brand" placeholder="e.g. Mercedes-Benz" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                        </div>

                        {{-- Model Name --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                                <span class="text-brand-accent text-lg leading-none">•</span> Model Name
                            </label>
                            <input type="text" name="car_name" placeholder="e.g. G-Class G63" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                        </div>
                    </div>
                </div>

                <div class="w-full h-px bg-gray-100 mb-6"></div>

                {{-- Group 2: Technical Specifications --}}
                <div class="mb-6">
                    <h3 class="font-body text-[11px] uppercase tracking-[2px] text-brand-gray-mid font-bold mb-4">2. Technical Specifications</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-[20px]">
                        {{-- Year --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2">Year</label>
                            <input type="number" name="year" placeholder="2024" min="2000" max="2026" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                        </div>

                        {{-- Transmission --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2">Transmission</label>
                            <select name="transmission" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors bg-white hover:border-gray-300">
                                <option value="">Select...</option>
                                <option value="Automatic">Automatic</option>
                                <option value="Manual">Manual</option>
                            </select>
                        </div>

                        {{-- Fuel Type --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2">Fuel Type</label>
                            <select name="fuel_type" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors bg-white hover:border-gray-300">
                                <option value="">Select...</option>
                                <option value="Petrol">Petrol</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Electric">Electric</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>

                        {{-- Seats --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2">Seats</label>
                            <input type="number" name="seats" value="5" min="2" max="8" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                        </div>

                        {{-- Mileage --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2">Mileage/Range</label>
                            <input type="text" name="mileage" placeholder="e.g. 15 km/l or 400km" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                        </div>
                    </div>
                </div>

                <div class="w-full h-px bg-gray-100 mb-6"></div>

                {{-- Group 3: Rental & Location Details --}}
                <div class="mb-6">
                    <h3 class="font-body text-[11px] uppercase tracking-[2px] text-brand-gray-mid font-bold mb-4">3. Rental Terms & Location</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-[20px]">
                        {{-- Price Per Day --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                                <span class="text-[#22c55e] text-lg leading-none">•</span> Price Per Day
                            </label>
                            <div class="relative">
                                <span class="absolute left-[16px] top-1/2 -translate-y-1/2 font-body text-brand-gray-mid font-semibold">₹</span>
                                <input type="number" step="0.01" name="price_per_day" required placeholder="55000" class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] pl-[32px] pr-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors hover:border-gray-300">
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="flex flex-col">
                            <label class="font-body text-brand-black text-[13px] font-semibold mb-2 flex items-center gap-2">
                                <span class="text-[#ef4444] text-lg leading-none">•</span> Primary Location
                            </label>
                            <select name="location_id" required class="h-[48px] w-full border-[1.5px] border-[#e0e0e0] rounded-[12px] px-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors bg-white hover:border-gray-300">
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Submit --}}
                        <div class="flex flex-col justify-end">
                            <button type="submit" class="h-[48px] w-full bg-brand-black text-white font-display text-[16px] tracking-[2px] rounded-[12px] hover:bg-brand-accent hover:shadow-[0_4px_15px_rgba(59,111,255,0.4)] transition-all duration-300 flex items-center justify-center gap-2 group">
                                ADD TO FLEET
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- SECTION 4 — CATEGORY MANAGEMENT (MOVED UP FOR BETTER FLOW) --}}
        <div class="mb-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-[28px] text-brand-black leading-none">FLEET CATEGORIES</h2>
                <div class="bg-[#1a1a1a] text-white px-3 py-1 rounded-full font-body text-[12px]">{{ $categories->count() }} active</div>
            </div>
            
            @if(isset($categories) && $categories->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($categories as $cat)
                        <div class="bg-white rounded-[20px] p-[20px] border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 rounded-[16px] bg-[#f8f8f8] border border-gray-100 flex items-center justify-center text-[28px] group-hover:scale-110 transition-transform">
                                    {{ $cat->icon }}
                                </div>
                                <span class="bg-brand-accent/10 text-brand-accent px-3 py-1 rounded-[50px] font-body text-[12px] font-semibold">
                                    {{ $cat->cars->count() }} Cars
                                </span>
                            </div>
                            <div>
                                <h3 class="font-display text-[22px] text-brand-black leading-none mb-1">{{ $cat->name }}</h3>
                                <p class="font-body text-[13px] text-brand-gray-mid line-clamp-1">{{ $cat->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-[20px] p-8 text-center border border-gray-100">
                    <p class="font-body text-brand-gray-mid">No categories defined yet.</p>
                </div>
            @endif
        </div>

        {{-- SECTION 5 — FLEET MANAGEMENT TABLE --}}
        <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden mb-10">
            <div class="p-[28px] border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#fafafa]">
                <div>
                    <h2 class="font-display text-[28px] text-brand-black leading-none mb-1">MANAGE INVENTORY</h2>
                    <p class="font-body text-[13px] text-brand-gray-mid">View and edit specific vehicles in your fleet</p>
                </div>
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-brand-gray-mid" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" id="tableSearch" placeholder="Search by model or brand..."
                        class="w-full md:w-[280px] h-[44px] border-[1.5px] border-[#e0e0e0] rounded-[50px] pl-[36px] pr-[16px] font-body text-[14px] focus:outline-none focus:border-brand-accent transition-colors bg-white shadow-sm hover:border-gray-300">
                </div>
            </div>

            @if($cars->isEmpty())
                <div class="p-16 text-center">
                    <svg class="w-24 h-24 mx-auto text-brand-gray-mid/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="font-display text-[32px] text-brand-black mb-1">FLEET IS EMPTY</h3>
                    <p class="font-body text-brand-gray-mid">Add your first vehicle using the form above</p>
                </div>
            @else
                {{-- DESKTOP TABLE --}}
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full border-collapse" id="fleetTable">
                        <thead class="bg-white border-b border-[#e0e0e0]">
                            <tr>
                                <th class="font-body text-[11px] uppercase tracking-[1.5px] text-brand-gray-mid py-[18px] px-[24px] text-center w-16">#</th>
                                <th class="font-body text-[11px] uppercase tracking-[1.5px] text-brand-gray-mid py-[18px] px-[24px] text-left">Vehicle Info</th>
                                <th class="font-body text-[11px] uppercase tracking-[1.5px] text-brand-gray-mid py-[18px] px-[24px] text-left">Classification</th>
                                <th class="font-body text-[11px] uppercase tracking-[1.5px] text-brand-gray-mid py-[18px] px-[24px] text-left">Rate</th>
                                <th class="font-body text-[11px] uppercase tracking-[1.5px] text-brand-gray-mid py-[18px] px-[24px] text-center">System Status</th>
                                <th class="font-body text-[11px] uppercase tracking-[1.5px] text-brand-gray-mid py-[18px] px-[24px] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach ($cars as $index => $car)
                                <tr class="hover:bg-brand-gray-light/30 transition-colors duration-200 table-row-item group" style="animation-delay: {{ $index * 0.05 }}s;">
                                    {{-- COL 1: # --}}
                                    <td class="py-[20px] px-[24px] text-center font-body text-brand-gray-mid font-medium text-[13px]">{{ $loop->iteration }}</td>
                                    
                                    {{-- COL 2: Vehicle Info --}}
                                    <td class="py-[20px] px-[24px]">
                                        <div class="flex items-center gap-4">
                                            @if($car->image ?? false)
                                                <img src="{{ asset('storage/' . $car->image) }}" class="w-12 h-12 rounded-[10px] object-cover border border-black/5 shadow-sm">
                                            @else
                                                <div class="w-12 h-12 rounded-[10px] bg-[#f5f5f5] flex items-center justify-center text-brand-gray-mid border border-black/5 shadow-sm">
                                                    <svg class="w-5 h-5 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h10l2-2z"></path></svg>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-display text-[18px] tracking-wide text-brand-black leading-none mb-1 car-name-cell">
                                                    {{ $car->brand }} {{ $car->car_name ?? $car->name ?? $car->type }}
                                                </p>
                                                <div class="flex items-center gap-2">
                                                    <p class="font-body text-[12px] text-brand-gray-mid">ID: #{{ str_pad($car->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                    @if($car->year)
                                                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                                        <p class="font-body text-[12px] text-brand-gray-mid">{{ $car->year }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- COL 3: Classification (Category & Type) --}}
                                    <td class="py-[20px] px-[24px]">
                                        <div class="flex flex-col items-start gap-1.5">
                                            @if($car->category)
                                                <span class="inline-flex items-center gap-1.5 px-[12px] py-[4px] rounded-[6px] font-body text-[12px] font-semibold bg-gray-100 text-brand-black border border-gray-200">
                                                    <span>{{ $car->category->icon }}</span> {{ $car->category->name }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-[12px] py-[4px] rounded-[6px] font-body text-[12px] font-medium bg-gray-50 text-gray-500 border border-gray-200">
                                                    Uncategorized
                                                </span>
                                            @endif
                                            <p class="font-body text-[11px] text-brand-gray-mid ml-1">Type: {{ $car->type }}</p>
                                        </div>
                                    </td>

                                    {{-- COL 4: Price --}}
                                    <td class="py-[20px] px-[24px]">
                                        <p class="font-display text-[22px] text-brand-accent leading-none mb-0.5">₹{{ number_format($car->price_per_day, 0) }}</p>
                                        <p class="font-body text-[11px] text-brand-gray-mid font-medium uppercase tracking-wider">per day</p>
                                    </td>

                                    {{-- COL 5: Availability & Live Status --}}
                                    <td class="py-[20px] px-[24px] text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            @php $status = $car->getCurrentStatus(); @endphp
                                            
                                            @if($status === 'Sitting in Garage')
                                                <span class="inline-flex items-center gap-1.5 px-[12px] py-[4px] rounded-[50px] font-body text-[12px] font-semibold bg-green-50 text-green-600 border border-green-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Garage
                                                </span>
                                            @elseif($status === 'Rented Out Right Now')
                                                <span class="inline-flex items-center gap-1.5 px-[12px] py-[4px] rounded-[50px] font-body text-[12px] font-semibold bg-red-50 text-red-600 border border-red-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 pulse-dot"></span> Rented
                                                </span>
                                            @elseif($status === 'Booked for Future')
                                                <span class="inline-flex items-center gap-1.5 px-[12px] py-[4px] rounded-[50px] font-body text-[12px] font-semibold bg-amber-50 text-amber-600 border border-amber-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Booked
                                                </span>
                                            @endif
                                            
                                            @if(!$car->is_available)
                                                <span class="font-body text-[10px] text-red-500 uppercase tracking-wider font-bold">MANUAL OFFLINE</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- COL 6: Actions --}}
                                    <td class="py-[20px] px-[24px] text-right whitespace-nowrap">
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-end gap-2">
                                            <a href="/admin/cars/{{ $car->id }}/edit" class="w-9 h-9 rounded-[8px] bg-gray-50 border border-gray-200 text-brand-black flex items-center justify-center hover:bg-brand-accent hover:text-white hover:border-brand-accent transition-colors" title="Edit Vehicle">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            
                                            <form method="POST" action="/admin/cars/{{ $car->id }}" class="inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Permanently delete {{ $car->brand }} {{ $car->car_name }} from the fleet?')" class="w-9 h-9 rounded-[8px] bg-red-50 border border-red-100 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white hover:border-red-500 transition-colors cursor-pointer" title="Delete Vehicle">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE CARDS --}}
                <div class="block lg:hidden p-4 space-y-4 bg-gray-50/50">
                    @foreach ($cars as $car)
                        <div class="bg-white border border-gray-100 rounded-[20px] p-[20px] shadow-sm mobile-card-item">
                            <div class="flex justify-between items-start mb-4 pb-4 border-b border-gray-50">
                                <div class="flex gap-3 items-center">
                                    @if($car->image ?? false)
                                        <img src="{{ asset('storage/' . $car->image) }}" class="w-12 h-12 rounded-[10px] object-cover border border-black/5">
                                    @else
                                        <div class="w-12 h-12 rounded-[10px] bg-gray-100 flex items-center justify-center text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h10l2-2z"></path></svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-display text-[18px] text-brand-black leading-none mb-1 car-name-cell">{{ $car->brand }} {{ $car->car_name ?? $car->name ?? $car->type }}</p>
                                        <p class="font-body text-[12px] text-brand-gray-mid">ID: #{{ str_pad($car->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-display text-[22px] text-brand-accent leading-none mb-0.5">₹{{ number_format($car->price_per_day, 0) }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-2 mb-5">
                                @if($car->category)
                                    <span class="px-[10px] py-[4px] rounded-[6px] font-body text-[11px] font-semibold bg-gray-100 text-brand-black border border-gray-200">
                                        {{ $car->category->icon }} {{ $car->category->name }}
                                    </span>
                                @endif

                                @php $status = $car->getCurrentStatus(); @endphp
                                @if($status === 'Sitting in Garage')
                                    <span class="px-[10px] py-[4px] rounded-[6px] font-body text-[11px] font-semibold bg-green-50 text-green-600 border border-green-200">Garage</span>
                                @elseif($status === 'Rented Out Right Now')
                                    <span class="px-[10px] py-[4px] rounded-[6px] font-body text-[11px] font-semibold bg-red-50 text-red-600 border border-red-200">Rented</span>
                                @elseif($status === 'Booked for Future')
                                    <span class="px-[10px] py-[4px] rounded-[6px] font-body text-[11px] font-semibold bg-amber-50 text-amber-600 border border-amber-200">Booked</span>
                                @endif
                                
                                @if(!$car->is_available)
                                    <span class="px-[10px] py-[4px] rounded-[6px] font-body text-[11px] font-bold bg-red-100 text-red-600 border border-red-200">OFFLINE</span>
                                @endif
                            </div>

                            <div class="flex gap-2 w-full pt-4 border-t border-gray-50">
                                <a href="/admin/cars/{{ $car->id }}/edit" class="flex-1 flex items-center justify-center gap-2 py-[12px] rounded-[10px] bg-gray-50 border border-gray-200 text-brand-black font-body text-[13px] font-semibold hover:bg-brand-accent hover:text-white hover:border-brand-accent transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Edit
                                </a>
                                <form method="POST" action="/admin/cars/{{ $car->id }}" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this car?')" class="w-full flex items-center justify-center gap-2 py-[12px] rounded-[10px] bg-red-50 border border-red-100 text-red-500 font-body text-[13px] font-semibold hover:bg-red-500 hover:text-white hover:border-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .hero-header {
        opacity: 0;
        animation: slideUpFade 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    
    .stat-card {
        opacity: 0;
        animation: slideUpFade 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    .table-row-item, .mobile-card-item {
        opacity: 0;
        animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    .flash-message {
        animation: slideDownFade 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    .pulse-dot {
        animation: pulseDot 2s infinite;
    }

    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDownFade {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulseDot {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search filter functionality
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            const term = e.target.value.toLowerCase();
            
            // Filter desktop table
            document.querySelectorAll('.table-row-item').forEach(row => {
                const name = row.querySelector('.car-name-cell').textContent.toLowerCase();
                row.style.display = name.includes(term) ? '' : 'none';
            });
            
            // Filter mobile cards
            document.querySelectorAll('.mobile-card-item').forEach(card => {
                const name = card.querySelector('.car-name-cell').textContent.toLowerCase();
                card.style.display = name.includes(term) ? '' : 'none';
            });
        });
    }

    // Auto-dismiss Flash Messages
    const flashes = document.querySelectorAll('.flash-message');
    flashes.forEach(flash => {
        setTimeout(() => {
            flash.style.transition = 'all 0.4s ease';
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
</script>
@endpush
