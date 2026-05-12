@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <header class="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#f3f3f3] pt-28 pb-20">
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 flex flex-col items-center mt-12 md:mt-0">
            <!-- Hero Text -->
            <h1 class="hero-title font-display text-brand-black leading-none tracking-tight text-center mb-0 select-none w-full" style="font-size: clamp(3rem, 13vw, 11rem); transform: translate(-2%, 0px);">
                YOUR NEXT CAR
            </h1>
            
            <!-- Hero Slideshow -->
            <div class="hero-image-container relative w-full max-w-[1100px] -mt-4 md:-mt-12 z-20 px-4 flex flex-col items-center">
                <!-- Slide Wrapper -->
                <div id="hero-slider" class="overflow-hidden w-full bg-brand-black relative" style="aspect-ratio: 16/7; border-radius: 9999px; box-shadow: 0 40px 80px rgba(0,0,0,0.22), 0 8px 24px rgba(0,0,0,0.12);">

                    <!-- Slide 1: Mercedes AMG GT -->
                    <div class="hero-slide absolute inset-0 opacity-100" data-index="0">
                        <img src="{{ asset('storage/cars/kevin-bhagat-10tOJa4APL8-unsplash.jpg') }}"
                             alt="Mercedes AMG GT"
                             class="w-full h-full object-cover slide-img"
                             style="object-position: 48% 60%; transform-origin: center center;"
                             loading="eager">
                    </div>

                    <!-- Slide 2: Ford Mustang -->
                    <div class="hero-slide absolute inset-0 opacity-0" data-index="1">
                        <img src="{{ asset('storage/cars/kartik-snekar-cqocvUWSc1A-unsplash.jpg') }}"
                             alt="Ford Mustang"
                             class="w-full h-full object-cover slide-img"
                             style="object-position: center 28%; transform-origin: center center;"
                             loading="lazy">
                    </div>

                    <!-- Slide 3: Volvo Grille -->
                    <div class="hero-slide absolute inset-0 opacity-0" data-index="2">
                        <img src="{{ asset('storage/cars/louis-tricot-0BM8hXdsn_k-unsplash.jpg') }}"
                             alt="Volvo Grille"
                             class="w-full h-full object-cover slide-img"
                             style="object-position: center center; transform-origin: center center;"
                             loading="lazy">
                    </div>

                    <!-- Cinematic 4-sided vignette overlay -->
                    <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(ellipse at center, transparent 50%, rgba(0,0,0,0.55) 100%);"></div>

                    <!-- Bottom gradient for label -->
                    <div class="absolute inset-x-0 bottom-0 h-28 pointer-events-none" style="background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, transparent 100%);"></div>

                    <!-- Slide Label + Counter -->
                    <div class="absolute bottom-5 inset-x-0 flex items-end justify-between px-8 select-none pointer-events-none">
                        <div id="slide-label" class="text-white/90 text-[11px] font-semibold tracking-[0.25em] uppercase" style="text-shadow:0 2px 12px rgba(0,0,0,1)">
                            Mercedes AMG GT &bull; Matte Edition
                        </div>
                        <div id="slide-counter" class="text-white/50 text-[11px] font-medium tracking-widest tabular-nums" style="text-shadow:0 1px 6px rgba(0,0,0,0.8)">
                            01 / 03
                        </div>
                    </div>
                </div>

                <!-- Indicator Lines -->
                <div class="flex justify-center gap-3 mt-8 md:mt-12">
                    <button id="dot-0" onclick="goToSlide(0)" class="hero-dot h-[3px] rounded-full bg-brand-black transition-all duration-500" style="width:48px" aria-label="Slide 1"></button>
                    <button id="dot-1" onclick="goToSlide(1)" class="hero-dot h-[3px] rounded-full transition-all duration-500" style="width:48px; background:rgba(0,0,0,0.2)" aria-label="Slide 2"></button>
                    <button id="dot-2" onclick="goToSlide(2)" class="hero-dot h-[3px] rounded-full transition-all duration-500" style="width:48px; background:rgba(0,0,0,0.2)" aria-label="Slide 3"></button>
                </div>
            </div>

        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="py-24 bg-brand-gray-light overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left Content -->
                <div class="section-header space-y-8">
                    <div>
                        <span class="text-brand-gray-mid font-semibold tracking-wider text-sm uppercase">Unique Car</span>
                        <h2 class="font-display text-5xl md:text-[56px] text-brand-black leading-[1.1] mt-2">FIND YOUR DREAM CAR TODAY</h2>
                    </div>
                    <p class="text-brand-gray-mid text-lg max-w-md leading-relaxed">
                        At DriveX, we offer a wide selection of high-quality vehicles to suit every style and budget. Browse our inventory today.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-8 pt-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-brand-black flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-brand-black text-lg">Quality Cars</h4>
                                <p class="text-sm text-brand-gray-mid mt-1">Explore our collection of top-notch vehicles.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-brand-black flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-brand-black text-lg">Exceptional Service</h4>
                                <p class="text-sm text-brand-gray-mid mt-1">Experience our personalized approach.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 pt-6">
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary px-8 py-3.5 font-semibold text-lg">Sign Up</a>
                        @else
                            <a href="#" class="btn-primary px-8 py-3.5 font-semibold text-lg">Sign Up</a>
                        @endif
                        <a href="#services" class="font-semibold text-brand-black hover:text-brand-accent transition-colors flex items-center gap-2">
                            Learn More
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Right Images -->
                <div class="relative mt-12 lg:mt-0 lg:ml-8">
                    <div class="rounded-[1.5rem] overflow-hidden shadow-2xl aspect-[4/5] max-h-[600px] w-full">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80" alt="Showroom" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    <!-- Overlapping Circles -->
                    <div class="absolute -bottom-6 -right-6 sm:-right-8 w-32 h-32 sm:w-40 sm:h-40 rounded-full border-[8px] border-brand-gray-light overflow-hidden shadow-xl z-20">
                        <img src="https://images.unsplash.com/photo-1600705722908-bab1e61c0b4d?w=400&q=80" alt="Steering Wheel" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    <div class="absolute -bottom-12 right-16 sm:right-24 w-28 h-28 sm:w-36 sm:h-36 rounded-full border-[8px] border-brand-gray-light overflow-hidden shadow-xl z-10">
                        <img src="https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=400&q=80" alt="Tire" class="w-full h-full object-cover" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Cars Section -->
    @php
        $demoCars = [
            (object)['name' => 'Luxury Sedan', 'color' => 'Black', 'price_per_day' => 55000, 'image' => asset('storage/cars/benjamin-child-7Cdw956mZ4w-unsplash.jpg')],
            (object)['name' => 'Sports Coupe', 'color' => 'Black', 'price_per_day' => 65000, 'image' => asset('storage/cars/hyundai-motor-group-mpWLjX-WaNo-unsplash.jpg')],
            (object)['name' => 'Luxury SUV',   'color' => 'Black', 'price_per_day' => 70000, 'image' => asset('storage/cars/kartik-snekar-cqocvUWSc1A-unsplash.jpg')],
            (object)['name' => 'Convertible',  'color' => 'Black', 'price_per_day' => 60000, 'image' => asset('storage/cars/kevin-bhagat-10tOJa4APL8-unsplash.jpg')],
            (object)['name' => 'Hatchback',    'color' => 'Black', 'price_per_day' => 45000, 'image' => asset('storage/cars/louis-tricot-0BM8hXdsn_k-unsplash.jpg')],
            (object)['name' => 'Electric SUV', 'color' => 'Black', 'price_per_day' => 75000, 'image' => asset('storage/cars/benjamin-child-7Cdw956mZ4w-unsplash.jpg')],
        ];
        $displayCars = isset($cars) && count($cars) > 0 ? $cars : $demoCars;
    @endphp

    <section id="featured" class="py-24 bg-brand-gray-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 section-header">
                <span class="text-brand-gray-mid font-semibold tracking-wider text-sm uppercase">Discover</span>
                <h2 class="font-display text-5xl md:text-7xl text-brand-black mt-2">FEATURED PROMINENTLY</h2>
                <p class="text-brand-gray-mid mt-4 max-w-2xl mx-auto text-lg">Explore our wide selection of high-quality cars.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($displayCars as $car)
                    <x-car-card 
                        :name="$car->name" 
                        :color="$car->color ?? 'Black'" 
                        :price="$car->price_per_day" 
                        :image="$car->image" 
                    />
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section id="why-choose-us" class="py-24 bg-brand-gray-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 section-header">
                <span class="text-brand-gray-mid font-semibold tracking-wider text-sm uppercase">Find Your Dream Car</span>
                <h2 class="font-display text-5xl md:text-7xl text-brand-black mt-2">PICK YOUR CAR</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                <!-- Col 1 -->
                <div class="feature-col text-center p-8">
                    <div class="w-20 h-20 rounded-full bg-brand-black text-white flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-2xl text-brand-black mb-4">Easy Process</h3>
                    <p class="text-brand-gray-mid text-sm leading-relaxed mb-6">At DriveX, we offer a range of benefits when you purchase a car from us. Our cars come with a warranty to give you peace of mind.</p>
                    <a href="#" class="btn-primary px-6 py-2.5 inline-block font-semibold">Learn More</a>
                </div>

                <!-- Col 2 (Elevated) -->
                <div class="feature-col text-center bg-white rounded-[2rem] p-10 shadow-2xl relative z-10 transform md:scale-105">
                    <div class="w-20 h-20 rounded-full bg-brand-black text-white flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <h3 class="font-bold text-2xl text-brand-black mb-4">Fast Approval</h3>
                    <p class="text-brand-gray-mid text-sm leading-relaxed mb-6">At DriveX, we offer a range of benefits when you purchase a car from us. Our cars come with a warranty to give you peace of mind.</p>
                    <a href="#" class="btn-primary px-6 py-2.5 inline-block font-semibold">Learn More</a>
                </div>

                <!-- Col 3 -->
                <div class="feature-col text-center p-8">
                    <div class="w-20 h-20 rounded-full bg-brand-black text-white flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="font-bold text-2xl text-brand-black mb-4">Secure Delivery</h3>
                    <p class="text-brand-gray-mid text-sm leading-relaxed mb-6">At DriveX, we offer a range of benefits when you purchase a car from us. Our cars come with a warranty to give you peace of mind.</p>
                    <a href="#" class="btn-primary px-6 py-2.5 inline-block font-semibold">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Test Drive CTA Banner -->
    <section id="cta" class="py-12 bg-brand-gray-light px-4 sm:px-6 lg:px-8">
        <div class="cta-banner max-w-6xl mx-auto bg-brand-black rounded-[2rem] overflow-hidden relative flex flex-col md:flex-row items-center p-10 md:p-16 shadow-2xl">
            <!-- Left Bolt Icon -->
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-brand-accent flex items-center justify-center shrink-0 mb-8 md:mb-0 md:mr-12 shadow-[0_0_30px_rgba(59,111,255,0.4)] z-10">
                <svg class="w-12 h-12 md:w-16 md:h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd" />
                </svg>
            </div>
            
            <!-- Center Content -->
            <div class="text-center md:text-left z-10 max-w-xl">
                <h2 class="font-display text-5xl md:text-6xl text-white mb-4">FREE TEST DRIVE</h2>
                <p class="text-brand-gray-mid text-lg mb-8">Sign up for early access and be the first to experience our upcoming car models.</p>
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary px-8 py-4 font-semibold text-lg inline-block">Get Early Access</a>
                @else
                    <a href="#" class="btn-primary px-8 py-4 font-semibold text-lg inline-block">Get Early Access</a>
                @endif
            </div>

            <!-- Right Image overlay -->
            <div class="absolute right-0 top-0 bottom-0 w-full md:w-1/2 opacity-30 md:opacity-60 pointer-events-none">
                <img src="https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800&q=80" alt="Car Headlight" class="w-full h-full object-cover object-right" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-r from-brand-black via-brand-black/50 to-transparent md:bg-gradient-to-r md:from-brand-black md:to-transparent"></div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            gsap.registerPlugin(ScrollTrigger);

            // Hero Animation
            const tl = gsap.timeline();
            tl.from(".hero-title", {
                y: 80,
                opacity: 0,
                duration: 1.2,
                ease: "power3.out",
                delay: 0.2
            })
            .from(".hero-image-container", {
                scale: 0.9,
                y: 40,
                opacity: 0,
                duration: 1.2,
                ease: "power3.out"
            }, "-=0.8");

            // Section Headers
            gsap.utils.toArray('.section-header').forEach(header => {
                gsap.from(header, {
                    scrollTrigger: {
                        trigger: header,
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    y: 40,
                    opacity: 0,
                    duration: 0.8,
                    ease: "power2.out"
                });
            });

            // Featured Car Cards Animation
            gsap.fromTo(".car-card", 
                { y: 60, opacity: 0 },
                {
                    scrollTrigger: {
                        trigger: "#featured",
                        start: "top 80%",
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.8,
                    ease: "power2.out"
                }
            );

            // Why Choose Us Feature Columns Stagger
            gsap.fromTo(".feature-col", 
                { y: 60, opacity: 0 },
                {
                    scrollTrigger: {
                        trigger: "#why-choose-us",
                        start: "top 80%",
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.8,
                    stagger: 0.2,
                    ease: "power2.out"
                }
            );

            // CTA Banner
            gsap.fromTo(".cta-banner", 
                { scale: 0.95, y: 40, opacity: 0 },
                {
                    scrollTrigger: {
                        trigger: "#cta",
                        start: "top 85%",
                    },
                    scale: 1,
                    y: 0,
                    opacity: 1,
                    duration: 1,
                    ease: "power3.out"
                }
            );
        });

        // ─── Premium Hero Slideshow ────────────────────────────────────────────────
        const SLIDES = [
            { label: "Mercedes AMG GT &bull; Matte Edition" },
            { label: "Ford Mustang &bull; Race Stripes" },
            { label: "Volvo &bull; Scandinavian Prestige" },
        ];

        let currentSlide  = 0;
        let sliderTimer   = null;
        let isAnimating   = false;

        function goToSlide(next) {
            if (isAnimating || next === currentSlide) return;
            isAnimating = true;

            const slides = document.querySelectorAll(".hero-slide");
            const dots   = document.querySelectorAll(".hero-dot");
            const label  = document.getElementById("slide-label");
            const prev   = currentSlide;

            // Fade out the outgoing slide image (Ken Burns reset)
            gsap.to(slides[prev], {
                opacity: 0,
                duration: 0.9,
                ease: "power2.inOut"
            });
            gsap.to(slides[prev].querySelector(".slide-img"), {
                scale: 1,
                duration: 0.9,
                ease: "power2.inOut"
            });

            // Fade in the incoming slide with Ken Burns zoom
            gsap.fromTo(slides[next],
                { opacity: 0 },
                {
                    opacity: 1,
                    duration: 0.9,
                    ease: "power2.inOut",
                    onComplete: () => { isAnimating = false; }
                }
            );
            gsap.fromTo(slides[next].querySelector(".slide-img"),
                { scale: 1.06 },
                { scale: 1, duration: 5, ease: "power1.out" }
            );

            // Swap label text with a quick fade
            const counter = document.getElementById("slide-counter");
            gsap.to(label, {
                opacity: 0,
                y: -8,
                duration: 0.3,
                onComplete: () => {
                    label.innerHTML = SLIDES[next].label;
                    if (counter) counter.textContent = String(next + 1).padStart(2, '0') + ' / 0' + SLIDES.length;
                    gsap.to(label, { opacity: 1, y: 0, duration: 0.4 });
                }
            });

            // Update dots with inline styles (reliable across Tailwind JIT)
            dots.forEach((d, i) => {
                if (i === next) {
                    d.style.background = '#0a0a0a';
                    d.style.width = '64px';
                } else {
                    d.style.background = 'rgba(0,0,0,0.2)';
                    d.style.width = '48px';
                }
            });

            currentSlide = next;
            resetTimer();
        }

        function nextSlide() {
            goToSlide((currentSlide + 1) % SLIDES.length);
        }

        function resetTimer() {
            clearInterval(sliderTimer);
            sliderTimer = setInterval(nextSlide, 4500);
        }

        // Kick off the Ken Burns effect on slide 1 immediately
        document.addEventListener("DOMContentLoaded", () => {
            const firstImg = document.querySelector(".hero-slide[data-index='0'] .slide-img");
            if (firstImg) {
                gsap.fromTo(firstImg, { scale: 1.06 }, { scale: 1, duration: 5, ease: "power1.out" });
            }
            resetTimer();
        });
        // ──────────────────────────────────────────────────────────────────────────</script>
@endpush
