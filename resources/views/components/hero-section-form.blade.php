@props([
    'page' => null,
    'hero' => null,
    'pages' => null,
    'action' => ''
])

@php
    $existingSlides = $hero && $hero->media_type === 'images'
        ? $hero->sliderImages
            ->map(fn($img) => [
                'id' => $img->id,
                'image_path' => $img->image_path,
                'image_url' => Storage::disk('public')->url($img->image_path),
                'section_title' => $img->section_title,
                'heading' => $img->heading,
                'description' => $img->description,
            ])
            ->toArray()
        : [];

    $existingButtons = $hero ? $hero->ctaButtons->toArray() : [];
@endphp

<div class="p-6 text-gray-900" x-data="{
    mediaType: {{ Js::from(old('media_type', $hero->media_type ?? 'images')) }},
    sectionTitle: {{ Js::from(old('section_title', $hero->section_title ?? '')) }},
    heading: {{ Js::from(old('heading', $hero->heading ?? '')) }},
    description: {{ Js::from(old('description', $hero->description ?? '')) }},
    
    // Video
    videoFile: null,
    videoPreviewUrl: {{ Js::from($hero && $hero->video_path ? Storage::disk('public')->url($hero->video_path) : '') }},
    videoError: '',

    // Images
    slides: {{ Js::from(array_values($existingSlides)) }}.map(function(s) {
        return {
            id: s.id ?? null,
            previewUrl: s.image_url ?? null,
            existingPath: s.image_path ?? null,
            sectionTitle: s.section_title ?? '',
            heading: s.heading ?? '',
            description: s.description ?? '',
            file: null,
            error: '',
            markedDelete: false,
        };
    }),

    // CTA Buttons
    ctaButtons: {{ Js::from($existingButtons) }},

    globalError: '',
    isSubmitting: false,

    init() {
        // No auto-init for slides or CTA buttons based on user request
    },

    addSlide() {
        this.slides.push({
            id: null,
            previewUrl: null,
            existingPath: null,
            sectionTitle: '',
            heading: '',
            description: '',
            file: null,
            error: '',
            markedDelete: false,
        });
    },

    removeSlide(index) {
        if (this.slides[index].existingPath) {
            this.slides[index].markedDelete = true;
        } else {
            this.slides.splice(index, 1);
        }
        this.globalError = '';
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

    validateVideoFile(event) {
        const file = event.target.files[0];
        this.videoError = '';
        if (!file) return;

        const validTypes = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];
        const maxSize = 50 * 1024 * 1024; // 50MB

        if (!validTypes.includes(file.type)) {
            this.videoError = 'Invalid type. Allowed: MP4, WebM, OGG, MOV.';
            event.target.value = null;
            return;
        }
        if (file.size > maxSize) {
            this.videoError = 'File too large. Maximum size is 50 MB.';
            event.target.value = null;
            return;
        }

        this.videoFile = file;
        this.videoPreviewUrl = URL.createObjectURL(file);
    },

    validateImageFile(index, event) {
        const file = event.target.files[0];
        this.slides[index].error = '';
        if (!file) return;

        const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp', 'image/svg+xml'];
        const maxSize = 2 * 1024 * 1024;

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

    activeSlides() {
        return this.slides.filter(s => !s.markedDelete);
    },

    beforeSubmit(event) {
        this.globalError = '';

        if (this.mediaType === 'images') {
            const active = this.activeSlides();
            if (active.length === 0) {
                event.preventDefault();
                this.globalError = 'At least one slider image is required.';
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            for (let i = 0; i < this.slides.length; i++) {
                const s = this.slides[i];
                if (s.markedDelete) continue;
                if (!s.previewUrl && !s.existingPath) {
                    event.preventDefault();
                    this.globalError = 'Please upload an image for every slide.';
                    window.scrollTo({ top: 300, behavior: 'smooth' });
                    return;
                }
                if (s.error) {
                    event.preventDefault();
                    this.globalError = 'Please fix image errors before submitting.';
                    return;
                }
            }
        } else if (this.mediaType === 'video') {
            if (!this.videoPreviewUrl) {
                event.preventDefault();
                this.globalError = 'Please upload a video.';
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }
            if (this.videoError) {
                event.preventDefault();
                this.globalError = 'Please fix video errors before submitting.';
                return;
            }
        }

        this.isSubmitting = true;
    }
}">

    <div x-show="isSubmitting" x-cloak
        class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm">
        <svg class="animate-spin h-12 w-12 text-indigo-600 mb-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
        <p class="text-indigo-700 font-semibold text-base">Saving, please wait…</p>
        <p class="text-gray-400 text-sm mt-1">Uploading media may take a moment.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-lg bg-green-50 border border-green-200 px-4 py-3">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <form class="space-y-8" method="POST" enctype="multipart/form-data" @submit="beforeSubmit($event)"
        action="{{ $action }}">
        @csrf
        @if ($hero)
            @method('PUT')
        @endif

        <div class="flex flex-col xl:flex-row gap-8">
            <div class="flex-1 space-y-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Hero Section
                    </h3>
                    @if (!empty($hero) && !empty($hero->status))
                        @if ($hero->status === 'published')
                            <span
                                class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                Published
                            </span>
                        @else
                            <span
                                class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/20">
                                Draft
                            </span>
                        @endif
                    @endif
                </div>

                <div x-show="globalError" x-cloak>
                    <p x-text="globalError"
                        class="text-sm text-red-600 font-medium bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    </p>
                </div>
                @error('hero_video')
                    <p class="text-sm text-red-600 font-medium bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                        {{ $message }}</p>
                @enderror
                @error('slider_images')
                    <p class="text-sm text-red-600 font-medium bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                        {{ $message }}</p>
                @enderror

                {{-- Basic Information --}}
                <div x-show="mediaType === 'video'" class="bg-gray-50 p-5 rounded-xl border border-gray-200 space-y-4">
                    <h4 class="text-sm font-semibold text-gray-800">Basic Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Section Title</label>
                            <input type="text" name="section_title" x-model="sectionTitle"
                                placeholder="e.g. Welcome"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                            @error('section_title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Heading</label>
                            <input type="text" name="heading" x-model="heading"
                                placeholder="e.g. Empowering Local Government"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                            @error('heading')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Description</label>
                            <textarea name="description" x-model="description" rows="3"
                                placeholder="Brief description..."
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white"></textarea>
                            @error('description')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- CTA Buttons --}}
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-gray-800">CTA Buttons</h4>
                        <span class="text-xs text-gray-500">Max 2 buttons</span>
                    </div>

                    <template x-for="(btn, index) in ctaButtons" :key="index">
                        <div class="flex items-center gap-4 bg-white p-3 rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Button Name <span class="text-red-500">*</span></label>
                                <input type="text" :name="'cta_buttons[' + index + '][button_name]'" x-model="btn.button_name"
                                    placeholder="e.g. Learn More" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Link (Page) <span class="text-red-500">*</span></label>
                                <select :name="'cta_buttons[' + index + '][page_id]'" x-model="btn.page_id" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Select a page...</option>
                                    @foreach($pages as $p)
                                        <option value="{{ $p->id }}">/{{ $p->slug }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pt-5">
                                <button type="button" @click="removeCtaButton(index)"
                                    class="text-red-500 hover:text-red-700 p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addCtaButton()" x-show="ctaButtons.length < 2"
                        class="text-sm text-indigo-600 font-medium hover:text-indigo-800">
                        + Add Button
                    </button>
                </div>

                {{-- Media Type Selection --}}
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800">Media Type</h3>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="media_type" value="images" x-model="mediaType" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Images Carousel</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="media_type" value="video" x-model="mediaType" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Single Video</span>
                        </label>
                    </div>
                </div>

                {{-- Video Upload --}}
                <div x-show="mediaType === 'video'" x-cloak class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-800">
                        Background Video <span class="text-red-500">*</span>
                        <span class="ml-1 text-xs font-normal text-gray-400">(MP4, WebM, OGG, MOV — max 50 MB)</span>
                    </label>

                    <div @click="$refs.videoInput.click()"
                        class="relative border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-indigo-400 transition-colors overflow-hidden"
                        :class="videoError ? 'border-red-400' : ''">
                        
                        <div x-show="!videoPreviewUrl" class="flex flex-col items-center justify-center py-12 gap-2">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-gray-500">Click to upload video</p>
                        </div>

                        <div x-show="videoPreviewUrl" class="relative group">
                            <video :key="videoPreviewUrl" :src="videoPreviewUrl" class="w-full max-h-64 object-cover rounded-lg" controls playsinline></video>
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity bg-black/50 text-white px-3 py-1 rounded text-sm pointer-events-none">
                                Click to change video
                            </div>
                        </div>

                        <input type="file" accept="video/mp4,video/webm,video/ogg,video/quicktime" name="hero_video" x-ref="videoInput"
                            class="hidden" @change="validateVideoFile($event)">
                    </div>
                    <p x-show="videoError" x-text="videoError" class="mt-1.5 text-xs text-red-600"></p>
                </div>

                {{-- Images Carousel Upload --}}
                <div x-show="mediaType === 'images'" x-cloak class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-800">
                        Slider Images <span class="text-red-500">*</span>
                        <span class="ml-1 text-xs font-normal text-gray-400">(at least 1 required)</span>
                    </label>

                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="!slide.markedDelete"
                            class="border border-gray-200 rounded-xl bg-gray-50 p-5 space-y-4 transition-all">
                            <input type="hidden" :name="'slider_images[' + index + '][existing_path]'" :value="slide.existingPath ?? ''">
                            <input type="hidden" :name="'slider_images[' + index + '][delete]'" :value="slide.markedDelete ? '1' : ''">

                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-600" x-text="'Slide ' + (index + 1)"></span>
                                <button type="button" @click="removeSlide(index)"
                                    class="flex items-center gap-1 text-xs text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Remove
                                </button>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                    Image <span class="text-red-500">*</span>
                                    <span class="text-gray-400">(PNG, JPG, GIF, WEBP, SVG — max 2 MB)</span>
                                </label>

                                <div @click="$refs['imgInput_' + index] ? $refs['imgInput_' + index].click() : $el.querySelector('input[type=file]').click()"
                                    class="relative border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-indigo-400 transition-colors overflow-hidden"
                                    :class="slide.error ? 'border-red-400' : ''">
                                    
                                    <div x-show="!slide.previewUrl" class="flex flex-col items-center justify-center py-8 gap-2">
                                        <svg class="w-10 h-10 text-gray-300" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="text-sm text-gray-500">Click to upload image</p>
                                    </div>

                                    <div x-show="slide.previewUrl" class="relative group">
                                        <img :src="slide.previewUrl" class="w-full max-h-52 object-cover rounded-lg" alt="Preview">
                                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                                            <p class="text-white text-sm font-medium">Click to change image</p>
                                        </div>
                                    </div>

                                    <input type="file" accept="image/*" :name="'slider_images[' + index + '][image]'"
                                        class="hidden" @change="validateImageFile(index, $event)">
                                </div>
                                <p x-show="slide.error" x-text="slide.error" class="mt-1.5 text-xs text-red-600"></p>
                            </div>

                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Section Title</label>
                                        <input type="text" :name="'slider_images[' + index + '][section_title]'"
                                            x-model="slide.sectionTitle" placeholder="Slide section title..."
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Heading</label>
                                        <input type="text" :name="'slider_images[' + index + '][heading]'"
                                            x-model="slide.heading" placeholder="Slide heading..."
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Description</label>
                                        <textarea :name="'slider_images[' + index + '][description]'"
                                            x-model="slide.description" rows="2" placeholder="Slide description..."
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addSlide()"
                        class="flex items-center gap-2 w-full justify-center py-3 px-4 border-2 border-dashed border-indigo-300 rounded-xl text-indigo-600 text-sm font-medium hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Slide
                    </button>
                </div>
            </div>

            <div class="w-full xl:w-72 sticky top-20 self-start space-y-5">
                {{-- Live Preview --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Live Preview</h3>
                    <div class="relative rounded-lg overflow-hidden bg-gradient-to-br from-indigo-500 to-purple-600 h-44">
                        
                        {{-- Video Preview --}}
                        <template x-if="mediaType === 'video'">
                            <div class="absolute inset-0">
                                <template x-if="videoPreviewUrl">
                                    <video :key="videoPreviewUrl" x-bind:src="videoPreviewUrl" class="w-full h-full object-cover opacity-60" x-show="videoPreviewUrl" autoplay muted loop controls playsinline></video>
                                </template>
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 bg-black/40">
                                    <p x-show="sectionTitle" x-text="sectionTitle" class="text-white/80 text-xs uppercase tracking-wider font-semibold mb-1"></p>
                                    <p x-show="heading" x-text="heading" class="text-white font-bold text-lg leading-snug drop-shadow-lg mb-1"></p>
                                    <p x-show="description" x-text="description" class="text-white/90 text-xs leading-snug drop-shadow-md line-clamp-2"></p>
                                </div>
                            </div>
                        </template>

                        {{-- Images Preview --}}
                        <template x-if="mediaType === 'images'">
                            <div class="absolute inset-0">
                                <template x-if="activeSlides().length > 0">
                                    <div class="absolute inset-0">
                                        <template x-if="activeSlides()[0].previewUrl">
                                            <div class="absolute inset-0 bg-cover bg-center" :style="'background-image: url(' + activeSlides()[0].previewUrl + ')'"></div>
                                        </template>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 bg-black/40">
                                            <p x-show="activeSlides()[0].sectionTitle" x-text="activeSlides()[0].sectionTitle" class="text-white/80 text-xs uppercase tracking-wider font-semibold mb-1"></p>
                                            <p x-show="activeSlides()[0].heading" x-text="activeSlides()[0].heading" class="text-white font-bold text-lg leading-snug drop-shadow-lg mb-1"></p>
                                            <p x-show="activeSlides()[0].description" x-text="activeSlides()[0].description" class="text-white/90 text-xs leading-snug drop-shadow-md line-clamp-2"></p>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="activeSlides().length === 0">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    <p x-show="mediaType === 'images' && activeSlides().length > 0" class="mt-2 text-center text-xs text-gray-400">
                        Showing first active slide
                    </p>
                </div>

                <div class="bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-3 flex items-center justify-between" x-show="mediaType === 'images'">
                    <span class="text-sm text-indigo-700 font-medium">Total slides</span>
                    <span x-text="activeSlides().length" class="bg-indigo-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center"></span>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Publish</h3>
                    <input type="hidden" name="status" id="statusInput" value="{{ old('status', $hero->status ?? 'draft') }}">
                    <div class="space-y-2.5">
                        <button type="submit" :disabled="isSubmitting" onclick="document.getElementById('statusInput').value='published'"
                            class="relative w-full bg-indigo-600 text-white py-2.5 px-4 rounded-lg hover:bg-indigo-700 active:scale-95 transition-all font-medium text-sm disabled:opacity-60 disabled:cursor-not-allowed disabled:active:scale-100">
                            <span x-show="!isSubmitting">{{ $hero ? 'Update & Publish' : 'Publish Page' }}</span>
                            <span x-show="isSubmitting" x-cloak class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving…
                            </span>
                        </button>
                        <button type="submit" :disabled="isSubmitting" onclick="document.getElementById('statusInput').value='draft'"
                            class="relative w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-gray-200 active:scale-95 transition-all font-medium text-sm disabled:opacity-60 disabled:cursor-not-allowed disabled:active:scale-100">
                            <span x-show="!isSubmitting">{{ $hero ? 'Update as Draft' : 'Save Draft' }}</span>
                            <span x-show="isSubmitting" x-cloak class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving…
                            </span>
                        </button>
                        <a href="{{ route('pages.dashboard', $page->slug) }}" class="block text-center text-xs text-gray-500 hover:text-indigo-600 mt-4 transition-colors">
                            &larr; Back to Page Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
