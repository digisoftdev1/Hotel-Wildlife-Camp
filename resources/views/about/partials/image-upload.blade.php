@props([
    'field',
    'preview' => null,
])

@php
    $label = ucwords(str_replace('_', ' ', $field));
@endphp

<div
    x-data="{
        image: {{ $preview ? "'" . Storage::disk('public')->url($preview) . "'" : 'null' }},
        error: '',

        validate(event) {
            const file = event.target.files[0];
            this.error = '';

            if (!file) return;

            const validTypes = [
                'image/png',
                'image/jpeg',
                'image/jpg',
                'image/gif',
                'image/webp',
                'image/svg+xml'
            ];

            const maxSize = 2 * 1024 * 1024;

            if (!validTypes.includes(file.type)) {
                this.error = 'Invalid file type! Allowed: PNG, JPG, GIF, WEBP, SVG.';
                event.target.value = null;
                this.image = null;
                return;
            }

            if (file.size > maxSize) {
                this.error = 'File is too large! Maximum size is 2MB.';
                event.target.value = null;
                this.image = null;
                return;
            }

            this.image = URL.createObjectURL(file);
        }
    }"
    class="space-y-3"
>

    {{-- LABEL --}}
    <span class="block text-sm font-bold text-gray-700">
        {{ $label }}
    </span>

    {{-- UPLOAD BOX --}}
    <div
        @click="$refs.input.click()"
        class="group relative border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center cursor-pointer hover:border-indigo-400 hover:bg-gray-50 transition-all"
    >

        {{-- EMPTY STATE --}}
        <div x-show="!image" class="space-y-2">
            <p class="text-sm font-bold text-gray-700">
                Click to upload or drag and drop
            </p>
            <p class="text-xs text-gray-400 uppercase tracking-wider">
                PNG, JPG, GIF, WEBP up to 2MB
            </p>
        </div>

        {{-- PREVIEW --}}
        <div x-show="image" class="relative">
            <img :src="image"
                 class="max-h-64 mx-auto rounded-xl shadow-sm object-cover">

            <button
                type="button"
                @click.stop="image=null; $refs.input.value=null; error=''"
                class="absolute top-2 right-2 bg-white text-rose-500 rounded-full w-7 h-7 flex items-center justify-center shadow hover:bg-rose-500 hover:text-white"
            >
                ×
            </button>
        </div>

        {{-- INPUT --}}
        <input
            type="file"
            name="{{ $field }}"
            x-ref="input"
            class="hidden"
            accept="image/*"
            @change="validate($event)"
        >
    </div>

    {{-- ERROR --}}
    <p x-show="error" x-text="error" class="text-sm text-rose-500 font-medium"></p>

    @error($field)
        <p class="text-sm text-rose-500 font-medium">{{ $message }}</p>
    @enderror

</div>