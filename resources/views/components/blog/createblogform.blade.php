@props(['blog' => null, 'categories' => null, 'featuredCount' => 0])

<div class="p-6 text-gray-900" x-data="{
    featuredImg: {{ $blog && $blog->featured_image ? "'" . Storage::disk('public')->url($blog->featured_image) . "'" : 'null' }},
    openCategoryModal: false,
    fileError: '',
    featuredCount: {{ $featuredCount }},
    isCurrentlyFeatured: {{ $blog && $blog->is_featured ? 'true' : 'false' }},
    get canFeature() {
        return this.isCurrentlyFeatured || this.featuredCount < 3;
    },
    validateFile(event) {
        const file = event.target.files[0];
        this.fileError = '';
        if (!file) return;

        const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp', 'image/svg+xml'];
        const maxSize = 2 * 1024 * 1024;

        if (!validTypes.includes(file.type)) {
            this.fileError = 'Invalid file type! Allowed: PNG, JPG, GIF, WEBP, SVG.';
            event.target.value = null;
            this.featuredImg = null;
            return;
        }

        if (file.size > maxSize) {
            this.fileError = 'File is too large! Maximum size is 2MB.';
            event.target.value = null;
            this.featuredImg = null;
            return;
        }

        this.featuredImg = URL.createObjectURL(file);
    },

    init() {
        window.addEventListener('open-category-modal', () => {
            this.openCategoryModal = true;
        });
        window.addEventListener('close-category-modal', () => {
            this.openCategoryModal = false;
        });
    }
}">
    <form id="blogForm" class="space-y-8" method="POST" enctype="multipart/form-data"
        action="{{ $blog ? route('blogs.update', $blog->id) : route('blogs.store') }}">
        @csrf
        @if ($blog)
            @method('PUT')
        @endif

        <div class="flex flex-col xl:flex-row gap-8">
            <div class="flex-1 space-y-8">
                <!-- Page Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ __('Blog Content Details') }}
                        </h3>
                        @if (!empty($blog) && !empty($blog->status))
                            @if ($blog->status === 'published')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                    Published
                                </span>
                            @elseif($blog->status === 'draft')
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-500/20">
                                    Draft
                                </span>
                            @endif
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="blog_title" :value="__('Title')" class="mb-2 font-bold text-gray-700" />
                            <input type="text" id="blog_title" name="blog_title"
                                value="{{ old('blog_title', $blog->blog_title ?? '') }}"
                                placeholder="Enter a compelling title..."
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none text-gray-900 placeholder-gray-400"
                                required
                                aria-describedby="title-error">
                            @error('blog_title')
                                <p id="title-error" class="mt-2 text-sm text-rose-500 font-medium">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <span class="block text-sm font-bold text-gray-700 mb-2">Feature Photo</span>
                            <div @click="$refs.featuredImg.click()"
                                @dragover.prevent="$el.classList.add('border-indigo-500', 'bg-indigo-50/50')"
                                @dragleave.prevent="$el.classList.remove('border-indigo-500', 'bg-indigo-50/50')"
                                @drop.prevent="$el.classList.remove('border-indigo-500', 'bg-indigo-50/50'); $refs.featuredImg.files = $event.dataTransfer.files; validateFile({target: $refs.featuredImg})"
                                class="group relative border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center hover:border-indigo-400 hover:bg-gray-50 transition-all cursor-pointer overflow-hidden shadow-inner">
                                
                                <div x-show="!featuredImg" class="space-y-3">
                                    <div>
                                        <p class="text-sm font-bold text-gray-700">Click to upload or drag and drop</p>
                                        <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider">PNG, JPG, GIF, WEBP up to 2MB</p>
                                    </div>
                                </div>

                                <div x-show="featuredImg" class="relative group/preview">
                                    <img :src="featuredImg" alt="Background" class="max-h-64 mx-auto rounded-xl shadow-lg transition-all group-hover/preview:brightness-75">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover/preview:opacity-100 transition-opacity">
                                        <span class="bg-white/90 backdrop-blur px-4 py-2 rounded-full text-xs font-bold text-gray-700 shadow-sm">Change Image</span>
                                    </div>
                                    <button
                                        @click.stop="featuredImg = null; $refs.featuredImg.value = null; fileError = '';"
                                        type="button"
                                        class="absolute top-2 right-2 bg-white/90 backdrop-blur text-rose-500 rounded-full w-8 h-8 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm z-10"
                                        aria-label="Remove image">
                                        &times;
                                    </button>
                                </div>
                                <input type="file" id="featured_image" accept="image/*" x-ref="featuredImg" name="featured_image"
                                    class="hidden" @change="validateFile($event)">
                            </div>
                            <p x-text="fileError" class="mt-2 text-sm text-rose-500 font-medium" x-show="fileError"></p>
                            @error('featured_image')
                                <p class="mt-2 text-sm text-rose-500 font-medium">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Category Dropdown -->
                        <div>
                            <label for="categorySelect" class="block text-sm font-bold text-gray-700 mb-2">Category <span class="text-rose-500">optional</span></label>
                            <div class="relative">
                                <select name="category_id" id="categorySelect"
                                    class="form-control js-category-select w-full rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500">
                                    <option value="">
                                        {{ empty($categories) || $categories->isEmpty() ? 'No categories available' : 'Select Category' }}
                                    </option>
                                    @if (!empty($categories) && $categories->count())
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $blog->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                    <option value="__add_new__" class="text-indigo-600 font-bold">+ Add New Category</option>
                                </select>
                            </div>
                            @error('category_id')
                                <p class="mt-2 text-sm text-rose-500 font-medium">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Blog Content -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">
                        {{ __('Post Content') }}
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="excerpt" :value="__('Excerpt')" class="mb-2 font-bold text-gray-700" />
                            <x-text-input id="excerpt" name="excerpt" type="text" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none text-gray-900 placeholder-gray-400"
                                :value="old('excerpt', $blog->excerpt ?? '')" required placeholder="A short summary of your post..." 
                                aria-describedby="excerpt-error" />
                            <x-input-error id="excerpt-error" class="mt-2" :messages="$errors->get('excerpt')" />
                        </div>

                        <div>
                            <label for="blog_content" class="block text-sm font-bold text-gray-700 mb-2">Blog Content</label>
                            <div class="rounded-xl overflow-hidden border border-gray-200 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all">
                                <input type="hidden" name="content" id="content_hidden" value="{{ old('content', $blog->content ?? '') }}">
                                <textarea id="blog_content" class="w-full" aria-label="Blog Content editor">{{ old('content', $blog->content ?? '') }}</textarea>
                            </div>
                            @error('content')
                                <p class="mt-2 text-sm text-rose-500 font-medium">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tags -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">
                        {{ __('Search Keywords') }}
                    </h3>
                    <div class="space-y-4">
                        <label for="blog_keywords" class="block text-sm font-bold text-gray-700 mb-2">Keywords</label>
                        <select id="blog_keywords" name="keywords[]" class="form-control js-example-tokenizer w-full" multiple aria-label="Keywords">
                            @if ($blog && $blog->keywords)
                                @foreach ($blog->keywords as $tag)
                                    <option value="{{ $tag }}" selected>{{ $tag }}</option>
                                @endforeach
                            @endif
                        </select>
                        <p class="text-xs text-gray-400">Type a keyword and press enter or comma to add it.</p>
                        @error('keywords')
                            <p class="mt-2 text-sm text-rose-500 font-medium">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar Preview + Publish -->
            <div class="w-full xl:w-96 sticky top-8 self-start space-y-8">
                <!-- Publish Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">
                        Publishing
                    </h3>
                    
                    <input type="hidden" name="status" id="statusInput"
                            value="{{ old('status', $blog->status ?? 'draft') }}">

                    <!-- Feature Toggle -->
                    <div class="mb-8 p-5 bg-indigo-50/50 rounded-2xl border border-indigo-100/50 group transition-all hover:bg-indigo-50">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div class="flex flex-col pr-4">
                                <span class="text-sm font-bold text-gray-800">Featured Post</span>
                                <p class="text-[11px] text-gray-500 leading-tight mt-1">Highlighted on homepage</p>
                                <template x-if="!canFeature">
                                    <span class="text-[10px] text-rose-600 font-bold uppercase mt-2 flex items-center bg-white px-2 py-0.5 rounded-full shadow-sm w-fit">
                                        Limit (3/3)
                                    </span>
                                </template>
                            </div>
                            <div class="relative shrink-0">
                                <input type="checkbox" name="is_featured" value="1"
                                    {{ old('is_featured', $blog->is_featured ?? false) ? 'checked' : '' }}
                                    class="w-6 h-6 text-indigo-600 border-gray-300 rounded-lg focus:ring-indigo-500/20 transition-all cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                                    :disabled="!canFeature"
                                    @change="if(!canFeature) { $el.checked = false; }">
                            </div>
                        </label>
                    </div>

                    <div class="space-y-4">
                        <button type="button" onclick="setStatus('published')"
                            class="w-full flex items-center justify-center bg-indigo-600 text-white font-bold py-3.5 px-6 rounded-xl hover:bg-indigo-700 active:scale-[0.98] transition-all shadow-lg shadow-indigo-200">
                            {{ $blog ? 'Update & Publish' : 'Publish Post' }}
                        </button>
                        <button type="button" onclick="setStatus('draft')"
                            class="w-full flex items-center justify-center bg-white text-gray-700 font-bold py-3.5 px-6 rounded-xl border border-gray-200 hover:bg-gray-50 active:scale-[0.98] transition-all">
                            {{ $blog ? 'Update as Draft' : 'Save Draft' }}
                        </button>
                    </div>
                </div>

                <!-- Preview Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 overflow-hidden transition-all hover:shadow-md">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">
                        Post Preview
                    </h3>
                    <div class="rounded-xl border border-gray-100 overflow-hidden bg-gray-50 group">
                        <div x-show="featuredImg" class="aspect-video relative overflow-hidden">
                            <img :src="featuredImg" alt="Feature Preview"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                        <div x-show="!featuredImg"
                            class="aspect-video flex flex-col items-center justify-center text-gray-400 bg-gray-50 border-b border-gray-100">
                            <span class="text-xs font-medium uppercase tracking-widest">No Image</span>
                        </div>
                        <div class="p-4 bg-white">
                            <div class="h-4 bg-gray-100 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-50 rounded w-full mb-1"></div>
                            <div class="h-3 bg-gray-50 rounded w-5/6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Category Modal -->
    <div x-show="openCategoryModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        @click.self="openCategoryModal = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 transform transition-all border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <h3 class="text-xl font-bold text-gray-900">Add New Category</h3>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label for="newCategoryName" class="block text-sm font-bold text-gray-700 mb-2">Category Name</label>
                    <input type="text" id="newCategoryName"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none"
                        placeholder="e.g. Technology, Lifestyle">
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="button" @click="openCategoryModal = false"
                    class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="button" id="saveCategoryBtn"
                    class="flex-[2] px-4 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-[0.98] flex items-center justify-center">
                    <span id="saveBtnText">Save Category</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="module">
        
        let uploadedImages = @json($blog && $blog->content_images ? $blog->content_images : []);
        let deletedImages = [];
        let previousImages = [...uploadedImages];

        const editor = SUNEDITOR.create(document.getElementById('blog_content'), {
            height: 'auto',
            plugins: plugins,
            minHeight: '300px',
            maxHeight: 'none',
            width: '100%',
            buttonList: [
                ['undo', 'redo'],
                ['bold', 'underline', 'italic', 'strike'],
                ['fontColor', 'backgroundColor'],
                ['list', 'align', 'fontSize'],
                ['link', 'removeFormat'],
                ['fullScreen']
            ],
            placeholder: 'Write your blog content here...',
            resizingBar: false,
            charCounter: true,
            charCounterLabel: 'Characters:',
            katex: 'window.katex',
            imagePaste: false

        });


        editor.onChange = function(contents, core) {
            // Immediate sync to hidden input
            document.getElementById('content_hidden').value = contents;

            const currentImages = extractImagesFromContent(contents);
            const removed = previousImages.filter(img => !currentImages.includes(img));

            if (removed.length > 0) {
                removed.forEach(imagePath => {
                    deleteImageImmediately(imagePath);

                    if (!deletedImages.includes(imagePath)) {
                        deletedImages.push(imagePath);
                    }

                    uploadedImages = uploadedImages.filter(img => img !== imagePath);
                });
            }

            previousImages = [...currentImages];
        };

        editor.onBlur = function() {
            let contents = '';
            if (typeof editor.getContents === 'function') {
                contents = editor.getContents();
            } else {
                contents = editor.$.frameContext.get('wysiwyg').innerHTML;
            }
            document.getElementById('content_hidden').value = contents;
        };

        function extractImagesFromContent(htmlContent) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = htmlContent;
            const images = tempDiv.querySelectorAll('img');
            const imagePaths = [];

            images.forEach(img => {
                const src = img.src || img.getAttribute('src');
                if (src && src.includes('blog-content-images/')) {
                    const imagePath = src.replace(window.location.origin + '/public/storage/', '');
                    imagePaths.push(imagePath);
                }
            });

            return imagePaths;
        }

        function deleteImageImmediately(imagePath) {
            fetch('/api/delete-editor-image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        image_path: imagePath,
                        _token: '{{ csrf_token() }}'
                    })
                })
                .then(response => response.json())
                .catch(error => {
                    throw new Error('Error deleting image');
                });
        }


        window.setStatus = function(status) {
            let contents = '';
            if (typeof editor.getContents === 'function') {
                contents = editor.getContents();
            } else {
                contents = editor.$.frameContext.get('wysiwyg').innerHTML;
            }
            document.getElementById('content_hidden').value = contents;
            
            document.getElementById('statusInput').value = status;

            const form = document.getElementById('blogForm');
            if (deletedImages.length > 0) {
                const deletedInput = document.createElement('input');
                deletedInput.type = 'hidden';
                deletedInput.name = 'deleted_images';
                deletedInput.value = JSON.stringify(deletedImages);
                form.appendChild(deletedInput);
            }

            // Manually submit the form to ensure all JS finishes
            form.submit();
        };

        // Final safeguard to ensure editor saves on any form submit
        document.getElementById('blogForm').addEventListener('submit', function() {
            let contents = '';
            if (typeof editor.getContents === 'function') {
                contents = editor.getContents();
            } else {
                contents = editor.$.frameContext.get('wysiwyg').innerHTML;
            }
            document.getElementById('content_hidden').value = contents;
        });
    </script>

    <script type="module">
        $(document).ready(function() {
            $('.js-example-tokenizer').select2({
                tags: true,
                tokenSeparators: [',', ' '],
                width: '100%'
            });

            const $categorySelect = $('.js-category-select');
            $categorySelect.select2({
                width: '100%'
            });

            $categorySelect.on('select2:select', function(e) {
                if (e.params.data.id === '__add_new__') {
                    $(this).val('').trigger('change');
                    window.dispatchEvent(new CustomEvent('open-category-modal'));
                }
            });

            $('#saveCategoryBtn').on('click', function() {
                const name = $('#newCategoryName').val().trim();
                if (!name) return alert('Category name is required');

                const $btn = $(this);
                const $btnText = $('#saveBtnText');
                const originalText = $btnText.text();

                // Loading State
                $btn.prop('disabled', true).addClass('opacity-70 cursor-wait');
                $btnText.text('Saving...');

                $.ajax({
                    url: "{{ route('categories.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        category_name: name
                    },
                    success: function() {
                        $.ajax({
                            url: "{{ route('categories.index') }}",
                            method: 'GET',
                            success: function(categories) {
                                $categorySelect.empty();
                                $categorySelect.append(
                                    '<option value="">Select Category</option>');

                                categories.forEach(function(category) {
                                    const option = new Option(category
                                        .category_name, category.id);
                                    if (category.category_name === name) {
                                        option.selected = true;
                                    }
                                    $categorySelect.append(option);
                                });

                                $categorySelect.append(
                                    '<option value="__add_new__">+ Add New Category</option>'
                                );
                                $categorySelect.trigger('change');

                                $('#newCategoryName').val('');
                                window.dispatchEvent(new CustomEvent(
                                    'close-category-modal'));
                            },
                            complete: function() {
                                $btn.prop('disabled', false).removeClass('opacity-70 cursor-wait');
                                $btnText.text(originalText);
                            }
                        });
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).removeClass('opacity-70 cursor-wait');
                        $btnText.text(originalText);
                        
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
