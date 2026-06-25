@props(['categories'])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($categories as $category)
        @continue(!$category)
        <div class="group">
            <div
                class="overflow-hidden bg-transparent hover:bg-gray-100/20 transition-colors duration-300 rounded-lg p-2">

                <a href="{{ route('gallery-categories.show', $category->id) }}" class="block cursor-pointer">
                    <div class="overflow-hidden rounded-md mb-3 relative aspect-[4/3]">
                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                            class="object-cover w-full h-full transform transition-transform duration-500 group-hover:scale-105"
                            data-testid="img-category-{{ $category->id }}" />
                        <div
                            class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 rounded-md">
                        </div>
                    </div>
                </a>

                <div class="flex flex-col items-start gap-1 px-1">
                    <div class="flex items-start justify-between w-full gap-2">
                        <div class="min-w-0">
                            <a href="{{ route('gallery-categories.show', $category->id) }}" class="block">
                                <h3 class="font-serif text-lg font-medium tracking-tight text-gray-900 group-hover:text-gray-600 transition-colors"
                                    data-testid="text-category-title-{{ $category->id }}">
                                    {{ $category->name }}
                                </h3>
                            </a>

                        </div>

                        {{-- Category Actions --}}
                        <div class="flex shrink-0 items-center gap-2 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-opacity"
                            @click.stop>
                            <x-edit-gallery-category :category="$category" />

                            <button
                                @click="if(confirm('Are you sure you want to delete this category? This will permanently remove all associated images and files.')) { 
                                fetch('{{ route('gallery-categories.destroy', $category->id) }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: JSON.stringify({ _method: 'DELETE' })
                                }).then(() => window.location.reload());
                            }"
                                class="p-1 text-red-600 hover:text-red-800 transition" title="Delete Category">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <a href="{{ route('gallery-categories.show', $category->id) }}" class="block w-full cursor-pointer">
                        <p class="text-gray-500 text-xs line-clamp-2 leading-snug"
                            data-testid="text-category-desc-{{ $category->id }}">
                            {{ $category->description }}
                        </p>
                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mt-1">
                            {{ $category->images_count ?? $category->images->count() }} Images
                        </p>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
