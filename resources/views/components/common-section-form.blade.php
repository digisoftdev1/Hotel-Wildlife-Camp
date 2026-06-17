@props([
    'page' => null,
    'section' => null,
    'sectionType' => '',
    'sectionLabel' => 'Section',
    'action' => '',
    'pages' => [],
    'contentMeta' => [],
])


@php
    $existingButtons = $section ? $section->ctaButtons->toArray() : [];
    $existingImages = $section
        ? $section->images
            ->map(
                fn($img) => [
                    'image_path' => $img->image_path,
                    'image_url' => asset('storage/' . $img->image_path),
                    'alt_text' => $img->alt_text,
                ],
            )
            ->toArray()
        : [];
    $sectionTypes = [
        'contact' => 'Contact',
        'accommodation' => 'Rooms',
        'services' => 'Services',
        'testimonials' => 'Testimonials',
        'featured_blogs' => 'Featured Blogs',
        'featured_activities' => 'Featured Activities',
        'featured_packages' => 'Featured Packages',
        'gallery' => 'Gallery',
    ];
    $selectedType = old('section_type', $section->section_type ?? ($sectionType === 'new' ? '' : $sectionType));
    $selectedDisplayFields = old('display_fields', $section->display_fields ?? []);

    // Centralized Field Definitions for Dynamic Content
    $allAvailableFields = [
        'accommodation' => [
            'featured_image' => 'Featured Image',
            'room_name' => 'Room Name',
            'headline' => 'Headline',
            'occupancy' => 'Occupancy',
            'room_size' => 'Room Size',
            'price' => 'Price',
            'currency' => 'Currency',
            'excerpt' => 'Excerpt',
            'description' => 'Description',
            'amenities' => 'Amenities',
            'beds' => 'Beds',
            'special_features' => 'Special Features',
            'gallery' => 'Gallery',
        ],
        'services' => [
            'icon' => 'Icon',
            'service_name' => 'Service Name',
            'description' => 'Description',
        ],
        'testimonials' => [
            'name' => 'Name',
            'platform' => 'Platform',
            'testimonial' => 'Testimonial',
        ],
        'featured_blogs' => [
            'featured_image' => 'Featured Image',
            'blog_title' => 'Title',
            'excerpt' => 'Excerpt',
            'category' => 'Category',
            'keywords' => 'Keywords',
            'read_time' => 'Read Time',
            'created_at' => 'Published Date',
        ],
        'featured_activities' => [
            'featured_image' => 'Featured Image',
            'name' => 'Name',
            'excerpt' => 'Excerpt',
            'duration' => 'Duration',
            'difficulty_level' => 'Difficulty Level',
            'best_time' => 'Best Time',
            'category' => 'Category',
        ],
        'featured_packages' => [
            'featured_image' => 'Featured Image',
            'name' => 'Name',
            'duration' => 'Duration',
            'grade' => 'Grade',
            'best_for' => 'Best For',
            'price' => 'Price',
            'currency' => 'Currency',
            'excerpt' => 'Excerpt',
            'category' => 'Category',
            'gallery' => 'Gallery',
        ],
        'gallery' => [
            'name' => 'Category Name',
            'image' => 'Cover Image',
            'images' => 'Gallery Images',
        ],
        'contact' => [
            'phones' => 'Phone Numbers',
            'emails' => 'Email Addresses',
            'address' => 'Address',
            'map_url' => 'Map',
            'business_hours' => 'Business Hours',
        ],
    ];
@endphp

