<nav x-data="{ 
    mobileMenuOpen: false, 
    isScrolled: false,
    activeDropdown: null
}" 
@scroll.window="isScrolled = window.pageYOffset > 50"
class="fixed w-full z-50 transition-all duration-300"
:class="isScrolled ? 'bg-white shadow-md py-2' : 'bg-transparent py-4'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}" class="flex flex-col">
                    <span class="text-2xl font-black tracking-tighter transition-colors"
                          :class="isScrolled ? 'text-indigo-600' : 'text-white'">
                        NEWA DABOO
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest leading-none"
                          :class="isScrolled ? 'text-gray-500' : 'text-gray-300'">
                        Cultural Foundation
                    </span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex lg:items-center lg:space-x-8">
                @foreach ($menuPages as $parent)
                    @if ($parent->children->count() > 0)
                        <div class="relative" @mouseenter="activeDropdown = '{{ $parent->id }}'" @mouseleave="activeDropdown = null">
                            <button class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-colors"
                                :class="(isScrolled || ! @json(request()->is('/'))) ? 'text-gray-700 hover:text-indigo-600' : 'text-white hover:text-gray-200'">
                                {{ $parent->name }}
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <div x-show="activeDropdown === '{{ $parent->id }}'"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute left-0 mt-2 w-48 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
                                <div class="py-2">
                                    @foreach ($parent->children as $child)
                                        <a href="{{ route('page.show', $child->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('page.show', $parent->slug) }}" 
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-colors"
                           :class="(isScrolled || ! @json(request()->is('/'))) ? 'text-gray-700 hover:text-indigo-600' : 'text-white hover:text-gray-200'">
                            {{ $parent->name }}
                        </a>
                    @endif
                @endforeach
                
                <a href="{{ route('login') }}" 
                   class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-all">
                    Member Portal
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="lg:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="inline-flex items-center justify-center p-2 rounded-md transition-colors"
                        :class="isScrolled ? 'text-gray-700' : 'text-white'">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="mobileMenuOpen ? 'hidden' : 'inline-flex'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="mobileMenuOpen ? 'inline-flex' : 'hidden'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" class="lg:hidden bg-white border-t border-gray-100 shadow-2xl">
        <div class="pt-2 pb-3 space-y-1">
            @foreach ($menuPages as $parent)
                @if ($parent->children->count() > 0)
                    <div x-data="{ open: false }" class="space-y-1">
                        <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                            {{ $parent->name }}
                            <svg class="h-4 w-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="pl-6 space-y-1">
                            @foreach ($parent->children as $child)
                                <a href="{{ route('page.show', $child->slug) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600">
                                    {{ $child->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route('page.show', $parent->slug) }}" class="block px-4 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        {{ $parent->name }}
                    </a>
                @endif
            @endforeach
        </div>
        <div class="pt-4 pb-3 border-t border-gray-100 px-4">
             <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 shadow-sm">
                Login
            </a>
        </div>
    </div>
</nav>