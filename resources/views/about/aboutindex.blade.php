<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('About Section') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row justify-end items-center gap-4 mb-6">
                        

                        <a href="{{ route('abouts.create') }}"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out whitespace-nowrap">
                            + About Section
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                       <table id="aboutTable" class="w-full">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SN</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">About Image</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created At</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
    </thead>

    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($abouts as $index => $about)
            <tr class="hover:bg-gray-50">

                {{-- SN --}}
                <td class="px-6 py-4 text-sm">
                    {{ $index + 1 }}
                </td>

                {{-- Image --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($about->about_image)
                        <img src="{{ Storage::url($about->about_image) }}"
                            class="h-16 w-24 object-cover rounded-lg"
                            alt="{{ $about->about_title }}">
                    @else
                        <div class="h-16 w-24 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    @endif
                </td>

                {{-- Title --}}
                <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-900 line-clamp-2">
                        {{ $about->about_title }}
                    </p>
                </td>

                {{-- Description --}}
                <td class="px-6 py-4 text-sm text-gray-600">
                    <p class="line-clamp-2">
                        {{ $about->about_description }}
                    </p>
                </td>

                {{-- Created At --}}
                <td class="px-6 py-4 text-sm text-gray-500">
                    {{ $about->created_at->format('M d, Y') }}
                </td>

                {{-- Actions --}}
                <td class="px-6 py-4 text-sm">
                    <div class="flex items-center space-x-3">

                        {{-- View --}}
                        <a href="{{ route('abouts.show', $about->id) }}"
                            class="text-blue-600 hover:text-blue-900" title="View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                        </a>

                        {{-- Edit --}}
                        <a href="{{ route('abouts.edit', $about->id) }}"
                            class="text-indigo-600 hover:text-indigo-900">
                            Edit
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('abouts.destroy', $about->id) }}" method="POST"
                            onsubmit="return confirm('Delete this About content?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:text-red-900">
                                Delete
                            </button>
                        </form>

                    </div>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center px-6 py-6 text-gray-500">
                    No content found.
                </td>
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
        <script type="module">
            $(document).ready(function() {
                let table = $('#blogTable').DataTable({
                    paging: true,
                    searching: true,
                    order: [
                        [5, 'desc']
                    ], // Sort by created_at column (index 5 after removing columns)
                    dom: 't<"bottom"p>',
                    columnDefs: [{
                            targets: [1, 6], // Disable sorting on Feature Photo and Actions columns
                            orderable: false
                        },
                        {
                            targets: "_all",
                            defaultContent: ""
                        }
                    ]
                });

                $('#searchInput').on('keyup', function() {
                    table.search(this.value).draw();
                });
            });
        </script>
    @endpush

</x-app-layout>