<script>
    function sectionForm() {
        return {
            globalError: '',
            isSubmitting: false,
            selectedType: @json($selectedType),
            displayFields: @json($selectedDisplayFields ?? []),
            contentMeta: @json($contentMeta ?? []),
            rawName: @json($section->section_identifier ?? ($sectionType === 'new' ? '' : ucfirst(str_replace('-', ' ', $sectionType)))),
            getMeta() {
                if (['rooms', 'accommodation', 'stay-with-us'].includes(this.selectedType)) {
                    return this.contentMeta['accommodation'];
                }
                return this.contentMeta[this.selectedType];
            },
            init() {
                console.log('Section Form Initialized');
                console.log('Type:', this.selectedType);
                console.log('Display Fields:', this.displayFields);
            },
            // CTA Buttons
            ctaButtons: @json($existingButtons),
            // Section Images
            slides: @json(array_values($existingImages)).map(function(s) {
                return {
                    previewUrl: s.image_url ?? null,
                    existingPath: s.image_path ?? null,
                    altText: s.alt_text ?? '',
                    file: null,
                    error: '',
                    markedDelete: false,
                };
            }),
            addSlide() {
                if (this.activeSlides().length < 3) {
                    this.slides.push({
                        previewUrl: null,
                        existingPath: null,
                        altText: '',
                        file: null,
                        error: '',
                        markedDelete: false,
                    });
                }
            },
            removeSlide(index) {
                if (this.slides[index].existingPath) {
                    this.slides[index].markedDelete = true;
                } else {
                    this.slides.splice(index, 1);
                }
            },
            activeSlides() {
                return this.slides.filter(s => !s.markedDelete);
            },
            validateImageFile(index, event) {
                const file = event.target.files[0];
                this.slides[index].error = '';
                if (!file) return;
                const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp', 'image/svg+xml'];
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (!validTypes.includes(file.type)) {
                    this.slides[index].error = 'Invalid type. Allowed: PNG, JPG, GIF, WEBP, SVG.';
                    event.target.value = null;
                    return;
                }
                if (file.size > maxSize) {
                    this.slides[index].error = 'File too large. Maximum size is 2 MB.';
                    event.target.value = null;
                    return;
                }
                this.slides[index].file = file;
                this.slides[index].previewUrl = URL.createObjectURL(file);
            },
            addCtaButton() {
                if (this.ctaButtons.length < 2) {
                    this.ctaButtons.push({
                        button_name: '',
                        page_id: ''
                    });
                }
            },
            removeCtaButton(index) {
                this.ctaButtons.splice(index, 1);
            },
            beforeSubmit(event) {
                this.globalError = '';
                this.isSubmitting = true;
            },
            resetSubmission() {
                this.isSubmitting = false;
            }
        }
    }
</script>


