<x-frontend-layout>
    <!-- Hero Slider -->
    @if($hero && $hero->sliderImages->count() > 0)
        <section x-data="{ 
            activeSlide: 0, 
            totalSlides: {{ $hero->sliderImages->count() }},
            next() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides },
            prev() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides }
        }" 
        x-init="setInterval(() => next(), 6000)"
        class="relative h-screen min-h-[600px] w-full overflow-hidden bg-black">
            
            @foreach($hero->sliderImages as $index => $image)
                <div x-show="activeSlide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-1000"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 w-full h-full">
                    
                    <!-- Background Image -->
                    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
                         style="background-image: url('{{ asset('storage/' . $image->image_path) }}')">
                    </div>
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
                    
                    <!-- Content -->
                    <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                        <div class="max-w-2xl text-white">
                            @if($image->sub_heading)
                                <span class="inline-block px-4 py-1.5 bg-indigo-600 text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] mb-6 rounded-full">
                                    {{ $image->sub_heading }}
                                </span>
                            @endif
                            <h2 class="text-4xl sm:text-6xl md:text-7xl font-black mb-8 leading-tight font-serif">
                                {{ $image->heading }}
                            </h2>
                            <div class="flex flex-wrap gap-4 mt-8">
                                <a href="#content" class="px-8 py-4 bg-white text-indigo-600 font-bold rounded-full hover:bg-indigo-50 transition-all shadow-lg hover:shadow-indigo-500/20 active:scale-95">
                                    Discover More
                                </a>
                                <a href="#" class="px-8 py-4 border-2 border-white/30 text-white font-bold rounded-full hover:bg-white/10 backdrop-blur-sm transition-all active:scale-95">
                                    Our Community
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Slider Controls -->
            <div class="absolute bottom-10 left-0 right-0 z-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <!-- Dots -->
                    <div class="flex space-x-3">
                        @foreach($hero->sliderImages as $index => $image)
                            <button @click="activeSlide = {{ $index }}" 
                                    class="h-1 rounded-full transition-all duration-500"
                                    :class="activeSlide === {{ $index }} ? 'w-12 bg-white' : 'w-4 bg-white/30 hover:bg-white/60'"></button>
                        @endforeach
                    </div>
                    
                    <!-- Arrows -->
                    <div class="flex space-x-4">
                        <button @click="prev()" class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <button @click="next()" class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    @else
        <!-- Fallback Header for pages without Hero -->
        <section class="relative pt-32 pb-20 bg-indigo-900 overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="grid grid-cols-8 gap-4 transform -rotate-12 scale-150">
                    @for($i=0; $i<32; $i++) <div class="h-24 bg-white rounded-lg"></div> @endfor
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
                <h1 class="text-4xl sm:text-6xl font-black text-white mb-4 tracking-tighter">{{ $page->name }}</h1>
                <div class="flex items-center justify-center text-indigo-300 text-sm font-medium uppercase tracking-widest space-x-2">
                    <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-white">{{ $page->name }}</span>
                </div>
            </div>
        </section>
    @endif

    <!-- Dynamic Sections -->
    <div id="content" class="py-12 md:py-24 space-y-24 md:space-y-32 overflow-hidden">
        @forelse($sections as $index => $section)
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal border-b border-gray-100 last:border-0 pb-16 last:pb-0">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center {{ $index % 2 != 0 ? 'lg:flex-row-reverse' : '' }}">
                    
                    <!-- Content Area -->
                    <div class="{{ $index % 2 != 0 ? 'lg:order-2' : 'lg:order-1' }}">
                        @if($section->section_title)
                            <span class="inline-block text-indigo-600 font-bold uppercase tracking-[0.2em] text-xs mb-4 border-l-4 border-indigo-600 pl-4">
                                {{ $section->section_title }}
                            </span>
                        @endif
                        
                        <h2 class="text-3xl md:text-5xl font-black mb-8 leading-tight text-gray-900 tracking-tight font-serif">
                            {{ $section->heading }}
                        </h2>
                        
                        @if($section->sub_heading)
                            <p class="text-lg font-medium text-gray-500 mb-8 italic">
                                {{ $section->sub_heading }}
                            </p>
                        @endif

                        <div class="prose prose-lg text-gray-600 max-w-none leading-relaxed mb-10">
                            {!! nl2br(e($section->content)) !!}
                        </div>

                        @if($section->cta_text)
                            <a href="{{ $section->cta_link ?? '#' }}" class="inline-flex items-center group font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                <span class="border-b-2 border-indigo-600 group-hover:border-indigo-800 pb-1">{{ $section->cta_text }}</span>
                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        @endif
                    </div>

                    <!-- Image Area -->
                    <div class="{{ $index % 2 != 0 ? 'lg:order-1' : 'lg:order-2' }} relative">
                        @if($section->image)
                            <div class="relative rounded-2xl overflow-hidden shadow-2xl group">
                                <img src="{{ asset('storage/' . $section->image) }}" 
                                     alt="{{ $section->heading }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition-all duration-700">
                                <div class="absolute inset-0 ring-1 ring-inset ring-black/10 rounded-2xl"></div>
                            </div>
                            
                            <!-- Decorative elements -->
                            <div class="absolute -z-10 -bottom-6 -right-6 w-32 h-32 bg-indigo-50 rounded-full"></div>
                            <div class="absolute -z-10 -top-6 -left-6 w-24 h-24 bg-pink-50 rounded-full"></div>
                        @else
                           <!-- Placeholder if no image -->
                           <div class="aspect-video bg-gray-50 rounded-2xl flex items-center justify-center border-2 border-dashed border-gray-200">
                               <span class="text-gray-300 font-bold uppercase tracking-widest">{{ $section->section_title ?? 'NEWA DABOO' }}</span>
                           </div>
                        @endif
                    </div>
                </div>
            </section>
        @empty
            <section class="py-20 text-center">
                <div class="max-w-md mx-auto px-4">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Expanding our content</h3>
                    <p class="text-gray-500">We are currently updating this page with more information about our initiatives. Check back soon!</p>
                </div>
            </section>
        @endforelse
    </div>
</x-frontend-layout>
