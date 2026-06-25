@props(['storeRoute', 'indexRoute', 'selectSelector'])

<div x-data="{ openCategoryModal: false }" @open-category-modal.window="openCategoryModal = true"
    @close-category-modal.window="openCategoryModal = false" x-show="openCategoryModal" x-cloak x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40"
    @click.self="openCategoryModal = false">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Category</h3>
        <div class="space-y-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
            <input type="text" id="newCategoryName"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                placeholder="Enter category name">
        </div>
        <div class="space-y-4 mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description <span
                    class="text-gray-400 text-xs">(optional)</span></label>
            <textarea id="newCategoryDescription"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                placeholder="Enter description (optional)"></textarea>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" @click="openCategoryModal = false"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
            <button type="button" id="saveCategoryBtn"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save</button>
        </div>
    </div>
</div>

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            $('#saveCategoryBtn').on('click', function() {
                const name = $('#newCategoryName').val().trim();
                const description = $('#newCategoryDescription').val().trim();
                if (!name) return alert('Category name is required');

                $.ajax({
                    url: "{{ $storeRoute }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        category_name: name,
                        description: description
                    },
                    success: function() {
                        $.ajax({
                            url: "{{ $indexRoute }}",
                            method: 'GET',
                            success: function(categories) {
                                const $categorySelect = $('{{ $selectSelector }}');
                                $categorySelect.empty();
                                $categorySelect.append(
                                    '<option value="">Select Category</option>');

                                categories.forEach(function(category) {
                                    // Assume category objects return 'name' and 'id'
                                    const optionName = category.name ||
                                        category.category_name;
                                    const option = new Option(optionName,
                                        category.id);
                                    if (optionName === name) {
                                        option.selected = true;
                                    }
                                    $categorySelect.append(option);
                                });

                                $categorySelect.append(
                                    '<option value="__add_new__">+ Add New Category</option>'
                                    );
                                $categorySelect.trigger('change');

                                $('#newCategoryName').val('');
                                $('#newCategoryDescription').val('');
                                window.dispatchEvent(new CustomEvent(
                                    'close-category-modal'));
                            }
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            alert(errors.category_name ? errors.category_name[0] :
                                'Validation failed');
                        } else {
                            alert('Failed to add category');
                        }
                    }
                });
            });
        });
    </script>
@endpush
