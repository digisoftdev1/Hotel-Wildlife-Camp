@props(['activity' => null, 'featuredCount' => 0, 'categories' => []])

<div class="p-0 text-gray-900" x-data="{
    featuredImg: {{ $activity && $activity->featured_image ? "'" . Storage::disk('public')->url($activity->featured_image) . "'" : 'null' }},
    name: {{ json_encode(old('name', $activity->name ?? '')) }},
    category_id: {{ json_encode(old('category_id', $activity->category_id ?? '')) }},
    excerpt: {{ json_encode(old('excerpt', $activity->excerpt ?? '')) }},
    duration: {{ json_encode(old('duration', $activity->duration ?? '')) }},
    difficulty_level: {{ json_encode(old('difficulty_level', $activity->difficulty_level ?? '')) }},
    best_time: {{ json_encode(old('best_time', $activity->best_time ?? '')) }},
    overview: {{ json_encode(old('overview', $activity->overview ?? '')) }},
    highlights: {{ json_encode(old('highlights', $activity->highlights ?? [''])) }},
    status: {{ json_encode(old('status', $activity->status ?? 'published')) }},

    featuredCount: {{ $featuredCount }},
    isCurrentlyFeatured: {{ $activity && $activity->is_featured ? 'true' : 'false' }},
    get canFeature() {
        return this.isCurrentlyFeatured || this.featuredCount < 6;
    },

    fileError: '',
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
    addHighlight() {
        this.highlights.push('');
    },
    removeHighlight(index) {
        if (this.highlights.length > 1) {
            this.highlights.splice(index, 1);
        }
    }
}">
    <form class="flex flex-col lg:flex-row gap-6 items-start" method="POST" enctype="multipart/form-data"
        action="{{ $activity ? route('experience-activities.update', $activity->id) : route('experience-activities.store') }}">
        @csrf
        @if ($activity)
            @method('PUT')
        @endif

        <!-- Main Content -->
        <div class="flex-1 w-full space-y-6">
            
            <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                                placeholder="Activity or Experience name" required>
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                            <select name="category_id" class="js-category-select w-full rounded-md border-gray-300 focus:ring-indigo-500" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $activity->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                                <option value="__add_new__">+ Add New Category</option>
                            </select>
                            @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Difficulty <span class="text-red-500">*</span></label>
                            <input type="text" name="difficulty_level" x-model="difficulty_level"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500"
                                placeholder="e.g., Moderate" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Duration <span class="text-red-500">*</span></label>
                            <input type="text" name="duration" x-model="duration"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500"
                                placeholder="e.g., 2-3 Hours" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Best Time</label>
                            <input type="text" name="best_time" x-model="best_time"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500"
                                placeholder="e.g., Spring & Autumn">
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Media & Content</h3>
                </div>

                <div class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Featured Image <span class="text-red-500">*</span></label>
                        <div @click="$refs.featuredImg.click()"
                            class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-all cursor-pointer min-h-[200px] flex flex-col items-center justify-center">
                            
                            <div x-show="!featuredImg">
                                <i class="fa-solid fa-image text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Click to upload image</p>
                                <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 2MB</p>
                            </div>

                            <div x-show="featuredImg" class="w-full relative">
                                <img :src="featuredImg" alt="Preview" class="max-h-64 mx-auto rounded-md shadow-sm">
                                <button @click.stop="featuredImg = null; $refs.featuredImg.value = null; fileError = '';"
                                    type="button"
                                    class="absolute top-0 right-0 bg-red-600 text-white rounded-full p-1.5 shadow-md hover:bg-red-700 transition-colors">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            <input type="file" accept="image/*" x-ref="featuredImg" name="featured_image" class="hidden" @change="validateFile($event)">
                        </div>
                        <p x-text="fileError" class="text-xs text-red-600 font-medium mt-1" x-show="fileError"></p>
                        @error('featured_image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Short Excerpt <span class="text-red-500">*</span></label>
                        <textarea name="excerpt" x-model="excerpt" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500"
                            placeholder="Brief summary for listings..." required></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Overview <span class="text-red-500">*</span></label>
                        <textarea name="overview" x-model="overview" rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500"
                            placeholder="Full detailed description..." required></textarea>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Highlights</h3>
                    <button type="button" @click="addHighlight()"
                        class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Highlight
                    </button>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <template x-for="(highlight, index) in highlights" :key="index">
                            <div class="flex items-center gap-2">
                                <input type="text" :name="'highlights[' + index + ']'" x-model="highlights[index]"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 text-sm"
                                    placeholder="Add a key highlight...">
                                <button type="button" @click="removeHighlight(index)"
                                    class="text-gray-400 hover:text-red-500"
                                    x-show="highlights.length > 1">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </section>
        </div>

        <!-- Sidebar Actions -->
        <aside class="w-full lg:w-80 space-y-6 lg:sticky lg:top-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900">Publish</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md border border-gray-100">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-700">Featured Item</span>
                            <template x-if="!canFeature">
                                <span class="text-[10px] text-red-600 font-bold uppercase mt-1">Limit reached (6/6)</span>
                            </template>
                        </div>
                        <input type="checkbox" name="is_featured" value="1"
                            {{ old('is_featured', $activity->is_featured ?? false) ? 'checked' : '' }}
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            :disabled="!canFeature"
                            @change="if(!canFeature) { $el.checked = false; }">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Status</label>
                        <select name="status" x-model="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                        @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2.5 rounded-md font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
                        {{ $activity ? 'Update Activity' : 'Save & Publish' }}
                    </button>
                    <a href="{{ route('experience-activities.index') }}"
                        class="w-full bg-white border border-gray-300 text-gray-700 py-2.5 rounded-md font-semibold text-center hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>

            @if($activity)
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h4 class="text-xs font-bold text-gray-500 uppercase mb-4">Activity Info</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Created:</span>
                        <span class="font-medium">{{ $activity->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Last Update:</span>
                        <span class="font-medium">{{ $activity->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @endif
        </aside>
    </form>

    <x-add-category-modal 
        storeRoute="{{ route('activity-package-categories.store') }}"
        indexRoute="{{ route('activity-package-categories.index') }}" 
        selectSelector=".js-category-select" />
</div>

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            $('.js-category-select').select2({
                width: '100%'
            }).on('select2:select', function(e) {
                if (e.params.data.id === '__add_new__') {
                    $(this).val('').trigger('change');
                    window.dispatchEvent(new CustomEvent('open-category-modal'));
                }
            });
        });
    </script>
@endpush
