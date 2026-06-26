@props(['contact' => null])
<div class="p-6 text-gray-900" x-data="{
    phones: @js(old('phones', $contact?->phones ?? [''])),
    emails: @js(old('emails', $contact?->emails ?? [''])),
    showMapHelp: false,
    init() {

        if (!Array.isArray(this.phones) || this.phones.length === 0) {
            this.phones = [''];
        }
        if (!Array.isArray(this.emails) || this.emails.length === 0) {
            this.emails = [''];
        }
    },
    fileError: '',
}">
    <form class="space-y-6" method="POST" enctype="multipart/form-data"
        action="{{ $contact ? route('contactpage.update', $contact->id) : route('contactpage.store') }}">
        @csrf
        @if ($contact)
            @method('PUT')
        @endif

        <!-- Display validation error for contact_info if exists -->
        @error('contact_info')
            <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                <strong class="font-bold">Error:</strong>
                <span class="block sm:inline">{{ $message }}</span>
            </div>
        @enderror

        <div class="flex flex-col xl:flex-row gap-6">
            <!-- Main Form Content -->
            <div class="flex-1 space-y-6">
                <!-- Page Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Details
                        @if (!empty($contact) && !empty($contact->status))
                            @if ($contact->status === 'published')
                                <span
                                    class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs 
                        font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                    Published
                                </span>
                            @elseif($contact->status === 'draft')
                                <span
                                    class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs 
                        font-medium text-gray-700 ring-1 ring-inset ring-gray-500/20">
                                    Draft
                                </span>
                            @endif
                        @endif
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Phone Numbers -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Phone Numbers</h3>
                                <div class="space-y-3">
                                    <template x-for="(phone, index) in phones" :key="index">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1">
                                                <input type="text" x-model="phones[index]"
                                                    :name="'phones[' + index + ']'" placeholder="e.g., +977 123456789"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                            </div>
                                            <button type="button" @click="phones.splice(index, 1)"
                                                x-show="phones.length > 1" class="text-red-600 hover:text-red-700 p-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button type="button" @click="phones.push('')"
                                        class="text-indigo-600 hover:text-indigo-700 text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add Phone Number
                                    </button>
                                </div>
                                @error('phones')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('phones.*')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Addresses -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Addresses</h3>
                                <div class="space-y-3">
                                    <template x-for="(email, index) in emails" :key="index">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1">
                                                <input type="email" x-model="emails[index]"
                                                    :name="'emails[' + index + ']'" placeholder="e.g., contact@example.com"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                            </div>
                                            <button type="button" @click="emails.splice(index, 1)"
                                                x-show="emails.length > 1" class="text-red-600 hover:text-red-700 p-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button type="button" @click="emails.push('')"
                                        class="text-indigo-600 hover:text-indigo-700 text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add Email Address
                                    </button>
                                </div>
                                @error('emails')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('emails.*')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Address with Map Coordinates -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Location & Address</h3>
                    <div class="space-y-4">
                        <!-- Street Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <input type="text" id="address" name="address"
                                value="{{ old('address', $contact->address ?? '') }}"
                                placeholder="Enter street address..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                required>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Map Coordinates -->
                        <div>
                            <label for="map_url" class="block text-sm font-medium text-gray-700 mb-2">Google Maps
                                URL</label>
                            <input type="url" id="map_url" name="map_url"
                                value="{{ old('map_url', $contact->map_url ?? '') }}"
                                placeholder="https://maps.app.goo.gl/CtJTedBm8vRewjjC8"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('map_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Paste your Google Maps link here.
                        </p>


                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                    <div class="space-y-4">
                        <!-- Business Hours -->
                        <div>
                            <label for="business_hours" class="block text-sm font-medium text-gray-700 mb-2">Business
                                Hours</label>
                            <textarea id="business_hours" name="business_hours" rows="4"
                                placeholder="e.g., Monday - Friday: 9:00 AM - 6:00 PM&#10;Saturday: 10:00 AM - 4:00 PM&#10;Sunday: Closed"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('business_hours', $contact->business_hours ?? '') }}</textarea>
                            @error('business_hours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar Preview & Actions -->
            <div class="w-full xl:w-80 sticky top-20 self-start space-y-6">
                <div class="flex flex-col space-y-6">
                    <!-- Contact Info Preview Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Info Preview</h3>
                        <div class="space-y-4 text-sm">
                            <!-- Phone Preview -->
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    Phone
                                </h4>
                                <div class="space-y-1 text-gray-600">
                                    <template x-for="(phone, index) in phones" :key="index">
                                        <p x-show="phone && phone.trim() !== ''" x-text="phone"></p>
                                    </template>
                                    <p x-show="!phones.some(p => p && p.trim())" class="text-gray-400 italic">
                                        No phones added</p>
                                </div>
                            </div>

                            <!-- Email Preview -->
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Email
                                </h4>
                                <div class="space-y-1 text-gray-600 break-words">
                                    <template x-for="(email, index) in emails" :key="index">
                                        <p x-show="email && email.trim() !== ''" x-text="email"></p>
                                    </template>
                                    <p x-show="!emails.some(e => e && e.trim())" class="text-gray-400 italic">
                                        No emails added</p>
                                </div>
                            </div>

                            <!-- Address Preview -->
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Location
                                </h4>
                                <p class="text-gray-600">View in form fields</p>
                            </div>
                        </div>
                    </div>

                    <!-- Publish Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Publish</h3>
                        <input type="hidden" name="status" id="statusInput" value="draft">
                        <div class="space-y-3">
                            <button type="submit" onclick="document.getElementById('statusInput').value='published'"
                                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                                {{ $contact ? 'Update & Publish' : 'Publish Page' }}
                            </button>
                            <button type="submit" onclick="document.getElementById('statusInput').value='draft'"
                                class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                                {{ $contact ? 'Update as Draft' : 'Save Draft' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
