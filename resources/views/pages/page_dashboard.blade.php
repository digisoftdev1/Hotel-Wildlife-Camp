<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $page->name }} Management
        </h2>
        <p class="mt-1 text-sm text-gray-500">Manage sections for {{ $page->name }}</p>
    </x-slot>

    <div class="py-12">
        <div class="mx-72 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                            class="absolute top-24 end-5 transition-all duration-500">
                            <div class="max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg" role="alert"
                                tabindex="-1">
                                <div class="flex p-4">
                                    <div class="shrink-0">
                                        <svg class="shrink-0 size-4 text-teal-500 mt-0.5"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ms-3">
                                        <p class="text-sm text-black">
                                            {{ session('success') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                        <div class="flex items-center gap-2">
                            <a href="{{ url('pages/' . $page->slug . '/section/new') }}" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition-all">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Add New Section</span>
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th width="40" class="px-6 py-3"></th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sections</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-sections" class="bg-white divide-y divide-gray-200">
                                @forelse ($availableSections as $section)
                                    <tr class="hover:bg-gray-50 transition-colors {{ $section['type'] === 'hero' ? 'static-item bg-gray-50/50' : 'draggable-item' }}" 
                                        data-id="{{ $section['id'] }}" 
                                        data-type="{{ $section['type'] }}">
                                        <td class="px-6 py-4 whitespace-nowrap {{ $section['type'] === 'hero' ? 'text-gray-300' : 'cursor-move drag-handle text-gray-400 hover:text-indigo-600 transition-colors' }}">
                                            @if ($section['type'] === 'hero')
                                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                            @else
                                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                                </svg>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-medium {{ $section['type'] === 'hero' ? 'text-indigo-600 uppercase text-xs tracking-wider' : 'text-gray-700' }}">
                                                {{ $section['label'] }}
                                            </span>
                                            @if ($section['status'] === 'published')
                                                <span
                                                    class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs 
                                                font-medium text-green-700 ring-1 ring-inset ring-green-600/20 ml-2">
                                                    Published
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs 
                                                font-medium text-gray-700 ring-1 ring-inset ring-gray-500/20 ml-2">
                                                    Draft
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ $section['edit_url'] }}"
                                                class="text-indigo-600 underline hover:text-indigo-800 font-bold text-sm">
                                                {{ $section['type'] === 'hero' ? 'Manage Hero' : 'Edit Section' }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 italic">No sections
                                            available for this page.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const el = document.getElementById('sortable-sections');
            if (el) {
                Sortable.create(el, {
                    animation: 150,
                    handle: '.drag-handle',
                    draggable: '.draggable-item', // Only allow common sections to be dragged
                    ghostClass: 'bg-indigo-50',
                    onEnd: function() {
                        const orders = [];
                        const rows = el.querySelectorAll('tr[data-id]');
                        rows.forEach((row, index) => {
                            // Only send updates for common sections (Hero is always 0)
                            if (row.getAttribute('data-type') !== 'hero') {
                                orders.push({
                                    id: row.getAttribute('data-id'),
                                    type: row.getAttribute('data-type'),
                                    order: index // Hero is at index 0, so others start from 1+
                                });
                            }
                        });

                        // Send to server
                        fetch("{{ route('pages.updateOrder', $page->slug) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ orders: orders })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Order saved successfully
                            }
                        })
                        .catch(error => {
                            console.error('Error updating order:', error);
                        });
                    }
                });
            }
        });
    </script>
    @endpush
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
