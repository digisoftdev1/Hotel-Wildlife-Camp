<!-- Modal -->
<div id="editTestimonialModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="w-full max-w-2xl overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl">
        <div class="flex items-start justify-between gap-4 border-b border-gray-200 bg-gray-50 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Edit Testimonial</h3>
                <p class="mt-1 text-sm text-gray-500">Update the testimonial content or source platform.</p>
            </div>
            <button type="button" onclick="closeEditModal()"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full text-gray-400 transition hover:bg-white hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div x-data="editTestimonialForm()" class="max-h-[85vh] overflow-y-auto px-6 py-6 text-gray-900">
            <form :action="formAction" method="POST" @submit="validateForm($event)" class="space-y-6">
                @csrf
                @method('PUT')

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
                    Update Testimonial
                </button>
            </form>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.testimonialUpdateRoute = @json(route('testimonials.update', '__ID__'));
    </script>
    <script>
        function editTestimonialForm() {
            return {
                formData: {
                    name: '',
                    platform: '',
                    testimonial: ''
                },
                formAction: '',
                errors: {},

                platforms: [{
                        id: 'tiktok',
                        name: 'TikTok',
                        icon: `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.75 2h2.25c.3 2.1 1.8 3.6 3.9 3.9v2.2c-1.6.1-3.1-.4-4.3-1.3v7.2a6 6 0 11-6-6c.3 0 .6 0 .9.1v2.3a3.7 3.7 0 10 2.3 3.5V2z"/></svg>`
                    },
                    {
                        id: 'facebook',
                        name: 'Facebook',
                        icon: `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22.675 0h-21.35C.6 0 0 .6 0 1.326v21.348C0 23.4.6 24 1.326 24h11.495v-9.294H9.691V11.41h3.13V8.797c0-3.1 1.894-4.788 4.66-4.788 1.325 0 2.463.098 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.31h3.587l-.467 3.296h-3.12V24h6.116C23.4 24 24 23.4 24 22.674V1.326C24 .6 23.4 0 22.675 0z"/></svg>`
                    },
                    {
                        id: 'instagram',
                        name: 'Instagram',
                        icon: `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2C4.24 2 2 4.24 2 7v10c0 2.76 2.24 5 5 5h10c2.76 0 5-2.24 5-5V7c0-2.76-2.24-5-5-5H7zm10 2c1.66 0 3 1.34 3 3v10c0 1.66-1.34 3-3 3H7c-1.66 0-3-1.34-3-3V7c0-1.66 1.34-3 3-3h10zm-5 3.5A4.5 4.5 0 1016.5 12 4.51 4.51 0 0012 7.5zm0 7.3A2.8 2.8 0 1114.8 12 2.8 2.8 0 0112 14.8zM17.75 6.25a1.05 1.05 0 11-1.05-1.05 1.05 1.05 0 011.05 1.05z"/></svg>`
                    },
                    {
                        id: 'linkedin',
                        name: 'LinkedIn',
                        icon: `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5A2.48 2.48 0 102.5 6a2.48 2.48 0 002.48-2.5zM2 8.98h5.96V24H2zM14.5 8.75c-1.6 0-2.6.88-3.04 1.7h-.04V8.98H5.5V24h5.96v-7.5c0-2 .38-3.94 2.85-3.94s2.47 2.3 2.47 4.06V24H22v-8.5c0-4.18-.9-7.75-7.5-7.75z"/></svg>`
                    },
                    {
                        id: 'youtube',
                        name: 'YouTube',
                        icon: `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2s-.2-1.7-.8-2.4c-.8-.9-1.7-.9-2.1-1C16.8 2.5 12 2.5 12 2.5h-.1s-4.8 0-8.6.3c-.4.1-1.3.1-2.1 1C.7 4.5.5 6.2.5 6.2S0 8.1 0 10v2c0 1.9.5 3.8.5 3.8s.2 1.7.8 2.4c.8.9 1.9.9 2.4 1 1.7.2 7.3.3 7.3.3s4.8 0 8.6-.3c.4-.1 1.3-.1 2.1-1 .6-.7.8-2.4.8-2.4s.5-1.9.5-3.8v-2c0-1.9-.5-3.8-.5-3.8zM9.75 14.02V7.98L15.5 11l-5.75 3.02z"/></svg>`
                    },
                    {
                        id: 'google',
                        name: 'Google',
                        icon: `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M21.6 12.23c0-.7-.06-1.37-.17-2.03H12v3.85h5.4a4.63 4.63 0 01-2 3.04v2.52h3.23c1.89-1.74 2.97-4.31 2.97-7.38z"/><path d="M12 22c2.7 0 4.97-.9 6.63-2.44l-3.23-2.52c-.9.6-2.05.96-3.4.96-2.62 0-4.84-1.77-5.63-4.16H3.02v2.62A10 10 0 0012 22z"/><path d="M6.37 13.84A6 6 0 016.05 12c0-.64.11-1.27.32-1.84V7.54H3.02A10 10 0 002 12c0 1.6.38 3.12 1.02 4.46l3.35-2.62z"/><path d="M12 6c1.47 0 2.79.5 3.82 1.49l2.86-2.86C16.96 2.9 14.69 2 12 2a10 10 0 00-8.98 5.54l3.35 2.62C7.16 7.77 9.38 6 12 6z"/></svg>`
                    },
                    {
                        id: 'other',
                        name: 'Other',
                        icon: `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h.01M12 12h.01M16 12h.01"/></svg>`
                    }
                ],

                validateForm(event) {
                    this.errors = {};
                    if (!this.formData.name) this.errors.name = 'Guest name is required';
                    if (!this.formData.platform) this.errors.platform = 'Platform is required';
                    if (!this.formData.testimonial) this.errors.testimonial = 'Testimonial is required';

                    if (Object.keys(this.errors).length > 0) {
                        event.preventDefault();
                        return false;
                    }
                    return true;
                },

                clearError(field) {
                    delete this.errors[field];
                },

                selectPlatform(id) {
                    this.formData.platform = id;
                    this.clearError('platform');
                },


            }
        }

        function openEditModal(testimonial) {
            // Get the Alpine data from the element
            const el = document.querySelector('[x-data^="editTestimonialForm"]');
            if (!el) {
                console.error('Edit modal element not found');
                return;
            }

            const data = window.Alpine ? window.Alpine.$data(el) : el._x_dataStack[0];

            if (data) {
                data.formData = {
                    name: testimonial.name,
                    platform: testimonial.platform,
                    testimonial: testimonial.testimonial
                };

                data.formAction = window.testimonialUpdateRoute.replace('__ID__', testimonial.id);
                data.errors = {};

                document.getElementById('editTestimonialModal').classList.remove('hidden');
            }
        }

        function closeEditModal() {
            document.getElementById('editTestimonialModal').classList.add('hidden');
        }
    </script>
@endpush
