<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Image Category') }}
        </h2>
    </x-slot>
    <div x-data="categoryModal()" class="py-12">
        <div class="mx-72 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <x-add-gallery-category />
                <x-gallery-category :categories="$categories" />
            </div>
        </div>
    </div>



</x-app-layout>
