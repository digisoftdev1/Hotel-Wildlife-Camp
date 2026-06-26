@props([
    'page' => null,
    'hero' => null,
    'pages' => null,
])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $page->name }} — Hero Section
        </h2>
        <p class="mt-1 text-sm text-gray-500">Manage hero section for {{ $page->name }}</p>
    </x-slot>

    <div class="py-12">
        <div class="mx-72 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <x-hero-section-form 
                    :page="$page" 
                    :hero="$hero" 
                    :pages="$pages"
                    :action="$hero 
                        ? route('pages.herosection.update', [$page->slug, $hero->id]) 
                        : route('pages.herosection.store', $page->slug)"
                />
            </div>
        </div>
    </div>
</x-app-layout>
