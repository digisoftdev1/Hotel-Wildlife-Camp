<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List of Blog Posts') }}
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

                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                        <div class="flex flex-1 items-center gap-4 w-full">
                            <input id="searchInput" type="text" placeholder="Search blog posts..."
                                class="pl-3 pr-4 py-2 border border-gray-300 rounded-lg w-full md:w-64 focus:ring-indigo-500 shadow-sm" />

                            <form action="{{ route('blogs.index') }}" method="GET" class="flex items-center gap-2">
                                <select name="category_id" onchange="this.form.submit()"
                                    class="border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 shadow-sm">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        <a href="{{ route('blogs.create') }}"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out whitespace-nowrap">
                            + Add Blog Post
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="blogTable" class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Feature Photo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase text-center">Featured</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created At</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($blogs as $index => $blog)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm">{{ $index + 1 }}</td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($blog->featured_image)
                                                <img src="{{ Storage::url($blog->featured_image) }}"
                                                    alt="{{ $blog->blog_title }}"
                                                    class="h-16 w-24 object-cover rounded-lg">
                                            @else
                                                <div
                                                    class="h-16 w-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="max-w-xs">
                                                <p class="text-sm font-semibold text-gray-900 line-clamp-2">
                                                    {{ $blog->blog_title }}
                                                </p>
                                                @if ($blog->is_featured)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                        Featured
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-sm">
                                            @if ($blog->category)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ $blog->category->category_name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-4 text-center">
                                            <div x-data="{ 
                                                featured: {{ $blog->is_featured ? 'true' : 'false' }},
                                                loading: false,
                                                async toggle() {
                                                    if (this.loading) return;
                                                    this.loading = true;
                                                    try {
                                                        const response = await axios.patch('{{ route('blogs.toggle-featured', $blog->id) }}');
                                                        this.featured = response.data.is_featured;
                                                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: response.data.message, type: 'success' } }));
                                                    } catch (error) {
                                                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: error.response?.data?.message || 'Error updating status', type: 'error' } }));
                                                    } finally {
                                                        this.loading = false;
                                                    }
                                                }
                                            }">
                                                <button @click="toggle()" :disabled="loading" class="relative inline-flex items-center focus:outline-none group">
                                                    <div class="w-10 h-5 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out group-hover:bg-gray-300"
                                                         :class="featured ? 'bg-indigo-600' : 'bg-gray-200'"></div>
                                                    <div class="absolute left-1 w-3 h-3 bg-white rounded-full transition-transform duration-200 ease-in-out transform"
                                                         :class="featured ? 'translate-x-5' : 'translate-x-0'"></div>
                                                </button>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <span
                                                class="px-3 py-1 text-xs rounded-full
                                                {{ $blog->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                                {{ ucfirst($blog->status) }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <div>
                                                <p class="font-medium">{{ $blog->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex items-center space-x-3">
                                                {{-- View button: uses slug if published and has slug --}}
                                                @if ($blog->status === 'published' && $blog->slug)
                                                    <a href="{{ route('blogs.show', $blog->slug) }}"
                                                        class="text-blue-600 hover:text-blue-900" title="View">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                @endif

                                                {{-- Edit button: always uses ID --}}
                                                <a href="{{ route('blogs.edit', $blog->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    Edit
                                                </a>

                                                {{-- Delete button: always uses ID --}}
                                                <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST"
                                                    onsubmit="return confirm('Delete this blog post?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center px-6 py-6 text-gray-500">
                                            No blog posts found.
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
