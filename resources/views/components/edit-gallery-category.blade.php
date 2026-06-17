@props(['category'])

<div x-data="editCategoryModal_{{ $category->id }}()">
    <button @click="openModal" class="p-1 text-blue-600 hover:text-blue-800 transition" title="Edit Category">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
    </button>

    <div x-show="isOpen" x-transition @keydown.escape.window="closeModal" x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50">

        <div @click.away="closeModal" class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800">Edit Category</h2>
            <p class="text-gray-500 text-sm mb-4">Update the name, description, or featured image.</p>

            <form @submit.prevent="submitForm">


                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" x-model="form.name"
                            class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                        <p x-show="errors.name" class="text-red-500 text-sm mt-1" x-text="errors.name"></p>
                    </div>


                </div>



                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">New Featured Image (Optional)</label>
                    <input type="file" accept="image/*" @change="handleFile"
                        class="w-full mt-1 px-3 py-2 border rounded-lg">
                    <p class="text-gray-400 text-xs mt-1">Keep empty to retain current image.</p>
                </div>

                <template x-if="preview">
                    <div class="mb-4">
                        <img :src="preview" class="h-32 rounded-lg border object-cover">
                    </div>
                </template>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="closeModal"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        :disabled="loading">
                        <span x-show="!loading">Update</span>
                        <span x-show="loading">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editCategoryModal_{{ $category->id }}() {
            return {
                isOpen: false,
                loading: false,
                apiError: '',
                form: {
                    name: @js($category->name),
                    image: null,
                },
                preview: null,
                errors: {},
                openModal() {
                    this.isOpen = true;
                    document.body.classList.add('overflow-hidden');
                    this.apiError = '';
                },
                closeModal() {
                    this.isOpen = false;
                    document.body.classList.remove('overflow-hidden');
                },

                handleFile(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.form.image = file;
                        this.preview = URL.createObjectURL(file);
                    }
                },
                submitForm() {
                    this.loading = true;
                    this.apiError = '';
                    this.errors = {};
                    let formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('name', this.form.name);
                    if (this.form.image) formData.append('image', this.form.image);

                    fetch("{{ route('gallery-categories.update', $category->id) }}", {
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
                                this.apiError = errorData.message || 'Validation failed. Please check your input.';
                                throw new Error("Update failed");
                            }
                            return res.json();
                        })
                        .then((data) => {
                            if (!data.success) {
                                this.apiError = data.message || 'Unable to update category.';
                                return;
                            }
                            window.location.reload();
                        })
                        .catch(err => {
                            console.error(err);
                            if (!this.apiError) {
                                this.apiError = err.message || 'Something went wrong while updating category.';
                            }
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            }
        }
    </script>

    <p x-show="apiError" x-cloak class="mt-3 text-sm text-red-600" x-text="apiError"></p>
</div>
