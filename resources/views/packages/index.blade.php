<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Packages') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500"> Manage Tour & Activity Packages </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Manage Packages</h2>
                        <div class="flex gap-2">
                            <a href="{{ route('currencies.index') }}"
                                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition shadow-sm">
                                Manage Currencies
                            </a>
                            <a href="{{ route('packages.create') }}"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                Add New Package
                            </a>
                        </div>
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
                            <input id="searchInput" type="text" placeholder="Search packages..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-64 focus:ring-indigo-500 focus:border-indigo-500" />
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <label for="statusFilter" class="text-sm font-medium text-gray-700">Status:</label>
                            <select id="statusFilter"
                                class="text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="packagesTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($packages as $package)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($package->featured_image)
                                                <img src="{{ Storage::disk('public')->url($package->featured_image) }}"
                                                    alt="{{ $package->name }}"
                                                    class="h-10 w-16 object-cover rounded shadow-sm">
                                            @else
                                                <div
                                                    class="h-10 w-16 bg-gray-100 rounded flex items-center justify-center border border-gray-200">
                                                    <span class="text-[10px] text-gray-400 uppercase font-bold">No
                                                        Image</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $package->name }}</div>
                                            @if ($package->is_featured)
                                                <span class="inline-block px-2 py-0.5 text-[10px] leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800 uppercase tracking-wider">Featured</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $package->category->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $package->duration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($package->price)
                                                {{ $package->currency->sign ?? '' }} {{ number_format($package->price, 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $package->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($package->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center space-x-3">
                                                <a href="{{ route('packages.edit', $package->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                                </a>
                                                <form action="{{ route('packages.destroy', $package->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this package?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 transition-colors">
                                                        <i class="fa-regular fa-trash-can"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 italic">
                                            No packages found. Get started by adding one!
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
                let table = $('#packagesTable').DataTable({
                    paging: true,
                    searching: true,
                    dom: 't<"bottom"p>',
                });

                $('#searchInput').on('keyup', function() {
                    table.search(this.value).draw();
                });

                $('#statusFilter').on('change', function() {
                    table.column(4).search($(this).val()).draw();
                });
            });
        </script>
    @endpush
</x-app-layout>
