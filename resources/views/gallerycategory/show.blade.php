<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $category->name }} - {{ __('Manage Images') }}
            </h2>

        </div>
    </x-slot>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('gallery-categories.index') }}"
                    class="px-1 py-2  text-gray-700 rounded-lg  transition text-sm font-medium mb-4 block w-30">
                    &larr; Back to Categories
                </a>
                <!-- Category Info -->
                <div class="flex justify-between items-start mb-8 pb-6 border-b">

                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h3>



                        <p class="text-xs text-gray-400 mt-4 uppercase tracking-wider font-semibold">
                            {{ $category->images->count() }} {{ Str::plural('Image', $category->images->count()) }}
                            Total
                        </p>
                    </div>
                    <div x-data="imageModal({{ $category->id }})">
                        <x-add-image :categoryId="$category->id" />
                    </div>
                </div>

                <!-- Images Grid -->
                <div x-data="{}"
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach ($category->images as $image)
                        <div
                            class="relative group aspect-square rounded-xl overflow-hidden border bg-gray-50 shadow-sm hover:shadow-md transition-shadow">
                            <img src="{{ Storage::url($image->image) }}" alt="{{ $image->name }}"
                                class="object-cover w-full h-full transform transition-transform duration-500 group-hover:scale-110">

                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center p-4 text-center">
                                <p class="text-white text-sm font-bold line-clamp-2 uppercase tracking-tight">
                                    {{ $image->name }}</p>



                                {{-- Image Actions --}}
                                <div class="mt-4 flex items-center gap-3">
                                    <x-edit-gallery-image :image="$image" />

                                    <button type="button"
                                        @click="if(confirm('Are you sure you want to delete this image?')) { 
                                        fetch('{{ route('gallery-images.destroy', $image->id) }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify({ _method: 'DELETE' })
                                        }).then(() => window.location.reload());
                                    }"
                                        class="p-1 text-red-400 hover:text-red-600 transition" title="Delete Image">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endforeach

                    @if ($category->images->count() === 0)
                        <div
                            class="col-span-full py-24 flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-100 rounded-2xl">
                            <div class="bg-gray-50 p-4 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-medium text-gray-600">No images yet</h4>
                            <p class="text-sm italic">Start building your gallery by adding some photos.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