<div class="p-0 text-gray-900" x-data="sectionForm()" @submission-finished.window="resetSubmission()">

    <div x-show="isSubmitting" x-cloak
        class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm">
        <div class="flex items-center justify-center w-12 h-12 mb-4 rounded-full bg-indigo-100">
            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </div>
        <p class="text-indigo-700 font-semibold text-base">Saving, please wait…</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <form class="flex flex-col lg:flex-row gap-6 items-start" method="POST" enctype="multipart/form-data"
        action="{{ $action }}" @submit="beforeSubmit($event)" id="commonSectionForm">
        @csrf
        @if ($section)
            @method('PUT')
        @endif

        <!-- Main Content -->
        <div class="flex-1 w-full space-y-6">

            <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Section Configuration</h3>
                </div>

                <div class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Section Identifier (Label)</label>
                        <input type="text" id="section_identifier" name="section_identifier" x-model="rawName" required
                            placeholder="e.g. Stay with us"
                            class="section_identifier w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm">
                        <p class="text-xs text-gray-500">This will be the internal identifier/label for this section.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="section_type_select" class="text-sm font-medium text-gray-700">
                            Include Content Type
                        </label>
                        <select name="section_type" id="section_type_select"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                            x-model="selectedType">
                            <option value="">No dynamic content (Simple Section)</option>
                            @foreach ($sectionTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                            @if($section && !isset($sectionTypes[$section->section_type]))
                                <option value="{{ $section->section_type }}">{{ ucfirst(str_replace('-', ' ', $section->section_type)) }} (Current)</option>
                            @endif
                        </select>
                        <p class="text-[10px] text-gray-400 italic">Select a content type to include dynamic items.</p>
                    </div>

                    {{-- Empty Content Warning --}}
                    <template x-if="selectedType && getMeta() && getMeta().count === 0">
                        <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5">
                                <svg class="size-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-amber-900">No content found!</h4>
                                <p class="text-xs text-amber-700 mt-1">
                                    You have selected <span class="font-bold" x-text="getMeta().label"></span>, but there are no items created yet. Nothing will be displayed on the frontend until you add some.
                                </p>
                                <a :href="getMeta().create_url" target="_blank"
                                    class="inline-flex items-center gap-1.5 mt-3 text-xs font-bold text-amber-800 hover:text-amber-900 transition-colors bg-white px-3 py-1.5 rounded border border-amber-200 shadow-sm">
                                    <svg class="size-3.5" x-show="getMeta().create_url.includes('create')" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <svg class="size-3.5" x-show="!getMeta().create_url.includes('create')" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    <span x-text="getMeta().create_url.includes('create') ? 'Add ' + getMeta().label : 'Manage ' + getMeta().label"></span>
                                </a>
                            </div>
                        </div>
                    </template>

                </div>
            </section>

            {{-- Dynamic Display Fields Section --}}
            <section x-show="selectedType" x-cloak class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-blue-50/50">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="size-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Content Display Options</span>
                    </h3>
                </div>
                <div class="p-6">
                    {{-- ── Rooms/Accommodation ─────────────────────────────────────────── --}}
                    <div x-show="['rooms', 'accommodation', 'stay-with-us'].includes(selectedType)" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-blue-700">All rooms will be included automatically</p>
                            <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full uppercase font-bold">Accommodation</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            @foreach ($allAvailableFields['accommodation'] ?? [] as $field => $label)
                                <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:text-indigo-600 transition-colors"
                                    :class="selectedType && contentMeta['accommodation'] && !contentMeta['accommodation'].field_stats['{{ $field }}'] ? 'opacity-60' : ''">
                                    <input type="checkbox" name="display_fields[]" value="{{ $field }}"
                                        x-model="displayFields"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                    <span class="flex items-center gap-1.5">
                                        {{ $label }}
                                        <template x-if="selectedType && contentMeta['accommodation'] && !contentMeta['accommodation'].field_stats['{{ $field }}']">
                                            <span class="text-[9px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded uppercase font-bold tracking-tighter" title="No data found in any records">Empty</span>
                                        </template>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Services ───────────────────────────────────────────────── --}}
                    <div x-show="selectedType === 'services'" class="space-y-4">
                        <p class="text-xs font-semibold text-blue-700">All services will be included automatically</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            @foreach ($allAvailableFields['services'] ?? [] as $field => $label)
                                <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:text-indigo-600 transition-colors"
                                    :class="selectedType === 'services' && contentMeta['services'] && !contentMeta['services'].field_stats['{{ $field }}'] ? 'opacity-60' : ''">
                                    <input type="checkbox" name="display_fields[]" value="{{ $field }}"
                                        x-model="displayFields"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                    <span class="flex items-center gap-1.5">
                                        {{ $label }}
                                        <template x-if="selectedType === 'services' && contentMeta['services'] && !contentMeta['services'].field_stats['{{ $field }}']">
                                            <span class="text-[9px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded uppercase font-bold tracking-tighter">Empty</span>
                                        </template>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Testimonials ────────────────────────────────────────────── --}}
                    <div x-show="selectedType === 'testimonials'" class="space-y-4">
                        <p class="text-xs font-semibold text-blue-700">All testimonials will be included automatically</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            @foreach ($allAvailableFields['testimonials'] ?? [] as $field => $label)
                                <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:text-indigo-600 transition-colors"
                                    :class="selectedType === 'testimonials' && contentMeta['testimonials'] && !contentMeta['testimonials'].field_stats['{{ $field }}'] ? 'opacity-60' : ''">
                                    <input type="checkbox" name="display_fields[]" value="{{ $field }}"
                                        x-model="displayFields"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                    <span class="flex items-center gap-1.5">
                                        {{ $label }}
                                        <template x-if="selectedType === 'testimonials' && contentMeta['testimonials'] && !contentMeta['testimonials'].field_stats['{{ $field }}']">
                                            <span class="text-[9px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded uppercase font-bold tracking-tighter">Empty</span>
                                        </template>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Featured Blogs ──────────────────────────────────────────── --}}
                    <div x-show="selectedType === 'featured_blogs'" class="space-y-4">
                        <p class="text-xs font-semibold text-blue-700">All featured blogs will be included automatically</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            @foreach ($allAvailableFields['featured_blogs'] ?? [] as $field => $label)
                                <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:text-indigo-600 transition-colors"
                                    :class="selectedType === 'featured_blogs' && contentMeta['featured_blogs'] && !contentMeta['featured_blogs'].field_stats['{{ $field }}'] ? 'opacity-60' : ''">
                                    <input type="checkbox" name="display_fields[]" value="{{ $field }}"
                                        x-model="displayFields"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                    <span class="flex items-center gap-1.5">
                                        {{ $label }}
                                        <template x-if="selectedType === 'featured_blogs' && contentMeta['featured_blogs'] && !contentMeta['featured_blogs'].field_stats['{{ $field }}']">
                                            <span class="text-[9px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded uppercase font-bold tracking-tighter">Empty</span>
                                        </template>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Featured Activities ─────────────────────────────────────── --}}
                    <div x-show="selectedType === 'featured_activities'" class="space-y-4">
                        <p class="text-xs font-semibold text-blue-700">All featured activities will be included automatically</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            @foreach ($allAvailableFields['featured_activities'] ?? [] as $field => $label)
                                <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:text-indigo-600 transition-colors"
                                    :class="selectedType === 'featured_activities' && contentMeta['featured_activities'] && !contentMeta['featured_activities'].field_stats['{{ $field }}'] ? 'opacity-60' : ''">
                                    <input type="checkbox" name="display_fields[]" value="{{ $field }}"
                                        x-model="displayFields"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                    <span class="flex items-center gap-1.5">
                                        {{ $label }}
                                        <template x-if="selectedType === 'featured_activities' && contentMeta['featured_activities'] && !contentMeta['featured_activities'].field_stats['{{ $field }}']">
                                            <span class="text-[9px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded uppercase font-bold tracking-tighter">Empty</span>
                                        </template>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Featured Packages ───────────────────────────────────────── --}}
                    <div x-show="selectedType === 'featured_packages'" class="space-y-4">
                        <p class="text-xs font-semibold text-blue-700">All featured packages will be included automatically</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            @foreach ($allAvailableFields['featured_packages'] ?? [] as $field => $label)
                                <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:text-indigo-600 transition-colors"
                                    :class="selectedType === 'featured_packages' && contentMeta['featured_packages'] && !contentMeta['featured_packages'].field_stats['{{ $field }}'] ? 'opacity-60' : ''">
                                    <input type="checkbox" name="display_fields[]" value="{{ $field }}"
                                        x-model="displayFields"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                    <span class="flex items-center gap-1.5">
                                        {{ $label }}
                                        <template x-if="selectedType === 'featured_packages' && contentMeta['featured_packages'] && !contentMeta['featured_packages'].field_stats['{{ $field }}']">
                                            <span class="text-[9px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded uppercase font-bold tracking-tighter">Empty</span>
                                        </template>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Gallery ─────────────────────────────────────────────────── --}}
                    <div x-show="selectedType === 'gallery'" class="space-y-4">
                        <p class="text-xs font-semibold text-blue-700">All gallery categories will be included automatically</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            @foreach ($allAvailableFields['gallery'] ?? [] as $field => $label)
                                <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:text-indigo-600 transition-colors"
                                    :class="selectedType === 'gallery' && contentMeta['gallery'] && !contentMeta['gallery'].field_stats['{{ $field }}'] ? 'opacity-60' : ''">
                                    <input type="checkbox" name="display_fields[]" value="{{ $field }}"
                                        x-model="displayFields"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                    <span class="flex items-center gap-1.5">
                                        {{ $label }}
                                        <template x-if="selectedType === 'gallery' && contentMeta['gallery'] && !contentMeta['gallery'].field_stats['{{ $field }}']">
                                            <span class="text-[9px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded uppercase font-bold tracking-tighter">Empty</span>
                                        </template>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Contact ──────────────────────────────────────────────────── --}}
                    <div x-show="selectedType === 'contact'" class="space-y-4">
                        <p class="text-xs font-semibold text-blue-700">Contact information will be included automatically</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            @foreach ($allAvailableFields['contact'] ?? [] as $field => $label)
                                <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer hover:text-indigo-600 transition-colors"
                                    :class="selectedType === 'contact' && contentMeta['contact'] && !contentMeta['contact'].field_stats['{{ $field }}'] ? 'opacity-60' : ''">
                                    <input type="checkbox" name="display_fields[]" value="{{ $field }}"
                                        x-model="displayFields"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                    <span class="flex items-center gap-1.5">
                                        {{ $label }}
                                        <template x-if="selectedType === 'contact' && contentMeta['contact'] && !contentMeta['contact'].field_stats['{{ $field }}']">
                                            <span class="text-[9px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded uppercase font-bold tracking-tighter">Empty</span>
                                        </template>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Section Content</h3>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="section_title" class="text-sm font-medium text-gray-700">
                                Section Title <span class="text-red-500" aria-label="required">*</span>
                            </label>
                            <input type="text" id="section_title" name="section_title"
                                value="{{ old('section_title', $section->section_title ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                                aria-describedby="section_title_error">
                            @error('section_title')
                                <p id="section_title_error" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="heading" class="text-sm font-medium text-gray-700">
                                Heading <span class="text-red-500" aria-label="required">*</span>
                            </label>
                            <input type="text" id="heading" name="heading"
                                value="{{ old('heading', $section->heading ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                                aria-describedby="heading_error">
                            @error('heading')
                                <p id="heading_error" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm resize-none"
                            aria-describedby="description_error">{{ old('description', $section->description ?? '') }}</textarea>
                        @error('description')
                            <p id="description_error" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Call-to-Action Buttons</h3>
                    <button type="button" @click="addCtaButton()" x-show="ctaButtons.length < 2"
                        class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Button
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <template x-for="(btn, index) in ctaButtons" :key="index">
                        <div class="p-4 border border-gray-200 rounded-lg space-y-3 relative bg-gray-50/30">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-900">Button <span x-text="index + 1"></span></span>
                                <button type="button" @click="removeCtaButton(index)"
                                    class="text-red-500 hover:text-red-700 text-sm">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-xs font-medium text-gray-700">Button Label</label>
                                    <input type="text" :name="'cta_buttons[' + index + '][button_name]'"
                                        x-model="btn.button_name" placeholder="e.g. Learn More" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 text-sm bg-white">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-medium text-gray-700">Link to Page</label>
                                    <select :name="'cta_buttons[' + index + '][page_id]'" x-model="btn.page_id" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 text-sm bg-white">
                                        <option value="">Select a page...</option>
                                        @foreach ($pages as $p)
                                            <option value="{{ $p->id }}">/{{ $p->slug }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="ctaButtons.length === 0" class="text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-sm text-gray-500">No buttons added yet. Click "Add Button" to include CTAs.</p>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" x-data="{ includeMedia: {{ $existingImages ? 'true' : 'false' }} }">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Section Media</h3>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-medium text-gray-500 uppercase">Enable Media</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="includeMedia" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>

                <div class="p-6" x-show="includeMedia" x-cloak x-transition>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="!slide.markedDelete" class="space-y-3">
                                <div @click="document.getElementById('imgInput_' + index).click()"
                                    class="relative aspect-video border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all flex flex-col items-center justify-center overflow-hidden">
                                    
                                    <div x-show="!slide.previewUrl" class="text-center p-4">
                                        <svg class="size-8 text-gray-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-xs text-gray-500">Upload Image</p>
                                    </div>

                                    <div x-show="slide.previewUrl" class="w-full h-full relative">
                                        <img :src="slide.previewUrl" class="w-full h-full object-cover">
                                        <button @click.stop="removeSlide(index)" type="button"
                                            class="absolute top-1.5 right-1.5 bg-red-600 text-white rounded-full p-1 shadow-md hover:bg-red-700 transition-colors">
                                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <input type="file" accept="image/*" :name="'section_images[' + index + '][image]'" :id="'imgInput_' + index" class="hidden" @change="validateImageFile(index, $event)">
                                </div>
                                <input type="text" :name="'section_images[' + index + '][alt_text]'" x-model="slide.altText" placeholder="Alt text" class="w-full px-3 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-indigo-500">
                                <input type="hidden" :name="'section_images[' + index + '][existing_path]'" :value="slide.existingPath ?? ''">
                                <input type="hidden" :name="'section_images[' + index + '][delete]'" :value="slide.markedDelete ? '1' : ''">
                                <p x-text="slide.error" class="text-[10px] text-red-600 font-medium" x-show="slide.error"></p>
                            </div>
                        </template>

                        <div x-show="activeSlides().length < 3" @click="addSlide()"
                            class="aspect-video border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 transition-all">
                            <svg class="size-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-xs text-gray-500">Add Image</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Sidebar Actions -->
        <aside class="w-full lg:w-80 space-y-6 lg:sticky lg:top-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900">Publish</h3>
                <input type="hidden" name="status" id="statusInput"
                    value="{{ old('status', $section->status ?? 'draft') }}">

                <div class="space-y-4">
                    <button type="submit" onclick="document.getElementById('statusInput').value='published'"
                        class="w-full bg-indigo-600 text-white py-2.5 rounded-md font-semibold hover:bg-indigo-700 transition-colors shadow-sm text-sm">
                        {{ $section ? 'Update & Publish' : 'Publish Section' }}
                    </button>

                    <button type="submit" onclick="document.getElementById('statusInput').value='draft'"
                        class="w-full bg-white border border-gray-300 text-gray-700 py-2.5 rounded-md font-semibold text-center hover:bg-gray-50 transition-colors text-sm">
                        {{ $section ? 'Update as Draft' : 'Save Draft' }}
                    </button>
                </div>

                <a href="{{ route('pages.dashboard', $page->slug) }}"
                    class="block text-center text-xs text-gray-500 hover:text-indigo-600 transition-colors pt-3 border-t border-gray-200">
                    Back to Dashboard
                </a>
            </div>

            @if($section)
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h4 class="text-xs font-bold text-gray-500 uppercase mb-4">Section Info</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Type:</span>
                        <span class="font-medium">{{ $sectionTypes[$section->section_type] ?? $section->section_type }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Last Updated:</span>
                        <span class="font-medium">{{ $section->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            @endif
        </aside>
</form>
</div>
