@props(['activity' => null, 'featuredCount' => 0, 'categories' => []])
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('experience-activities.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ isset($activity) ? __('Edit Experience/Activity') : __('Add New Experience/Activity') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500"> 
                    {{ isset($activity) ? 'Update details for ' . $activity->name : 'Create a new experience or activity' }} 
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <x-add-exp-activity-form :activity="$activity" :featuredCount="$featuredCount" :categories="$categories" />
        </div>
    </div>
</x-app-layout>
