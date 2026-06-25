@props(['contact' => null])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contacts') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Manage contact details displayed on the website') }}
        </p>
    </x-slot>

    <div class="py-12 lg:ml-64">
        <div class=" mx-auto sm:px-6 lg:px-8">


            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                    class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg" role="alert">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="float-right font-bold">×</button>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                    class="mb-4 p-4 text-sm text-red-800 bg-red-100 rounded-lg" role="alert">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="float-right font-bold">×</button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <x-contact.contactform :contact="$contact" />



            </div>

        </div>
    </div>



</x-app-layout>
