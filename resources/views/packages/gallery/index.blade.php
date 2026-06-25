<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Package Gallery Management') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">Manage multiple photos for your tour packages</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        showModal: false,
        currentPackage: null,
        existingPhotos: [],
        newPhotos: [],
        newPreviews: [],
        isLoading: false,

        openGallery(pkg) {
            this.currentPackage = pkg;
            this.existingPhotos = pkg.gallery ? pkg.gallery.photos : [];
            this.newPhotos = [];
            this.newPreviews = [];
            this.showModal = true;
        },

        fileError: null,

        handleFiles(event) {
            this.fileError = null;
            const files = Array.from(event.target.files);
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            files.forEach(file => {
                if (!validTypes.includes(file.type)) {
                    this.fileError = 'One or more files are not valid images (JPEG, PNG, GIF, WEBP only).';
                    return;
                }
                if (file.size > maxSize) {
                    this.fileError = 'One or more files exceed the 2MB limit.';
                    return;
                }
                this.newPhotos.push(file);
                this.newPreviews.push(URL.createObjectURL(file));
            });
            event.target.value = null; // Reset input
        },

        removeExisting(index) {
            this.existingPhotos.splice(index, 1);
        },

        removeNew(index) {
            this.newPhotos.splice(index, 1);
            this.newPreviews.splice(index, 1);
        },

        async saveGallery() {
            this.isLoading = true;
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            
            this.existingPhotos.forEach(photo => {
                formData.append('existing_photos[]', photo);
            });

            this.newPhotos.forEach(file => {
                formData.append('new_photos[]', file);
            });

            try {
                const response = await fetch(`/package-galleries/${this.currentPackage.id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    const data = await response.json();
                    this.fileError = data.message || 'Failed to save gallery.';
                    this.isLoading = false;
                }
            } catch (error) {
                this.fileError = 'A network error occurred.';
                this.isLoading = false;
            }
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($packages as $package)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="relative h-48 bg-gray-100">
                            @if($package->featured_image)
                                <img src="{{ Storage::disk('public')->url($package->featured_image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-image text-4xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2 bg-black/50 text-white px-2 py-1 rounded text-xs backdrop-blur-sm">
                                {{ count($package->gallery->photos ?? []) }} Images
                            </div>
                        </div>
                        <div class="p-4 space-y-3">
                            <h3 class="font-bold text-gray-900 truncate">{{ $package->name }}</h3>
                            <p class="text-xs text-gray-500 truncate">{{ $package->category->name ?? 'No Category' }}</p>
                            
                            <button @click="openGallery({{ json_encode($package) }})" 
                                class="w-full bg-indigo-50 text-indigo-700 py-2 rounded-md text-sm font-semibold hover:bg-indigo-100 transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-images"></i> Manage Gallery
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-lg border-2 border-dashed border-gray-200">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-400 mb-4">
                            <i class="fa-solid fa-box-open text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">No Packages Found</h3>
                        <p class="text-sm text-gray-500 mt-1">Start by creating your first tour package.</p>
                        <a href="{{ route('packages.create') }}" class="inline-flex items-center gap-2 mt-6 bg-indigo-600 text-white px-6 py-2 rounded-md font-bold hover:bg-indigo-700 transition-colors">
                            <i class="fa-solid fa-plus"></i> Create Package
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <div x-show="showModal" 
            class="fixed inset-0 z-50 overflow-hidden" 
            x-cloak
            @keydown.escape.window="showModal = false"
            style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" 
                    x-cloak
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" 
                    @click="showModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg sm:my-8">
                    
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-gray-900" x-text="currentPackage ? currentPackage.name : ''"></h3>
                            <p class="text-[10px] text-gray-500 uppercase font-semibold">Gallery Manager</p>
                        </div>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form @submit.prevent="saveGallery()" enctype="multipart/form-data">
                        <div class="p-5 space-y-5 max-h-[60vh] overflow-y-auto">
                            <!-- Existing Photos -->
                            <div class="space-y-2">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Current Gallery</h4>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="(photo, index) in existingPhotos" :key="index">
                                        <div class="relative w-10 h-10 group rounded-sm overflow-hidden bg-gray-100 border border-gray-200 shadow-sm">
                                            <img :src="'/storage/' + photo" class="w-full h-full object-cover">
                                            <input type="hidden" name="existing_photos[]" :value="photo">
                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <button type="button" @click="removeExisting(index)" class="text-white hover:text-red-400 transition-colors">
                                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="existingPhotos.length === 0">
                                        <div class="w-full py-2 text-center border border-dashed border-gray-200 rounded text-[10px] text-gray-400 italic">
                                            Empty
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- New Uploads -->
                            <div class="space-y-2">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Add New</h4>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="(preview, index) in newPreviews" :key="index">
                                        <div class="relative w-10 h-10 group rounded-sm overflow-hidden bg-indigo-50 border border-indigo-100 shadow-sm">
                                            <img :src="preview" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <button type="button" @click="removeNew(index)" class="text-white hover:text-red-400 transition-colors">
                                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- Upload Button -->
                                    <label class="relative w-10 h-10 flex items-center justify-center border-2 border-dashed border-indigo-200 rounded-sm bg-indigo-50/30 hover:bg-indigo-100 cursor-pointer transition-colors group">
                                        <i class="fa-solid fa-plus text-[10px] text-indigo-400 group-hover:text-indigo-600"></i>
                                        <input type="file" name="new_photos[]" multiple accept="image/*" class="hidden" @change="handleFiles">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="px-5 py-2" x-show="fileError">
                            <p class="text-[10px] font-bold text-red-500 bg-red-50 px-3 py-1.5 rounded-sm border border-red-100" x-text="fileError"></p>
                        </div>

                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                            <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                :disabled="isLoading"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md text-xs font-bold hover:bg-indigo-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                <span x-show="!isLoading">Save Changes</span>
                                <span x-show="isLoading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Saving...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
