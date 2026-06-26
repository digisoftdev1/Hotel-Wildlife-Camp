<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Package FAQ Management') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">Manage frequently asked questions for your tour packages</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        showModal: false,
        currentPackage: null,
        faqs: [],
        isLoading: false,
        faqError: null,

        openFaq(pkg) {
            this.currentPackage = pkg;
            this.faqs = pkg.faqs ? pkg.faqs.faqs : [];
            if (this.faqs.length === 0) {
                this.addFaq();
            }
            this.faqError = null;
            this.showModal = true;
        },

        addFaq() {
            this.faqs.push({ question: '', answer: '' });
        },

        removeFaq(index) {
            this.faqs.splice(index, 1);
        },

        async saveFaq() {
            this.isLoading = true;
            this.faqError = null;

            try {
                const response = await fetch(`package-faqs/${this.currentPackage.id}`, {
                    method: 'POST',
                    body: JSON.stringify({ faqs: this.faqs }),
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    const data = await response.json();
                    this.faqError = data.message || 'Failed to save FAQs.';
                    this.isLoading = false;
                }
            } catch (error) {
                this.faqError = 'A network error occurred.';
                this.isLoading = false;
            }
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($packages as $package)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="relative h-48 bg-gray-100">
                            @if($package->featured_image)
                                <img src="{{ Storage::disk('public')->url($package->featured_image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-image text-4xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2 bg-black/50 text-white px-2 py-1 rounded text-xs backdrop-blur-sm">
                                {{ count($package->faqs->faqs ?? []) }} FAQ's
                            </div>
                        </div>
                        <div class="p-4 space-y-3">
                            <h3 class="font-bold text-gray-900 truncate">{{ $package->name }}</h3>
                            <p class="text-xs text-gray-500 truncate">{{ $package->category->name ?? 'No Category' }}</p>
                            
                            <button @click="openFaq({{ json_encode($package) }})" 
                                class="w-full bg-indigo-50 text-indigo-700 py-2 rounded-md text-sm font-semibold hover:bg-indigo-100 transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-circle-question"></i> Manage FAQ's
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-lg border-2 border-dashed border-gray-200">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-400 mb-4">
                            <i class="fa-solid fa-box-open text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">No Packages Found</h3>
                        <p class="text-sm text-gray-500 mt-1">Start by creating your first tour package.</p>
                        <a href="{{ route('packages.create') }}" class="inline-flex items-center gap-2 mt-6 bg-indigo-600 text-white px-6 py-2 rounded-md font-bold hover:bg-indigo-700 transition-colors">
                            <i class="fa-solid fa-plus"></i> Create Package
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- FAQ Modal -->
        <div x-show="showModal" 
            class="fixed inset-0 z-50 overflow-hidden" 
            x-cloak
            @keydown.escape.window="showModal = false"
            style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" 
                    x-cloak
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" 
                    @click="showModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg sm:my-8">
                    
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-gray-900" x-text="currentPackage ? currentPackage.name : ''"></h3>
                            <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-widest">FAQ Manager</p>
                        </div>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form @submit.prevent="saveFaq()">
                        <div class="p-5 space-y-4 max-h-[60vh] overflow-y-auto">
                            <template x-for="(faq, index) in faqs" :key="index">
                                <div class="p-4 bg-gray-50 rounded border border-gray-200 space-y-3 relative group">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest" x-text="'Question ' + (index + 1)"></span>
                                        <button type="button" @click="removeFaq(index)" class="text-gray-400 hover:text-red-500 transition-colors" x-show="faqs.length > 1">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                    <input type="text" x-model="faq.question" placeholder="Enter question..." 
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <textarea x-model="faq.answer" placeholder="Enter answer..." rows="2"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                                </div>
                            </template>

                            <button type="button" @click="addFaq()" class="w-full py-3 border-2 border-dashed border-gray-200 rounded text-gray-400 hover:border-indigo-300 hover:text-indigo-500 transition-all flex items-center justify-center gap-2 text-sm font-semibold">
                                <i class="fa-solid fa-plus text-xs"></i> Add New Question
                            </button>

                            <div x-show="faqError" class="py-2">
                                <p class="text-[10px] font-bold text-red-500 bg-red-50 px-3 py-1.5 rounded-sm border border-red-100" x-text="faqError"></p>
                            </div>
                        </div>

                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                            <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                :disabled="isLoading"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md text-xs font-bold hover:bg-indigo-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                <span x-show="!isLoading">Save FAQ's</span>
                                <span x-show="isLoading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Saving...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
