<div x-show="showServiceModal" x-cloak @click.self="showServiceModal = false"
    class="fixed top-0 left-0 z-50 w-full h-full overflow-x-hidden overflow-y-auto bg-black/60 flex items-center justify-center p-3"
    role="dialog" tabindex="-1">

    <div x-show="showServiceModal" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" @click.stop
        class="w-full max-w-lg bg-white border border-gray-200 shadow-2xl rounded-xl">

        <!-- Header -->
        <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-800">
                Add Service
            </h3>
            <button type="button" @click="showServiceModal = false"
                class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none"
                aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-4 overflow-y-auto max-h-[calc(100vh-200px)]">
            <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4"
                x-data="{
                    selectedIcon: '',
                    showIconDropdown: false,
                    serviceName: '',
                    errors: {
                        icon: '',
                        serviceName: ''
                    },
                                       icons: [
                        { name: 'wifi', label: 'WiFi' },
                        { name: 'desktop', label: 'Projector' },
                        { name: 'microphone', label: 'Microphone' },
                        { name: 'volume-up', label: 'Speaker' }, 
                        { name: 'tv', label: 'Monitor/TV' },
                        { name: 'video', label: 'Video Camera' },
                        { name: 'camera', label: 'Webcam' },
                        { name: 'users', label: 'Users' },
                        { name: 'briefcase', label: 'Briefcase' },
                        { name: 'calendar', label: 'Calendar' },
                        { name: 'coffee', label: 'Coffee/Break' },
                        { name: 'utensils', label: 'Food/Catering' },
                        { name: 'car', label: 'Parking/Car' },
                        { name: 'plane', label: 'Travel' },
                        { name: 'cog', label: 'Settings' },
                        { name: 'shield-alt', label: 'Security' },
                        { name: 'wheelchair', label: 'Accessibility' },
                        { name: 'print', label: 'Printer' },
                        { name: 'phone', label: 'Phone' },
                        { name: 'envelope', label: 'Email/Mail' },
                        { name: 'globe', label: 'Internet/Globe' },
                        { name: 'building', label: 'Building/Office' },
                        { name: 'home', label: 'Home' },
                        { name: 'laptop', label: 'Laptop' },
                        { name: 'tablet-alt', label: 'Tablet' },
                        { name: 'mobile-alt', label: 'Mobile' },
                        { name: 'database', label: 'Database' },
                        { name: 'cloud', label: 'Cloud' },
                        { name: 'lock', label: 'Lock/Security' },
                        { name: 'unlock', label: 'Unlock/Access' },
                        { name: 'clock', label: 'Clock' },
                        { name: 'stethoscope', label: 'Stethoscope' },
                        { name: 'compass', label: 'Compass' },
                        { name: 'tshirt', label: 'Shirt' },
                        { name: 'chalkboard-teacher', label: 'Presentation' },
                    ],
                    selectIcon(icon) {
                        this.selectedIcon = icon;
                        this.showIconDropdown = false;
                        this.errors.icon = '';
                    },
                    validateForm(e) {
                        this.errors = { icon: '', serviceName: '' };
                        let hasError = false;
                
                        if (!this.selectedIcon) {
                            this.errors.icon = 'Please select an icon';
                            hasError = true;
                        }
                
                        if (!this.serviceName.trim()) {
                            this.errors.serviceName = 'Service name is required';
                            hasError = true;
                        }
                
                        if (hasError) {
                            e.preventDefault();
                        }
                    }
                }" @submit="validateForm($event)">
                @csrf
                <!-- Icon Selector -->
                <div class="relative" @click.away="showIconDropdown = false">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Icon <span class="text-red-500">*</span>
                    </label>
                    <button type="button" @click="showIconDropdown = !showIconDropdown"
                        class="w-full px-4 py-2 text-left border rounded-lg focus:ring-2 focus:ring-blue-500 bg-white flex items-center justify-between"
                        :class="errors.icon ? 'border-red-500' : 'border-gray-300'">

                        <span x-show="!selectedIcon" class="text-gray-400">
                            Select an icon...
                        </span>

                        <template x-if="selectedIcon">
                            <span class="flex items-center gap-2">
                                <i :class="'fa fa-' + selectedIcon" class="text-lg"></i>
                                <span x-text="icons.find(i => i.name === selectedIcon)?.label"></span>
                            </span>
                        </template>

                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <input type="hidden" name="icon" x-model="selectedIcon">

                    <!-- Validation Error -->
                    <p x-show="errors.icon" x-text="errors.icon" class="mt-1 text-sm text-red-600"></p>

                    <!-- Dropdown -->
                    <div x-show="showIconDropdown" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <div class="grid grid-cols-2 gap-1 p-2">
                            <template x-for="icon in icons" :key="icon.name">
                                <button type="button" @click="selectIcon(icon.name)"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-left hover:bg-gray-100 rounded-md transition-colors"
                                    :class="selectedIcon === icon.name ? 'bg-blue-50 text-blue-600' : 'text-gray-700'">
                                    <i :class="'fa fa-' + icon.name" class="text-lg w-5"></i>
                                    <span x-text="icon.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    @error('icon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="service_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Service Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="service_name" name="service_name" placeholder="Enter service name..."
                        x-model="serviceName" @input="errors.serviceName = ''"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        :class="errors.serviceName ? 'border-red-500' : 'border-gray-300'">

                    <!-- Validation Error -->
                    <p x-show="errors.serviceName" x-text="errors.serviceName" class="mt-1 text-sm text-red-600"></p>

                    @error('service_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4" placeholder="Enter service description..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Footer -->
                <div class="flex justify-end items-center gap-x-2 pt-4 border-t border-gray-200">
                    <button type="button" @click="showServiceModal = false"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                        Close
                    </button>
                    <button type="submit"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Save Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
