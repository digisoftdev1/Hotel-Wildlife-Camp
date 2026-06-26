<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Gallery') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Upload, update, and organize room gallery images.') }}
        </p>
    </x-slot>

    <div x-data="galleryModal()" x-cloak class="py-12">
        <div class="mx-72 sm:px-6 lg:px-8">

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-800 bg-red-100 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">Available Rooms</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($rooms as $room)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">

                            <img src="{{ Storage::disk('public')->url($room->featured_image) }}" class="w-full h-48 object-cover">

                            <div class="p-4">
                                <h3 class="text-lg font-semibold mb-2">
                                    {{ $room->room_name }}
                                </h3>

                                <p class="text-sm text-gray-600 mb-4">
                                    {{ $room->gallery ? count($room->gallery->photos) . ' photo(s)' : 'No gallery photos' }}
                                </p>

                                @if ($room->gallery)
                                    <button
                                        @click="openEditModal(
                                            {{ $room->id }},
                                            '{{ $room->room_name }}',
                                            {{ json_encode($room->gallery->photos) }},
                                        )"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                        Edit Gallery
                                    </button>
                                @else
                                    <button @click="openAddModal({{ $room->id }}, '{{ $room->room_name }}')"
                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                                        Add Photos
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ================= ADD MODAL ================= -->
        <x-roomgallery.addgalleryfrommodal />

        <!-- ================= EDIT MODAL ================= -->
        <x-roomgallery.editgalleryfrommodal />
    </div>

    <!-- ================= ALPINE ================= -->
    <script>
        function galleryModal() {
            return {
                baseUrl: "{{ rtrim(Storage::disk('public')->url(''), '/') }}",
                updateRoute: "{{ url('/roomgallery') }}",
                showAddModal: false,
                showEditModal: false,
                selectedRoomId: null,
                selectedRoomName: '',
                existingPhotos: [],
                imagePreviews: [],
                imageFiles: [],
                imageError: '',

                hasImages() {
                    return this.imageFiles.length > 0 || this.existingPhotos.length > 0;
                },

                openAddModal(id, name) {
                    this.selectedRoomId = id;
                    this.selectedRoomName = name;
                    this.imagePreviews = [];
                    this.imageFiles = [];
                    this.imageError = '';
                    this.showAddModal = true;
                    document.body.style.overflow = 'hidden';
                },

                closeAddModal() {
                    this.showAddModal = false;
                    this.imagePreviews = [];
                    this.imageFiles = [];
                    this.imageError = '';
                    document.body.style.overflow = 'auto';
                },

                openEditModal(id, name, photos) {
                    this.selectedRoomId = id;
                    this.selectedRoomName = name;
                    this.existingPhotos = [...photos];
                    this.imagePreviews = [];
                    this.imageFiles = [];
                    this.imageError = '';
                    this.showEditModal = true;
                    document.body.style.overflow = 'hidden';
                },

                closeEditModal() {
                    this.showEditModal = false;
                    this.imagePreviews = [];
                    this.imageFiles = [];
                    this.imageError = '';
                    document.body.style.overflow = 'auto';
                },

                previewEditImages(event) {
                    this.imagePreviews = [];
                    this.imageFiles = [];
                    this.imageError = '';

                    const files = Array.from(event.target.files);
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/svg+xml'];
                    const maxSize = 2 * 1024 * 1024;

                    for (let file of files) {
                        if (!allowedTypes.includes(file.type)) {
                            this.imageError = 'Only JPG, PNG, and SVG images are allowed.';
                            event.target.value = '';
                            return;
                        }

                        if (file.size > maxSize) {
                            this.imageError = 'Each image must be 2 MB or smaller.';
                            event.target.value = '';
                            return;
                        }

                        this.imageFiles.push(file);

                        const reader = new FileReader();
                        reader.onload = e => this.imagePreviews.push(e.target.result);
                        reader.readAsDataURL(file);
                    }
                },

                removeNewImage(index) {
                    this.imagePreviews.splice(index, 1);
                    this.imageFiles.splice(index, 1);
                },

                removeExistingImage(index) {
                    this.existingPhotos.splice(index, 1);
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>
