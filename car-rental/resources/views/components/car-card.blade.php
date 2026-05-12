@props(['name', 'color', 'price', 'image'])

<div class="car-card group bg-white rounded-[40px] shadow-sm hover:-translate-y-2 hover:shadow-xl transition-all duration-300 flex flex-col">
    <!-- Image Container -->
    <div class="relative w-full aspect-[4/4.5] rounded-t-[40px]">
        <!-- Image with rounded top corners -->
        <div class="w-full h-full rounded-t-[40px] overflow-hidden">
            <img src="{{ isset($image) && !str_starts_with($image, 'http') ? asset('storage/' . $image) : $image }}" 
                 alt="{{ $name }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
        </div>
        
        <!-- Book Button Overlap (Bottom Right) -->
        @if(Route::has('booking.step1'))
            <a href="{{ route('booking.step1') }}" class="absolute -bottom-8 right-6 w-16 h-16 bg-white border-[3px] border-brand-black rounded-full flex items-center justify-center hover:bg-brand-black hover:text-white transition-colors z-20 group/btn">
                <svg class="w-6 h-6 text-brand-black group-hover/btn:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </a>
        @else
            <a href="#" class="absolute -bottom-8 right-6 w-16 h-16 bg-white border-[3px] border-brand-black rounded-full flex items-center justify-center hover:bg-brand-black hover:text-white transition-colors z-20 group/btn">
                <svg class="w-6 h-6 text-brand-black group-hover/btn:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </a>
        @endif
    </div>

    <!-- Text Content -->
    <div class="text-center px-6 pt-12 pb-10 rounded-b-[40px] bg-white relative z-10 flex-grow flex flex-col justify-end">
        <h3 class="font-display text-[28px] text-brand-black tracking-wider uppercase leading-none">{{ $name }}</h3>
        <p class="text-brand-gray-mid text-sm mt-2">{{ $color ?? 'Black' }}</p>
        <p class="text-brand-black font-bold text-2xl mt-4">${{ number_format($price) }}</p>
    </div>
</div>
