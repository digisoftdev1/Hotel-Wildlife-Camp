<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Experiences & Activities') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500"> Manage Experiences & Activities </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Manage Items</h2>
                        <a href="{{ route('experience-activities.create') }}"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                            Add New
                        </a>
                    </div>

                    @if (session('success'))
                        <div
                            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <div class="relative">
                            <input id="searchInput" type="text" placeholder="Search items..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-64 focus:ring-indigo-500 focus:border-indigo-500" />
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Removed Type Filter -->

                        <div class="flex items-center gap-2">
                            <label for="featuredFilter" class="text-sm font-medium text-gray-700">Featured:</label>
                            <select id="featuredFilter"
                                class="text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Items</option>
                                <option value="featured">Featured Only</option>
                                <option value="regular">Regular Only</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <label for="statusFilter" class="text-sm font-medium text-gray-700">Status:</label>
                            <select id="statusFilter"
                                class="text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Status</option>
                                <option value="Published">Published</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="activitiesTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Image</th>
                                    <!-- Removed Type Column -->
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <!-- Removed Price Column -->
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                    <th class="hidden">featured</th>
                                    <th class="hidden">status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($activities as $activity)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($activity->featured_image)
                                                <img src="{{ Storage::disk('public')->url($activity->featured_image) }}"
                                                    alt="{{ $activity->name }}"
                                                    class="h-10 w-16 object-cover rounded shadow-sm">
                                            @else
                                                <div
                                                    class="h-10 w-16 bg-gray-100 rounded flex items-center justify-center border border-gray-200">
                                                    <span class="text-[10px] text-gray-400 uppercase font-bold">No
                                                        Image</span>
                                                </div>
                                            @endif
                                        </td>
                                        <!-- Removed featured badge from its own column -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $activity->name }}</div>
                                            <div class="text-xs text-gray-500 gap-1">
                                                {{ $activity->duration }}
                                                @if ($activity->is_featured)
                                                    <span
                                                        class="mt-1 inline-block px-2.5 py-0.5 text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Featured
                                                    </span>
                                                @endif
                                            </div>

                                        </td>
                                        <!-- Removed Price Cell -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $activity->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($activity->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center space-x-3">
                                                <a href="{{ route('experience-activities.edit', $activity->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                                </a>
                                                <form
                                                    action="{{ route('experience-activities.destroy', $activity->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this item?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 transition-colors">
                                                        <i class="fa-regular fa-trash-can"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="hidden">{{ $activity->is_featured ? 'featured' : 'regular' }}</td>
                                        <td class="hidden">{{ ucfirst($activity->status) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-sm text-gray-500 italic">
                                            No experiences or activities found. Get started by adding one!
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
                let table = $('#activitiesTable').DataTable({
                    paging: true,
                    searching: true,
                    order: [
                        [2, 'asc']
                    ],
                    dom: 't<"bottom"p>',
                    columnDefs: [{
                            targets: [0, 5],
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


                // Removed typeFilter event

                $('#featuredFilter').on('change', function() {
                    const val = $(this).val();
                    // The hidden featured column index may have changed after removing columns. Adjust to correct index.
                    // Find the index of the hidden featured column
                    const featuredColIdx = $('#activitiesTable thead th').filter(function() {
                        return $(this).hasClass('hidden') && $(this).text().toLowerCase().includes(
                            'featured');
                    }).index();
                    table.column(featuredColIdx).search(val ? '^' + val + '$' : '', true, false).draw();
                });

                $('#statusFilter').on('change', function() {
                    const val = $(this).val();
                    table.column(7).search(val ? '^' + val + '$' : '', true, false).draw();
                });
            });
        </script>
    @endpush
</x-app-layout>
