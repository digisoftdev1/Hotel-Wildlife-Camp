<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('packages.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ isset($package) ? __('Edit Package') : __('Add New Package') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500"> 
                    {{ isset($package) ? 'Update details for ' . $package->name : 'Create a new tour or activity package' }} 
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <x-package-form :package="$package ?? null" :categories="$categories" :currencies="$currencies" />
        </div>
    </div>
</x-app-layout>
