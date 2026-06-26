@props([
    'hero' => null,
    'commonSections' => [],
])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Homepage') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">Manage Homepage Content </p>
    </x-slot>

    <div class="py-12">
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

                        <div class="flex items-center gap-2">
                            <label for="entriesSelect" class="text-sm text-gray-700">Show</label>
                            <select id="entriesSelect"
                                class="border border-gray-300 w-16 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="text-sm text-gray-700">entries</span>
                        </div>



                        <div class="w-full sm:w-auto">
                            <div class="relative">
                                <input type="text" id="searchInput" placeholder="Search highlights..."
                                    class="w-full sm:w-64 border border-gray-300 rounded-md pl-10 pr-4 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>


                    <div class="overflow-x-auto">
                        <table id="highlightsTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sections</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-gray-400 font-medium">Hero Section</span>
                                        @if (!empty($hero) && !empty($hero->status))
                                            @if ($hero->status === 'published')
                                                <span
                                                    class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs 
                                                    font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                    Published
                                                </span>
                                            @elseif($hero->status === 'draft')
                                                <span
                                                    class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs 
                                                    font-medium text-gray-700 ring-1 ring-inset ring-gray-500/20">
                                                    Draft
                                                </span>
                                            @endif
                                        @endif
                                    </td>


                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('pages.homepage.herosection') }}"
                                            class="text-blue-600 underline hover:text-blue-800 font-medium text-sm">
                                            {{ $hero?->id ? 'Edit' : 'Create' }}
                                        </a>
                                    </td>
                                </tr>
                                @forelse ($commonSections as $section)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-gray-400 font-medium">{{ $section['label'] }}</span>
                                            @if (!empty($section['data']) && !empty($section['data']->status))
                                                @if ($section['data']->status === 'published')
                                                    <span
                                                        class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs 
                                                    font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                        Published
                                                    </span>
                                                @elseif($section['data']->status === 'draft')
                                                    <span
                                                        class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs 
                                                    font-medium text-gray-700 ring-1 ring-inset ring-gray-500/20">
                                                        Draft
                                                    </span>
                                                @endif
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('pages.homepage.section', $section['type']) }}"
                                                class="text-blue-600 underline hover:text-blue-800 font-medium text-sm">
                                                {{ $section['data']?->id ? 'Edit' : 'Create' }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-center text-gray-500">No sections
                                            available</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
