<div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-600 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

    <!-- Logo -->
    <div class="h-20 border-b border-gray-100 flex-shrink-0 bg-white">
        <a href="{{ route('dashboard') }}" class="block w-full h-full transition-opacity hover:opacity-80">
            <img src="{{ asset('assets/logo/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
        </a>
    </div>
    <nav
        class="mt-8 flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-500 scrollbar-track-gray-700 hover:scrollbar-thumb-gray-400">
        <div class="px-4 space-y-2 pb-4">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors group">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v10H8V5z"></path>
                </svg>
                Dashboard
            </a>

            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-box-open"></i>
                        Packages
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-8 mt-2 space-y-1">
                    <a href="{{ route('packages.create') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500">
                        Add New Package
                    </a>
                    <a href="{{ route('packages.index') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500">
                        List All Packages
                    </a>
                    <a href="{{ route('currencies.index') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500">
                        Currencies
                    </a>
                    <a href="{{ route('package-galleries.index') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500">
                        Gallery
                    </a>

                    <a href="{{ route('package-faqs.index') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500">
                        FAQ's
                    </a>
                </div>
            </div>

            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-person-hiking"></i>
                        Activities
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-8 mt-2 space-y-1">
                    <a href="{{ route('experience-activities.create') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500">
                        Add New
                    </a>
                    <a href="{{ route('experience-activities.index') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500">
                        List All
                    </a>
                </div>
            </div>

            <!-- Pages -->
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Pages
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-8 mt-2 space-y-1">
                    @foreach ($menuPages as $parent)
                        @if ($parent->children->count() > 0)
                            {{-- Dropdown for pages with children --}}
                            <div x-data="{ subOpen: {{ request()->is('pages/' . $parent->slug . '*') ? 'true' : 'false' }} }">
                                <button @click="subOpen = !subOpen"
                                    class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500 transition-colors">
                                    <div class="flex items-center">
                                        {{ $parent->name }}
                                    </div>
                                    <svg :class="subOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </button>
                                <div x-show="subOpen" x-collapse class="ml-4 mt-2 space-y-1">
                                    @foreach ($parent->children as $child)
                                        <a href="{{ route('pages.dashboard', $child->slug) }}"
                                            class="block px-4 py-2 text-xs text-gray-300 rounded hover:bg-gray-500 @if (request()->is('pages/' . $child->slug . '*')) bg-gray-500 text-white @endif">
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            {{-- Direct link for pages without children --}}
                            <a href="{{ route('pages.dashboard', $parent->slug) }}"
                                class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500 @if (request()->is('pages/' . $parent->slug . '*')) bg-gray-500 text-white @endif">
                                {{ $parent->name }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-bed"></i>
                        Rooms
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-8 mt-2 space-y-1">
                    <a href="{{ route('rooms.index') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:hover:bg-gray-500">All Rooms</a>
                    <a href="{{ route('rooms.create') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:hover:bg-gray-500">Add Room</a>
                    <a href="{{ route('roomgallery.index') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:hover:bg-gray-500">Room Gallery</a>
                </div>
            </div>

            <a href="{{ route('testimonials.index') }}"
                class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors group">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                    </path>
                </svg>
                Testimonials
            </a>

            <a href="{{ route('services.index') }}"
                class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors group">
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-bell-concierge"></i>
                    Services
                </div>
            </a>

            <a href="{{ route('contactpage.index') }}"
                class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors group">
                <div class="flex items-center gap-4">
                    <i class="fa-regular fa-address-card"></i>
                    Contacts
                </div>
            </a>

            <a href="{{ route('gallery-categories.index') }}"
                class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors group">
                <svg class="w-5 h-5 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m3 16 5-7 6 6.5m6.5 2.5L16 13l-4.286 6M14 10h.01M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z" />
                </svg>


                Gallery
            </a>

            @if (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                        <div class="flex items-center gap-4">
                            <i class="fa-solid fa-users"></i>
                            User management
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-1">
                        <a href="{{ route('users.list') }}"
                            class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500">List users</a>

                        @if (auth()->user()->role === 'superadmin')
                            <a href="{{ route('register') }}"
                                class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500">Add users</a>
                        @endif
                    </div>
                </div>
            @endif

            <a href="{{ route('messages.index') }}"
                class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors group">
                <div class="flex items-center gap-4">
                    <i class="fa-regular fa-message"></i>
                    Customer Message
                </div>
            </a>

            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                    Blog
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-8 mt-2 space-y-1">
                    <a href="{{ route('blogs.index') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500">List of
                        blog post</a>
                    <a href="{{ route('blogs.create') }}"
                        class="block px-4 py-2 text-sm text-gray-200 rounded hover:bg-gray-500">Create
                        Blog</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- User Role Info (Fixed at bottom) -->
    <div class="p-4 border-t border-gray-200 flex-shrink-0">
        <div class="bg-indigo-50 rounded-lg p-3">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700">{{ ucfirst(auth()->user()->name) }}</p>
                    <p class="text-xs text-indigo-600">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
