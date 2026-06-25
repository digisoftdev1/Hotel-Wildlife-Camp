@props(['categoryId' => null])

<div>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="flex justify-start">
        <button @click="openModal"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-1.5 rounded-lg shadow-md transition text-sm">
            + Add Image
        </button>
    </div>


    <div x-show="isOpen" x-transition @keydown.escape.window="closeModal" x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50">

        <!-- Modal Box -->
        <div @click.away="closeModal" class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">

            <!-- Header -->
            <h2 class="text-xl font-semibold text-gray-800">Add Image</h2>
            <p class="text-gray-500 text-sm mb-4">
                Upload a new image to this gallery category.
            </p>

            <!-- Form -->
            <form @submit.prevent="submitForm">



                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" x-model="form.name"
                            class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200"
                            placeholder="Enter image name">

                        <p x-show="errors.name" class="text-red-500 text-sm mt-1" x-text="errors.name"></p>
                    </div>


                </div>



                <!-- Image -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Upload Image</label>

                    <input type="file" accept="image/*" @change="handleFile"
                        class="w-full mt-1 px-3 py-2 border rounded-lg">

                    <p class="text-gray-400 text-xs mt-1">Max size: 2MB</p>
                    <p x-show="errors.image" class="text-red-500 text-sm mt-1" x-text="errors.image"></p>
                </div>

                <!-- Preview -->
                <template x-if="preview">
                    <div class="mb-4">
                        <img :src="preview" class="h-32 rounded-lg border object-cover">
                    </div>
                </template>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="closeModal"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        :disabled="loading">
                        <span x-show="!loading">Save</span>
                        <span x-show="loading">Saving...</span>
                    </button>
                </div>

            </form>

        </div>
    </div>



    <script>
        function imageModal(categoryId) {
            return {
                isOpen: false,
                loading: false,
                apiError: '',

                form: {
                    name: '',
                    image: null,
                    gallery_category_id: categoryId || '',
                },

                preview: null,
                errors: {},

                openModal() {
                    this.isOpen = true;
                    document.body.classList.add('overflow-hidden');
                },

                closeModal() {
                    this.isOpen = false;
                    document.body.classList.remove('overflow-hidden');
                    this.resetForm();
                },



                handleFile(event) {
                    const file = event.target.files[0];
                    this.errors.image = '';
                    this.preview = null;

                    if (file) {
                        // Type validation
                        if (!file.type.startsWith('image/')) {
                            this.errors.image = "Only image files are allowed";
                            event.target.value = '';
                            return;
                        }

                        // Size validation (2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            this.errors.image = "Image must be less than 2MB";
                            event.target.value = '';
                            return;
                        }

                        this.form.image = file;
                        this.preview = URL.createObjectURL(file);
                    }
                },

                validate() {
                    this.errors = {};
                    this.apiError = '';

                    if (!this.form.name.trim()) {
                        this.errors.name = "Name is required";
                    }

                    if (!this.form.gallery_category_id) {
                        this.apiError = 'Category not found. Please open a specific category page to add images.';
                    }

                    if (!this.form.image) {
                        this.errors.image = "Image is required";
                    }

                    return Object.keys(this.errors).length === 0 && !this.apiError;
                },

                submitForm() {
                    if (this.validate()) {
                        this.loading = true;
                        this.apiError = '';
                        let formData = new FormData();
                        formData.append('name', this.form.name);
                        formData.append('image', this.form.image);
                        formData.append('gallery_category_id', this.form.gallery_category_id);

                        fetch("{{ route('gallery-images.store') }}", {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                }
                            })
                            .then(async res => {
                                if (!res.ok) {
                                    let errorData;
                                    try {
                                        errorData = await res.json();
                                    } catch {
                                        throw new Error('Request failed. Please try again.');
                                    }
                                    this.errors = errorData.errors || {};
                                    this.apiError = errorData.message ||
                                        'Validation failed. Please check your input.';
                                    throw new Error("Validation failed");
                                }
                                return res.json();
                            })
                            .then(data => {
                                if (!data.success) {
                                    this.apiError = data.message || 'Unable to save image.';
                                    return;
                                }
                                this.closeModal();
                                window.location.reload();
                            })
                            .catch(err => {
                                console.error(err);
                                if (!this.apiError) {
                                    this.apiError = err.message || 'Something went wrong while saving image.';
                                }
                            })
                            .finally(() => {
                                this.loading = false;
                            });
                    }
                },


                resetForm() {
                    this.form = {
                        name: '',
                        image: null,
                        gallery_category_id: categoryId || '',
                    };
                    this.preview = null;
                    this.errors = {};
                    this.apiError = '';
                }
            }
        }
    </script>

    <p x-show="apiError" x-cloak class="mt-3 text-sm text-red-600" x-text="apiError"></p>
</div>
