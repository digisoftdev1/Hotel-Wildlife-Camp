@props(['services' => []])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">Welcome back {{ ucfirst(auth()->user()->name) }} to Dashboard </p>
    </x-slot>

    <div class="py-12" x-data="{
        showServiceModal: false,
        showEditModal: false,
        editServiceData: {},
        editServiceRoute: '',
        searchQuery: '',
        entriesPerPage: 10,
        currentPage: 1,
    
        get filteredServices() {
            let services = {{ Js::from($services) }};
    
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                services = services.filter(service =>
                    service.service_name.toLowerCase().includes(query)
                );
            }
    
            return services;
        },
    
        get paginatedServices() {
            const start = (this.currentPage - 1) * this.entriesPerPage;
            const end = start + this.entriesPerPage;
            return this.filteredServices.slice(start, end);
        },
    
        get totalPages() {
            return Math.ceil(this.filteredServices.length / this.entriesPerPage);
        },
    
        get startEntry() {
            if (this.filteredServices.length === 0) return 0;
            return ((this.currentPage - 1) * this.entriesPerPage) + 1;
        },
    
        get endEntry() {
            const end = this.currentPage * this.entriesPerPage;
            return Math.min(end, this.filteredServices.length);
        },
    
        openEditModal(service) {
            this.editServiceData = {
                id: service.id,
                service_name: service.service_name,
                description: service.description,
                icon: service.icon,
                status: service.status
            };
            this.editServiceRoute = `/admin/services/${service.id}`;
            this.showEditModal = true;
    
            this.$nextTick(() => {
                window.dispatchEvent(new CustomEvent('modal-opened'));
            });
        }
    }">
        <div class="mx-72 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                            class="absolute top-24 end-5 transition-all duration-500">
                            <div class="max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg" role="alert"
                                tabindex="-1" aria-labelledby="hs-toast-success-example-label">
                                <div class="flex p-4">
                                    <div class="shrink-0">
                                        <svg class="shrink-0 size-4 text-teal-500 mt-0.5"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ms-3">
                                        <p id="hs-toast-success-example-label" class="text-sm text-black">
                                            {{ session('success') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">

                        <!-- Left side: Entries + Search -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full sm:w-auto">
                            <!-- Entries select -->
                            <div class="flex items-center gap-2">
                                <label for="entriesSelect" class="text-sm text-gray-700">Show</label>
                                <select x-model.number="entriesPerPage" @change="currentPage = 1"
                                    class="border border-gray-300 w-16 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span class="text-sm text-gray-700">entries</span>
                            </div>

                            <!-- Search -->
                            <div class="w-full sm:w-auto">
                                <div class="relative">
                                    <input type="text" x-model="searchQuery" @input="currentPage = 1"
                                        placeholder="Search services..."
                                        class="w-full sm:w-64 border border-gray-300 rounded-md pl-10 pr-4 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Right side: Add Service button -->
                        <div class="ml-auto">
                            <button type="button" @click="showServiceModal = true"
                                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Add Service
                            </button>
                        </div>
                    </div>


                    <!-- Table -->
                    <div class="rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-if="paginatedServices.length > 0">
                                    <template x-for="service in paginatedServices" :key="service.id">
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <i :class="'fa fa-' + service.icon" class="text-xl"></i>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium text-gray-900"
                                                        x-text="service.service_name"></span>

                                                    <span x-show="service.status === 'published'"
                                                        class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 
                                                        text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">
                                                        Published
                                                    </span>

                                                    <span x-show="service.status === 'draft'"
                                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 
                                                        text-xs font-semibold text-gray-700 ring-1 ring-inset ring-gray-500/20">
                                                        Draft
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button type="button" @click="openEditModal(service)"
                                                    class="text-blue-600 underline hover:text-blue-800 font-medium text-sm">
                                                    Edit
                                                </button>

                                                <form :action="'/admin/services/' + service.id" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 underline hover:text-red-800 font-medium text-sm ml-4"
                                                        onclick="return confirm('Are you sure you want to delete this service?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    </template>
                                </template>

                                <template x-if="paginatedServices.length === 0">
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">
                                            No services found
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table info and Pagination -->
                    <div class="mt-4 flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Showing <span x-text="startEntry"></span> to <span x-text="endEntry"></span>
                            of <span x-text="filteredServices.length"></span> entries
                        </div>

                        <!-- Pagination -->
                        <div class="flex gap-2" x-show="totalPages > 1">
                            <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                                class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                Previous
                            </button>

                            <template x-for="page in totalPages" :key="page">
                                <button @click="currentPage = page"
                                    :class="currentPage === page ? 'bg-blue-600 text-white' :
                                        'bg-white text-gray-700 hover:bg-gray-50'"
                                    class="px-3 py-1 text-sm border border-gray-300 rounded-md" x-text="page">
                                </button>
                            </template>

                            <button @click="currentPage = Math.min(totalPages, currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                Next
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <x-services.addservicemodal />
        <x-services.editservicemodal />
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>