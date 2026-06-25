<nav x-data="{ open: false }" class="bg-white shadow-sm border-b p-3">
    <!-- Main Wrapper -->
    <div class=" mx-72 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">

            <!-- Header / Page Title -->
            <div class="hidden sm:flex items-center">
                @isset($header)
                    <div class="text-lg font-semibold text-gray-700">
                        {{ $header }}
                    </div>
                @endisset
            </div>

            <!-- User Dropdown - Desktop -->
            <div class="hidden sm:flex items-center space-x-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                            <span>{{ Auth::user()->name }}</span>

                            <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06 0L10 10.91l3.71-3.7a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 010-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if (auth()->user()->role == 'superadmin')
                            <x-responsive-nav-link :href="route('profile.edit')">
                                Profile
                            </x-responsive-nav-link>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Tablet / Small Desktop) -->
            <div class="sm:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <!-- Menu Icon -->
                        <path :class="{ 'hidden': open, 'block': !open }" class="block" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />

                        <!-- Close Icon -->
                        <path :class="{ 'hidden': !open, 'block': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t px-4 pb-4">
        <div class="pt-4 space-y-2">

            <div class="text-base font-medium text-gray-800">
                {{ Auth::user()->name }}
            </div>
            <div class="text-sm text-gray-500">
                {{ Auth::user()->email }}
            </div>

            <div class="mt-3 space-y-1">
                @if (auth()->user()->role == 'superadmin')
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profile
                    </x-responsive-nav-link>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>

        </div>
    </div>
</nav>