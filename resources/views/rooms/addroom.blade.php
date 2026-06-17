@props(['amenity' => null, 'room' => null])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Management') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">Add rooms </p>
    </x-slot>

    <div class="py-12">
        <div class="mx-72 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Success Alert -->
                    @if (session('success'))
                        <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 border border-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Alert -->
                    @if (session('error'))
                        <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 border border-red-300">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 border border-red-300">
                            <strong>There were some problems with your input:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <form method="POST" action="{{ $room ? route('rooms.update', $room) : route('rooms.store') }}"
                        x-data="propertyFeatures()" x-init="$nextTick(() => lucide.createIcons())" enctype="multipart/form-data">
                        @csrf
                        @if ($room)
                            @method('PUT')
                        @endif
                        <div x-data="{
                            featuredImg: {{ $room && $room->featured_image ? "'" . Storage::disk('public')->url($room->featured_image) . "'" : 'null' }},
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
                            }
                        }">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Room Details
                                @if (!empty($room) && !empty($room->status))
                                    @if ($room->status === 'published')
                                        <span
                                            class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs 
                        font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            Published
                                        </span>
                                    @elseif($room->status === 'draft')
                                        <span
                                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs 
                        font-medium text-gray-700 ring-1 ring-inset ring-gray-500/20">
                                            Draft
                                        </span>
                                    @endif
                                @endif
                            </h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Featured Image <span class="text-red-500">*</span>
                                </label>
                                <div @click="$refs.featuredImg.click()"
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6  text-center hover:border-indigo-400 transition-colors cursor-pointer">
                                    <div x-show="!featuredImg">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Click to upload or drag and
                                            drop</p>
                                        <p class="text-xs text-gray-400">PNG, JPG, GIF, WEBP, SVG up to 2MB
                                        </p>
                                    </div>
                                    <div x-show="featuredImg" class="relative">
                                        <img :src="featuredImg" alt="Background"
                                            class="max-h-48 mx-auto rounded-lg">
                                        <button
                                            @click.stop="
                                            featuredImg = null; 
                                            $refs.featuredImg.value = null;
                                            fileError = '';
                                        "
                                            type="button"
                                            class="absolute -top-2 -right-2 bg-red-100 text-red-600 rounded-full p-1 hover:bg-red-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="file" accept="image/*" x-ref="featuredImg" name="featured_image"
                                        class="hidden" @change="validateFile($event)">
                                </div>
                                <p x-text="fileError" class="mt-1 text-sm text-red-600" x-show="fileError">
                                </p>
                                @error('featured_image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Room Name -->
                            <div>
                                <label for="room_name" class="block text-sm font-medium text-gray-700 mt-4 mb-2">
                                    Room Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="room_name" name="room_name"
                                    value="{{ old('room_name', $room->room_name ?? '') }}"
                                    placeholder="Enter room name..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    required>
                                @error('room_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- <div>
                                <label for="tagline" class="block text-sm font-medium text-gray-700 mt-4 mb-2">
                                    Tagline <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="tagline" name="tagline"
                                    value="{{ old('tagline', $room->tagline ?? '') }}" placeholder="Enter tagline..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    required>
                                @error('tagline')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div> --}}
                        </div>
                        <!-- Room Type + Occupancy + Price (Same Row, 3 Equal Columns) -->
                        <div class="md:col-span-2 mt-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                <!-- Headline -->
                                <div>
                                    <label for="headline" class="block text-sm font-medium text-gray-700 mb-2">
                                        Headline <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="headline" name="headline"
                                        value="{{ old('headline', $room->headline ?? '') }}"
                                        placeholder="Enter room headline..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                                    @error('headline')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Occupancy -->
                                <div>
                                    <label for="occupancy" class="block text-sm font-medium text-gray-700 mb-2">
                                        Occupancy <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="occupancy" name="occupancy" placeholder="eg: 2 guests ..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        required value="{{ old('occupancy', $room->occupancy ?? '') }}">
                                    @error('occupancy')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Price -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                        Price <span class="text-red-500">*</span>
                                    </label>

                                    <div class="flex rounded-lg shadow-sm">
                                        <!-- Currency -->
                                        <select name="currency_id"
                                            class="rounded-l-lg border w-20 border-gray-300 bg-gray-50 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            @foreach ($currencies as $currency)
                                                <option value="{{ $currency->id }}"
                                                    {{ old('currency_id', $room->currency_id ?? '') == $currency->id ? 'selected' : '' }}>
                                                    {{ $currency->sign }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- Price -->
                                        <input type="number" name="price" id="price" step="0.01"
                                            min="0" placeholder="123"
                                            class="w-34 px-4 py-2 border border-l-0 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            required value="{{ old('price', $room->price ?? '') }}">

                                        <!-- Unit -->
                                        <span
                                            class="inline-flex items-center px-4 text-sm text-gray-600 bg-gray-50 border border-l-0 border-gray-300 rounded-r-lg">
                                            / night
                                        </span>
                                    </div>

                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>


                            </div>
                            <div>
                                <label for="room_size" class="block text-sm font-medium text-gray-700 mt-3 mb-2">
                                    Room Size <span class="text-red-500">*</span>
                                </label>

                                <div class="flex rounded-lg shadow-sm">


                                    <input type="number" name="room_size" id="room_size" step="0.01"
                                        min="0" placeholder="123"
                                        class="w-34 px-4 py-2 border  border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        required value="{{ old('room_size', $room->room_size ?? '') }}">


                                    <span
                                        class="inline-flex items-center px-4 text-sm text-gray-600 bg-gray-50 border border-l-0 border-gray-300 rounded-r-lg">
                                        Sq.ft.
                                    </span>
                                </div>

                                @error('room_size')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 mt-4 mb-2">
                                Excerpt <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="excerpt" name="excerpt"
                                value="{{ old('excerpt', $room->excerpt ?? '') }}"
                                placeholder="Enter short description..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                required>
                            @error('excerpt')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mt-4 mb-2">Description <span
                                class="text-red-500">*</span></h3>
                        <textarea name="description" rows="10" placeholder="Write your room description here..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            required>{{ old('description', $room->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror


    


                        <!-- Amenities Section -->
                        <div class="mb-8 mt-4">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700">Amenities</h3>

                            <!-- Hidden inputs for form submission -->
                            <template x-for="(amenity, index) in amenities" :key="amenity.id">
                                <div>
                                    <input type="hidden" :name="'amenities[' + index + '][name]'"
                                        :value="amenity.name">
                                    <input type="hidden" :name="'amenities[' + index + '][icon]'"
                                        :value="amenity.icon">
                                </div>
                            </template>

                            <!-- Display Amenities -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <template x-for="amenity in amenities" :key="amenity.id">
                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 text-gray-700 rounded-md text-sm">
                                        <i :data-lucide="getIconName(amenity.icon)" class="w-4 h-4 text-green-600"></i>
                                        <span x-text="amenity.name"></span>
                                        <button type="button" @click="removeAmenity(amenity.id)"
                                            class="ml-1 text-gray-500 hover:text-red-600 transition-colors">
                                            <i data-lucide="x" class="w-3 h-3"></i>
                                        </button>
                                    </span>
                                </template>
                            </div>

                            <!-- Add Amenity Form -->
                            <div x-show="isAddingAmenity" x-transition
                                class="p-4 border border-gray-200 rounded-lg bg-gray-50 space-y-4 mb-4">
                                <input type="text" x-model="newAmenityName" @keydown.enter.prevent="addAmenity()"
                                    placeholder="Amenity name (e.g., Free WiFi)"
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />

                                <div>
                                    <p class="text-sm text-gray-600 mb-2">Select an icon:</p>
                                    <div class="grid grid-cols-10 gap-2">
                                        <template x-for="icon in availableIcons" :key="icon.name">
                                            <button type="button" @click="selectedAmenityIcon = icon.name"
                                                :class="selectedAmenityIcon === icon.name ?
                                                    'border-green-600 bg-green-50 text-green-600' :
                                                    'border-gray-200 hover:border-green-400 hover:bg-gray-100'"
                                                class="p-2 rounded-lg border transition-all duration-200">
                                                <i :data-lucide="icon.icon" class="w-5 h-5 mx-auto"></i>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <x-primary-button type="button" @click="addAmenity()">
                                        Add Amenity
                                    </x-primary-button>
                                    <x-secondary-button type="button" @click="isAddingAmenity = false">
                                        Cancel
                                    </x-secondary-button>
                                </div>
                            </div>

                            <x-secondary-button x-show="!isAddingAmenity" type="button"
                                @click="isAddingAmenity = true" class="border-dashed">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                Add Amenity
                            </x-secondary-button>
                        </div>

                        <hr class="my-8">

                        <!-- Bed Types Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700">Bed Configuration</h3>

                            <!-- Hidden inputs for form submission -->
                            <template x-for="(bed, index) in beds" :key="bed.id">
                                <div>
                                    <input type="hidden" :name="'beds[' + index + '][type]'" :value="bed.type">
                                    <input type="hidden" :name="'beds[' + index + '][quantity]'"
                                        :value="bed.quantity">
                                </div>

                            </template>

                            <!-- Display Beds -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <template x-for="bed in beds" :key="bed.id">
                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 text-gray-700 rounded-md text-sm">
                                        <i data-lucide="bed" class="w-4 h-4 text-green-600"></i>
                                        <span x-text="bed.quantity + ' × ' + getBedLabel(bed.type)"></span>
                                        <button type="button" @click="removeBed(bed.id)"
                                            class="ml-1 text-gray-500 hover:text-red-600 transition-colors">
                                            <i data-lucide="x" class="w-3 h-3"></i>
                                        </button>
                                    </span>
                                </template>
                            </div>

                            <!-- Add Bed Form -->
                            <div x-show="isAddingBed" x-transition
                                class="p-4 border border-gray-200 rounded-lg bg-gray-50 mb-4">
                                <div class="flex flex-wrap gap-3 items-end">
                                    <div class="flex-1 min-w-[140px]">
                                        <label class="block text-sm text-gray-600 mb-2">Bed Type</label>
                                        <select x-model="selectedBedType"
                                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            <option value="">Select bed type</option>
                                            <template x-for="bedType in bedTypes" :key="bedType.value">
                                                <option :value="bedType.value" x-text="bedType.label"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <div class="w-24">
                                        <label class="block text-sm text-gray-600 mb-2">Quantity</label>
                                        <input type="number" x-model="selectedBedQuantity"
                                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    </div>

                                    <div class="flex gap-2">
                                        <x-primary-button type="button" @click="addBed()"
                                            x-bind:disabled="!selectedBedType">
                                            Add
                                        </x-primary-button>
                                        <x-secondary-button type="button" @click="isAddingBed = false">
                                            Cancel
                                        </x-secondary-button>
                                    </div>
                                </div>
                            </div>

                            <x-secondary-button x-show="!isAddingBed" type="button" @click="isAddingBed = true"
                                class="border-dashed">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                Add Bed
                            </x-secondary-button>
                        </div>

                        <hr class="my-8">

                        <!-- Special Features Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700">Special Features</h3>

                            <!-- Hidden inputs for form submission -->
                            <template x-for="(feature, index) in specialFeatures" :key="index">
                                <input type="hidden" :name="'special_features[]'" :value="feature">
                            </template>

                            <!-- Display Features -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <template x-for="(feature, index) in specialFeatures" :key="index">
                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-2 bg-green-50 text-green-700 rounded-md text-sm">
                                        <i data-lucide="star" class="w-3 h-3 fill-current text-green-600"></i>
                                        <span x-text="feature"></span>
                                        <button type="button" @click="removeFeature(feature)"
                                            class="ml-1 text-green-600 hover:text-red-600 transition-colors">
                                            <i data-lucide="x" class="w-3 h-3"></i>
                                        </button>
                                    </span>
                                </template>
                            </div>

                            <!-- Add Feature Form -->
                            <div class="flex gap-2">
                                <input type="text" x-model="newFeature" @keydown.enter.prevent="addFeature()"
                                    placeholder="Add a special feature (e.g., Ocean View)"
                                    class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                <x-secondary-button type="button" @click="addFeature()">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </x-secondary-button>
                            </div>
                        </div>


                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Publish</h3>
                        <input type="hidden" name="status" id="statusInput" value="draft">

                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Publish Button -->
                            <button type="submit" onclick="document.getElementById('statusInput').value='published'"
                                class="flex-1 bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-center">
                                {{ $room ? 'Update & Publish' : 'Publish Page' }}
                            </button>

                            <!-- Draft Button -->
                            <button type="submit" onclick="document.getElementById('statusInput').value='draft'"
                                class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors font-medium text-center">
                                {{ $room ? 'Update as Draft' : 'Save Draft' }}
                            </button>
                        </div>



                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function propertyFeatures() {
                return {
                    // Use old() values first, then fallback to existing data
                    amenities: @json(old('amenities', $roomAmenities ?? [])),
                    isAddingAmenity: false,
                    newAmenityName: '',
                    selectedAmenityIcon: 'check',

                    beds: @json(old('beds', $roomBeds ?? [])),
                    isAddingBed: false,
                    selectedBedType: '',
                    selectedBedQuantity: '1',

                    specialFeatures: @json(old('special_features', $roomSpecialFeatures ?? [])),
                    newFeature: '',

                    availableIcons: [{
                            name: 'check',
                            icon: 'check'
                        },
                        {
                            name: 'wifi',
                            icon: 'wifi'
                        },
                        {
                            name: 'tv',
                            icon: 'tv'
                        },
                        {
                            name: 'air-conditioning',
                            icon: 'wind'
                        },
                        {
                            name: 'coffee',
                            icon: 'coffee'
                        },
                        {
                            name: 'bath',
                            icon: 'bath'
                        },
                        {
                            name: 'refrigerator',
                            icon: 'refrigerator'
                        },
                        {
                            name: 'restaurant',
                            icon: 'utensils'
                        },
                        {
                            name: 'parking',
                            icon: 'car'
                        },
                        {
                            name: 'gym',
                            icon: 'dumbbell'
                        },
                        {
                            name: 'pool',
                            icon: 'waves'
                        },
                        {
                            name: 'fireplace',
                            icon: 'flame'
                        },
                        {
                            name: 'phone',
                            icon: 'phone'
                        },
                        {
                            name: 'locker',
                            icon: 'lock'
                        },
                        {
                            name: 'laundry',
                            icon: 'shirt'
                        },
                        {
                            name: 'mountain-view',
                            icon: 'mountain'
                        },
                        {
                            name: 'premium',
                            icon: 'sparkles'
                        },
                        {
                            name: 'baby-friendly',
                            icon: 'baby'
                        },
                        {
                            name: 'accessible',
                            icon: 'accessibility'
                        },
                        {
                            name: 'non-smoking',
                            icon: 'cigarette-off'
                        },
                        {
                            name: 'pet-friendly',
                            icon: 'dog'
                        },
                    ],

                    bedTypes: [{
                            value: 'single',
                            label: 'Single Bed'
                        },
                        {
                            value: 'double',
                            label: 'Double Bed'
                        },
                        {
                            value: 'queen',
                            label: 'Queen Bed'
                        },
                        {
                            value: 'king',
                            label: 'King Bed'
                        },
                        {
                            value: 'twin',
                            label: 'Twin Bed'
                        },
                    ],

                    init() {
                        // Ensure amenities have proper structure
                        if (this.amenities && Array.isArray(this.amenities)) {
                            this.amenities = this.amenities.map((amenity, index) => {
                                // Check if amenity already has an id
                                if (amenity.id) {
                                    return amenity;
                                } else if (amenity.name && amenity.icon) {
                                    // If amenity has name and icon but no id, add one
                                    return {
                                        id: 'temp-' + Date.now() + '-' + index,
                                        name: amenity.name,
                                        icon: amenity.icon
                                    };
                                }
                                return amenity;
                            });
                        } else {
                            this.amenities = [];
                        }

                        // Ensure beds have proper structure
                        if (this.beds && Array.isArray(this.beds)) {
                            this.beds = this.beds.map((bed, index) => {
                                // Check if bed already has an id
                                if (bed.id) {
                                    return bed;
                                } else if (bed.type && bed.quantity) {
                                    // If bed has type and quantity but no id, add one
                                    return {
                                        id: 'temp-' + Date.now() + '-' + index,
                                        type: bed.type,
                                        quantity: parseInt(bed.quantity)
                                    };
                                }
                                return bed;
                            });
                        } else {
                            this.beds = [];
                        }

                        // Ensure special features is an array
                        if (!Array.isArray(this.specialFeatures)) {
                            this.specialFeatures = [];
                        }

                        // Initialize icons after data is loaded
                        this.$nextTick(() => lucide.createIcons());
                    },

                    getIconName(iconName) {
                        const icon = this.availableIcons.find(i => i.name === iconName);
                        return icon ? icon.icon : this.availableIcons[0].icon;
                    },

                    addAmenity() {
                        if (this.newAmenityName.trim()) {
                            this.amenities.push({
                                id: 'new-' + Date.now(),
                                name: this.newAmenityName.trim(),
                                icon: this.selectedAmenityIcon
                            });
                            this.newAmenityName = '';
                            this.selectedAmenityIcon = 'check';
                            this.isAddingAmenity = false;
                            this.$nextTick(() => lucide.createIcons());
                        }
                    },

                    removeAmenity(id) {
                        this.amenities = this.amenities.filter(a => a.id !== id);
                    },

                    getBedLabel(type) {
                        const bedType = this.bedTypes.find(b => b.value === type);
                        return bedType ? bedType.label : type;
                    },

                    addBed() {
                        if (!this.selectedBedType) return;

                        this.beds.push({
                            id: 'new-' + Date.now(),
                            type: this.selectedBedType,
                            quantity: parseInt(this.selectedBedQuantity)
                        });

                        this.selectedBedType = '';
                        this.selectedBedQuantity = '1';
                        this.isAddingBed = false;
                        this.$nextTick(() => lucide.createIcons());
                    },

                    removeBed(id) {
                        this.beds = this.beds.filter(b => b.id !== id);
                    },

                    addFeature() {
                        if (this.newFeature.trim() && !this.specialFeatures.includes(this.newFeature.trim())) {
                            this.specialFeatures.push(this.newFeature.trim());
                            this.newFeature = '';
                            this.$nextTick(() => lucide.createIcons());
                        }
                    },

                    removeFeature(feature) {
                        this.specialFeatures = this.specialFeatures.filter(f => f !== feature);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
