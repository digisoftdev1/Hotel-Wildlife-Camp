<!-- Modal -->
<div id="testimonialModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="w-full max-w-2xl overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl">
        <div class="flex items-start justify-between gap-4 border-b border-gray-200 bg-gray-50 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Add New Testimonial</h3>
                <p class="mt-1 text-sm text-gray-500">Capture a testimonial from your preferred source platform.</p>
            </div>
            <button type="button" onclick="document.getElementById('testimonialModal').classList.add('hidden')"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full text-gray-400 transition hover:bg-white hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div x-data="testimonialForm()" class="max-h-[85vh] overflow-y-auto px-6 py-6 text-gray-900">
            <form action="{{ route('testimonials.store') }}" method="POST" @submit="validateForm($event)"
                class="space-y-6">
                @csrf

                <!-- Platform Selection -->
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700">
                        Source Platform <span class="text-red-600">*</span>
                    </label>

                    <input type="hidden" name="platform" x-model="formData.platform">

                    <div class="grid grid-cols-4 gap-3 sm:grid-cols-7">
                        <template x-for="platform in platforms" :key="platform.id">
                            <button type="button" @click="selectPlatform(platform.id)"
                                class="flex flex-col items-center justify-center gap-1 rounded-xl border p-3 text-center transition duration-200 hover:-translate-y-0.5"
                                :class="formData.platform === platform.id ?
                                    'border-blue-500 bg-blue-600 text-white shadow-lg shadow-blue-500/20' :
                                    'border-gray-200 bg-gray-50 text-gray-600 hover:border-gray-300 hover:bg-gray-100'">
                                <div x-html="platform.icon"></div>
                                <span class="text-xs font-medium" x-text="platform.name"></span>
                            </button>
                        </template>
                    </div>

                    <p x-show="errors.platform" x-text="errors.platform" class="text-sm text-red-600" x-cloak></p>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Guest Name <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="name" x-model="formData.name" @input="clearError('name')"
                            placeholder="Enter guest's full name"
                            class="w-full rounded-xl border bg-gray-50 px-4 py-3 transition focus:outline-none"
                            :class="errors.name ? 'border-red-500 ring-2 ring-red-200' :
                                'border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200'">
                        <p x-show="errors.name" x-text="errors.name" class="text-sm text-red-600" x-cloak></p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Testimonial Text <span class="text-red-600">*</span>
                        </label>
                        <textarea rows="4" name="testimonial" x-model="formData.testimonial" @input="clearError('testimonial')"
                            class="w-full resize-none rounded-xl border bg-gray-50 px-4 py-3 transition focus:outline-none"
                            :class="errors.testimonial ? 'border-red-500 ring-2 ring-red-200' :
                                'border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200'"></textarea>
                        <p x-show="errors.testimonial" x-text="errors.testimonial" class="text-sm text-red-600" x-cloak>
                        </p>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="flex h-12 w-full items-center justify-center rounded-xl bg-blue-600 font-semibold text-white transition hover:bg-blue-700">
                    Save Testimonial
                </button>
            </form>

        </div>
    </div>
</div>
