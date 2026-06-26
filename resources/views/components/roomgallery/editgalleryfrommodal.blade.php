<div x-show="showEditModal" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center">

    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/60" @click="closeEditModal()"></div>

    <!-- Modal -->
    <div class="relative z-10 w-full max-w-xl bg-white rounded-xl shadow-xl">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">
                Edit Gallery Photos
            </h3>
            <button type="button" @click="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-xl">
                &times;
            </button>
        </div>

        <!-- Body -->
        <form :action="`${updateRoute}/${selectedRoomId}`" method="POST" enctype="multipart/form-data"
            class="px-6 py-5 space-y-5"
            @submit.prevent="
                if (!hasImages()) {
                    imageError = 'Please keep at least one image or upload new ones.';
                    return;
                }
                $el.submit();
            ">
            @csrf
            @method('PUT')

            <p class="text-sm text-gray-500">
                Room:
                <span class="font-medium text-gray-700" x-text="selectedRoomName"></span>
            </p>

            <!-- Existing Photos -->
            <div x-show="existingPhotos.length > 0">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Existing Photos
                </label>
                <div class="grid grid-cols-3 gap-3">
                    <template x-for="(photo, index) in existingPhotos" :key="index">
                        <div class="relative group">
                            <img :src="`${baseUrl}/${photo}`" class="h-24 w-full object-cover rounded-lg border">
                            <input type="hidden" name="existing_photos[]" :value="photo">

                            <!-- Remove Button -->
                            <button type="button" @click="removeExistingImage(index)"
                                class="absolute top-1 right-1 bg-black/70 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition"
                                aria-label="Remove image">
                                &times;
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Upload Area -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Add More Photos
                </label>

                <label
                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer hover:border-indigo-500 transition bg-gray-50">
                    <span class="text-sm text-gray-500">
                        Click to upload or drag & drop
                    </span>
                    <span class="text-xs text-gray-400 mt-1">
                        JPG, PNG, SVG (Max 2 MB per image)
                    </span>

                    <input x-ref="fileInput" type="file" name="photos[]" multiple
                        accept="image/jpeg,image/png,image/svg+xml" @change="previewEditImages($event)" class="hidden">
                </label>

                <!-- Error -->
                <div x-show="imageError" class="flex items-start gap-2 text-sm text-red-600 mt-2">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>
                    <span x-text="imageError"></span>
                </div>
            </div>

            <!-- Preview New Images -->
            <div x-show="imagePreviews.length">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Preview New Photos
                </label>

                <div class="grid grid-cols-3 gap-3">
                    <template x-for="(img, i) in imagePreviews" :key="i">
                        <div class="relative group">
                            <img :src="img" class="h-24 w-full object-cover rounded-lg border">

                            <!-- Remove Button -->
                            <button type="button" @click="removeNewImage(i)"
                                class="absolute top-1 right-1 bg-black/70 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition"
                                aria-label="Remove image">
                                &times;
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" @click="closeEditModal()"
                    class="px-4 py-2 text-sm rounded-lg border text-gray-700 hover:bg-gray-100">
                    Cancel
                </button>
                <button type="submit" :disabled="!hasImages()"
                    class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700
                        disabled:opacity-50 disabled:cursor-not-allowed">
                    Update Photos
                </button>
            </div>
        </form>
    </div>
</div>
