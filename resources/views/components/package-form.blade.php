@props(['package' => null, 'categories' => [], 'currencies' => []])

<div class="p-0 text-gray-900" x-data="{
    featuredImg: {{ $package && $package->featured_image ? "'" . Storage::disk('public')->url($package->featured_image) . "'" : 'null' }},
    name: {{ json_encode(old('name', $package->name ?? '')) }},
    category_id: {{ json_encode(old('category_id', $package->category_id ?? '')) }},
    duration: {{ json_encode(old('duration', $package->duration ?? '')) }},
    grade: {{ json_encode(old('grade', $package->grade ?? '')) }},
    best_for: {{ json_encode(old('best_for', $package->best_for ?? '')) }},
    price: {{ json_encode(old('price', $package->price ?? '')) }},
    currency_id: {{ json_encode(old('currency_id', $package->currency_id ?? '')) }},
    excerpt: {{ json_encode(old('excerpt', $package->excerpt ?? '')) }},
    overview: {{ json_encode(old('overview', $package->overview ?? '')) }},
    includes: {{ json_encode(old('includes', $package->includes ?? [''])) }},
    itinerary: {{ json_encode(old('itinerary', $package->itinerary ?? [['day' => 'Day 1', 'description' => '']])) }},
    status: {{ json_encode(old('status', $package->status ?? 'active')) }},

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
    addInclude() {
        this.includes.push('');
    },
    removeInclude(index) {
        if (this.includes.length > 1) {
            this.includes.splice(index, 1);
        }
    },
    addItinerary() {
        const nextDay = this.itinerary.length + 1;
        this.itinerary.push({ day: 'Day ' + nextDay, description: '' });
    },
    removeItinerary(index) {
        if (this.itinerary.length > 1) {
            this.itinerary.splice(index, 1);
            this.itinerary.forEach((item, i) => {
                item.day = 'Day ' + (i + 1);
            });
        }
    }
}">
    <form class="flex flex-col lg:flex-row gap-6 items-start" method="POST" enctype="multipart/form-data"
        action="{{ $package ? route('packages.update', $package->id) : route('packages.store') }}">
        @csrf
        @if ($package)
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
                            <label class="text-sm font-medium text-gray-700">Package Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                                placeholder="Enter package name" required>
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                            <select name="category_id" id="categorySelect"
                                class="js-category-select w-full rounded-md border-gray-300 focus:ring-indigo-500">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $package->category_id ?? '') == $category->id) ? 'selected' : '' }}>
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
                            <label class="text-sm font-medium text-gray-700">Duration</label>
                            <input type="text" name="duration" x-model="duration"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500"
                                placeholder="e.g., 14 Days">
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Difficulty</label>
                            <input type="text" name="grade" x-model="grade"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500"
                                placeholder="e.g., Moderate">
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Best For</label>
                            <input type="text" name="best_for" x-model="best_for"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500"
                                placeholder="e.g., Solo, Couples">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Price</label>
                            <div class="flex items-center gap-2 flex-nowrap whitespace-nowrap">
                                <div class="w-28 shrink-0">
                                    <select name="currency_id" x-model="currency_id"
                                        class="w-full px-2 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 text-sm">
                                        <option value="">Currency</option>
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->id }}" :selected="currency_id == {{ $currency->id }}">{{ $currency->sign }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="number" step="0.01" name="price" x-model="price"
                                    class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 text-sm"
                                    placeholder="0.00">
                                <span class="text-sm text-gray-500 font-medium">/ per person</span>
                            </div>
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
                        <label class="text-sm font-medium text-gray-700">Featured Image</label>
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
                        <p x-text="fileError" class="text-xs text-red-600 font-medium" x-show="fileError"></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Short Excerpt</label>
                        <textarea name="excerpt" x-model="excerpt" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500"
                            placeholder="Brief summary..."></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Detailed Overview</label>
                        <textarea name="overview" x-model="overview" rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500"
                            placeholder="Full description..."></textarea>
                    </div>
                </div>
            </section>

            <!-- Itinerary -->
            <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Itinerary Schedule</h3>
                    <button type="button" @click="addItinerary()"
                        class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Day
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <template x-for="(item, index) in itinerary" :key="index">
                        <div class="p-4 border border-gray-200 rounded-lg space-y-3 relative bg-gray-50/30">
                            <div class="flex justify-between items-center">
                                <input type="text" :name="'itinerary_days[' + index + ']'" x-model="item.day"
                                    class="font-bold text-gray-900 bg-transparent border-none p-0 focus:ring-0 w-24">
                                <button type="button" @click="removeItinerary(index)"
                                    class="text-red-500 hover:text-red-700 text-sm"
                                    x-show="itinerary.length > 1">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                            <textarea :name="'itinerary_desc[' + index + ']'" x-model="item.description"
                                rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 bg-white"
                                placeholder="Describe the day's activities..."></textarea>
                        </div>
                    </template>
                </div>
            </section>

            <!-- Includes -->
            <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">What's Included</h3>
                    <button type="button" @click="addInclude()"
                        class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Item
                    </button>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <template x-for="(include, index) in includes" :key="index">
                            <div class="flex items-center gap-2">
                                <input type="text" :name="'includes[' + index + ']'" x-model="includes[index]"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 text-sm"
                                    placeholder="e.g., Breakfast">
                                <button type="button" @click="removeInclude(index)"
                                    class="text-gray-400 hover:text-red-500"
                                    x-show="includes.length > 1">
                                    <i class="fa-solid fa-xmark"></i>
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
                        <span class="text-sm font-medium text-gray-700">Featured Package</span>
                        <input type="checkbox" name="is_featured" value="1"
                            {{ old('is_featured', $package->is_featured ?? false) ? 'checked' : '' }}
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Status</label>
                        <select name="status" x-model="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2.5 rounded-md font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
                        {{ $package ? 'Update Package' : 'Publish Package' }}
                    </button>
                    <a href="{{ route('packages.index') }}"
                        class="w-full bg-white border border-gray-300 text-gray-700 py-2.5 rounded-md font-semibold text-center hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>

            @if($package)
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h4 class="text-xs font-bold text-gray-500 uppercase mb-4">Package Info</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Created by:</span>
                        <span class="font-medium">{{ $package->creator->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Created at:</span>
                        <span class="font-medium">{{ $package->created_at->format('M d, Y') }}</span>
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
