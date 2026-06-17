<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 leading-tight tracking-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <!-- Quick Actions -->
            <section>
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <a href="{{ route('rooms.create') }}"
                        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                        <div class="relative z-10 flex flex-col items-center text-center space-y-3">
                            <div class="p-3 bg-white/20 rounded-full backdrop-blur-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg tracking-wide">Add Room</span>
                        </div>
                    </a>

                    <a href="{{ route('packages.create') }}"
                        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                        <div class="relative z-10 flex flex-col items-center text-center space-y-3">
                            <div class="p-3 bg-white/20 rounded-full backdrop-blur-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg tracking-wide">Add Package</span>
                        </div>
                    </a>

                    <a href="{{ route('blogs.create') }}"
                        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-fuchsia-500 to-pink-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                        <div class="relative z-10 flex flex-col items-center text-center space-y-3">
                            <div class="p-3 bg-white/20 rounded-full backdrop-blur-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg tracking-wide">Add Blog</span>
                        </div>
                    </a>

                    <a href="{{ route('experience-activities.create') }}"
                        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                        <div class="relative z-10 flex flex-col items-center text-center space-y-3">
                            <div class="p-3 bg-white/20 rounded-full backdrop-blur-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg tracking-wide">Add Activity</span>
                        </div>
                    </a>
                </div>
            </section>

            <!-- Statistics Grid -->
            <section>
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    System Statistics
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">
                    <!-- Stat Card -->
                    <div
                        class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100 flex flex-col items-center justify-center relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <span
                            class="relative z-10 text-4xl font-black text-gray-900 mb-2">{{ $statistics['total_rooms'] }}</span>
                        <span
                            class="relative z-10 text-xs font-bold text-gray-500 uppercase tracking-widest">Rooms</span>
                    </div>
                    <!-- Stat Card -->
                    <div
                        class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100 flex flex-col items-center justify-center relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -top-4 w-16 h-16 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <span
                            class="relative z-10 text-4xl font-black text-gray-900 mb-2">{{ $statistics['total_packages'] }}</span>
                        <span
                            class="relative z-10 text-xs font-bold text-gray-500 uppercase tracking-widest">Packages</span>
                    </div>
                    <!-- Stat Card -->
                    <div
                        class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100 flex flex-col items-center justify-center relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <span
                            class="relative z-10 text-4xl font-black text-gray-900 mb-2">{{ $statistics['total_activities'] }}</span>
                        <span
                            class="relative z-10 text-xs font-bold text-gray-500 uppercase tracking-widest">Activities</span>
                    </div>
                    <!-- Stat Card -->
                    <div
                        class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100 flex flex-col items-center justify-center relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -top-4 w-16 h-16 bg-fuchsia-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <span
                            class="relative z-10 text-4xl font-black text-gray-900 mb-2">{{ $statistics['total_blogs'] }}</span>
                        <span
                            class="relative z-10 text-xs font-bold text-gray-500 uppercase tracking-widest">Blogs</span>
                    </div>
                    <!-- Stat Card -->
                    <div
                        class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100 flex flex-col items-center justify-center relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -top-4 w-16 h-16 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <span
                            class="relative z-10 text-4xl font-black text-gray-900 mb-2">{{ $statistics['total_testimonials'] }}</span>
                        <span
                            class="relative z-10 text-xs font-bold text-gray-500 uppercase tracking-widest">Reviews</span>
                    </div>
                    <!-- Stat Card -->
                    <div
                        class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100 flex flex-col items-center justify-center relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -top-4 w-16 h-16 bg-rose-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <span
                            class="relative z-10 text-4xl font-black text-gray-900 mb-2">{{ $statistics['total_services'] }}</span>
                        <span
                            class="relative z-10 text-xs font-bold text-gray-500 uppercase tracking-widest">Services</span>
                    </div>
                    <!-- Stat Card -->
                    <div
                        class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100 flex flex-col items-center justify-center relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -top-4 w-16 h-16 bg-cyan-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <span
                            class="relative z-10 text-4xl font-black text-gray-900 mb-2">{{ $statistics['total_messages'] }}</span>
                        <span class="relative z-10 text-xs font-bold text-gray-500 uppercase tracking-widest">Total
                            Msgs</span>
                    </div>
                    <!-- Stat Card -->
                    <div
                        class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100 flex flex-col items-center justify-center relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -top-4 w-16 h-16 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <span
                            class="relative z-10 text-4xl font-black text-red-600 mb-2">{{ $statistics['unread_messages'] }}</span>
                        <span class="relative z-10 text-xs font-bold text-red-500 uppercase tracking-widest">Unread
                            Msgs</span>
                    </div>
                </div>
            </section>

            <!-- Recent Items -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Rooms -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-white flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Recent Rooms
                        </h3>
                        <a href="{{ route('rooms.index') }}"
                            class="text-sm text-indigo-600 hover:text-indigo-800 font-bold transition-colors">View All
                            &rarr;</a>
                    </div>
                    <ul class="divide-y divide-gray-50">
                        @forelse($recentItems['recent_rooms'] as $room)
                            <li class="px-6 py-4 hover:bg-gray-50/80 transition-colors group">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1 min-w-0 flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                                        <p
                                            class="text-sm font-semibold text-gray-700 truncate group-hover:text-blue-600 transition-colors">
                                            {{ $room->title }}</p>
                                    </div>
                                    <div
                                        class="ml-4 flex-shrink-0 text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-md">
                                        {{ $room->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-6 py-12 text-center">
                                <p class="text-gray-400 text-sm font-medium">No rooms found yet.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Recent Blogs -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-white flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Recent Blogs
                        </h3>
                        <a href="{{ route('blogs.index') }}"
                            class="text-sm text-fuchsia-600 hover:text-fuchsia-800 font-bold transition-colors">View All
                            &rarr;</a>
                    </div>
                    <ul class="divide-y divide-gray-50">
                        @forelse($recentItems['recent_blogs'] as $blog)
                            <li class="px-6 py-4 hover:bg-gray-50/80 transition-colors group">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1 min-w-0 flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-fuchsia-400"></div>
                                        <p
                                            class="text-sm font-semibold text-gray-700 truncate group-hover:text-fuchsia-600 transition-colors">
                                            {{ $blog->title }}</p>
                                    </div>
                                    <div
                                        class="ml-4 flex-shrink-0 text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-md">
                                        {{ $blog->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-6 py-12 text-center">
                                <p class="text-gray-400 text-sm font-medium">No blogs found yet.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Recent Messages -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-white flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Recent Messages
                        </h3>
                        <a href="{{ route('messages.index') }}"
                            class="text-sm text-cyan-600 hover:text-cyan-800 font-bold transition-colors">View All
                            &rarr;</a>
                    </div>
                    <ul class="divide-y divide-gray-50">
                        @forelse($recentItems['recent_messages'] as $message)
                            <li class="px-6 py-4 hover:bg-gray-50/80 transition-colors group">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1 min-w-0 flex items-center gap-3">
                                        <div
                                            class="w-2 h-2 rounded-full {{ $message->status === 'new' ? 'bg-red-500' : 'bg-cyan-400' }}">
                                        </div>
                                        <p
                                            class="text-sm font-semibold text-gray-700 truncate group-hover:text-cyan-600 transition-colors">
                                            {{ $message->title }} <span class="font-normal text-gray-500">-
                                                {{ $message->name }}</span>
                                        </p>
                                    </div>
                                    <div
                                        class="ml-4 flex-shrink-0 text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-md">
                                        {{ $message->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-6 py-12 text-center">
                                <p class="text-gray-400 text-sm font-medium">No messages found yet.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>