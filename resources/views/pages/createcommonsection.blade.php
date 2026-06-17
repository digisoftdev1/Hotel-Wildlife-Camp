@props([
    'page'         => null,
    'section'      => null,
    'sectionType'  => '',
    'sectionLabel' => 'Section',
])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $page->name }} — {{ $sectionLabel }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">Manage {{ $sectionLabel }} content</p>
    </x-slot>

    <div class="py-10">
        <div class="mx-72 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <x-common-section-form
                    :page="$page"
                    :section="$section"
                    :sectionType="$sectionType"
                    :sectionLabel="$sectionLabel"
                    :pages="$pages"
                    :contentMeta="$contentMeta"
                    :action="$section 
                        ? route('pages.section.update', [$page->slug, $section->slug]) 
                        : route('pages.section.store', [$page->slug, $sectionType])"
                />
            </div>
        </div>
    </div>
</x-app-layout>
