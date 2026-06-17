@props(['about' => null, 'categories' => null, 'featuredCount' => 0])

<div class="p-6 text-gray-900"
x-data="{
    breadcrumbImg: {{ $about && $about->breadcrumb_image ? "'" . Storage::disk('public')->url($about->breadcrumb_image) . "'" : 'null' }},
    aboutImg: {{ $about && $about->about_image ? "'" . Storage::disk('public')->url($about->about_image) . "'" : 'null' }},
    teamImg: {{ $about && $about->team_image ? "'" . Storage::disk('public')->url($about->team_image) . "'" : 'null' }},

    fileError: '',

    validateFile(event, target) {
        const file = event.target.files[0];
        this.fileError = '';

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
            this.fileError = 'Invalid file type! Allowed: PNG, JPG, GIF, WEBP, SVG.';
            event.target.value = null;
            this[target] = null;
            return;
        }

        if (file.size > maxSize) {
            this.fileError = 'File is too large! Maximum size is 2MB.';
            event.target.value = null;
            this[target] = null;
            return;
        }

        this[target] = URL.createObjectURL(file);
    },

    init() {}
}">
<form id="aboutForm" class="space-y-8" method="POST" enctype="multipart/form-data"
    action="{{ $about ? route('abouts.update', $about->id) : route('abouts.store') }}">

    @csrf
    @if ($about)
        @method('PUT')
    @endif

    <div class="flex flex-col xl:flex-row gap-8">

        {{-- LEFT SIDE --}}
        <div class="flex-1 space-y-8">

            {{-- BREADCRUMB --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">

                <h3 class="text-xl font-bold text-gray-900 mb-6">Breadcrumb Section</h3>

                <div class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <x-input-label value="Breadcrumb Title" class="mb-2 font-bold text-gray-700" />
                            <input type="text" name="breadcrumb_title"
                                value="{{ old('breadcrumb_title', $about->breadcrumb_title ?? '') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                        </div>

                        <div>
                            <x-input-label value="Breadcrumb Description" class="mb-2 font-bold text-gray-700" />
                            <input type="text" name="breadcrumb_description"
                                value="{{ old('breadcrumb_description', $about->breadcrumb_description ?? '') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                        </div>

                    </div>

                    @include('about.partials.image-upload', [
                        'field' => 'breadcrumb_image',
                        'preview' => $about->breadcrumb_image ?? null
                    ])

                </div>
            </div>


            {{-- ABOUT --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">

                <h3 class="text-xl font-bold text-gray-900 mb-6">About Section</h3>

                <div class="space-y-6">

                    <div>
                        <x-input-label value="About Title" class="mb-2 font-bold text-gray-700" />
                        <input type="text" name="about_title"
                            value="{{ old('about_title', $about->about_title ?? '') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
                        <x-input-label value="About Description" class="mb-2 font-bold text-gray-700" />
                        <input type="hidden" name="about_description"
                            id="about_description_hidden"
                            value="{{ old('about_description', $about->about_description ?? '') }}">

                        <textarea id="about_description_editor"></textarea>
                    </div>

                </div>

                @include('about.partials.image-upload', [
                    'field' => 'about_image',
                    'preview' => $about->about_image ?? null
                ])

            </div>


            {{-- GRID: ESTABLISHED + LOCATION --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

                {{-- ESTABLISHED --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">

                    <h3 class="text-xl font-bold text-gray-900 mb-6">Established</h3>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label value="Year" class="mb-2 font-bold text-gray-700" />
                            <input type="text" name="established_year"
                                value="{{ old('established_year', $about->established_year ?? '') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                        </div>

                        <div>
                            <x-input-label value="Description" class="mb-2 font-bold text-gray-700" />
                            <input type="text" name="established_description"
                                value="{{ old('established_description', $about->established_description ?? '') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                        </div>

                    </div>
                </div>
                </div>


                {{-- LOCATION --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">

                    <h3 class="text-xl font-bold text-gray-900 mb-6">Location</h3>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label value="Location" class="mb-2 font-bold text-gray-700" />
                            <input type="text" name="location"
                                value="{{ old('location', $about->location ?? '') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                        </div>

                        <div>
                            <x-input-label value="Description" class="mb-2 font-bold text-gray-700" />
                            <input type="text" name="location_description"
                                value="{{ old('location_description', $about->location_description ?? '') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                        </div>

                    </div>
                    </div>
                </div>

            </div>


            {{-- TEAM --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">

                <h3 class="text-xl font-bold text-gray-900 mb-6">Team</h3>

                <div class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <x-input-label value="Title" class="mb-2 font-bold text-gray-700" />
                            <input type="text" name="team_title"
                                value="{{ old('team_title', $about->team_title ?? '') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                        </div>

                        <div>
                            <x-input-label value="Description" class="mb-2 font-bold text-gray-700" />
                            <input type="text" name="team_description"
                                value="{{ old('team_description', $about->team_description ?? '') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                        </div>

                    </div>

                    @include('about.partials.image-upload', [
                        'field' => 'team_image',
                        'preview' => $about->team_image ?? null
                    ])

                </div>
            </div>


            {{-- FACILITIES --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">

                <h3 class="text-xl font-bold text-gray-900 mb-6">Facilities</h3>

                <div class="space-y-6">

                    <div>
                        <x-input-label value="Title" class="mb-2 font-bold text-gray-700" />
                        <input type="text" name="facilities_title"
                            value="{{ old('facilities_title', $about->facilities_title ?? '') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
                        <x-input-label value="Facilities" class="mb-2 font-bold text-gray-700" />
                        <select name="facilities[]" multiple class="w-full js-example-tokenizer">
                            @if($about && $about->facilities)
                                @foreach($about->facilities as $item)
                                    <option selected>{{ $item }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                </div>
            </div>

        </div>


        {{-- RIGHT SIDE --}}
        <div class="w-full xl:w-96 sticky top-8 self-start space-y-8">

            {{-- PUBLISH --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">

                <h3 class="text-lg font-bold mb-6">Publishing</h3>

                <input type="hidden" name="status" id="statusInput"
                    value="{{ old('status', $about->status ?? 'draft') }}">

                <div class="space-y-4">
                    <button type="button" onclick="setStatus('published')"
                        class="w-full bg-indigo-600 text-white py-3 rounded-xl">
                        Publish
                    </button>

                    <button type="button" onclick="setStatus('draft')"
                        class="w-full border py-3 rounded-xl">
                        Save Draft
                    </button>
                </div>

            </div>

            {{-- PREVIEW --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-all hover:shadow-md">

                <h3 class="text-lg font-bold mb-6">Preview</h3>

                <div class="aspect-video bg-gray-50 rounded-xl overflow-hidden">
                    @if($about && $about->breadcrumb_image)
                        <img src="{{ Storage::url($about->breadcrumb_image) }}"
                            class="w-full h-full object-cover">
                    @endif
                </div>

            </div>

        </div>

    </div>
</form>

    
</div>

@push('scripts')
<script type="module">

    const aboutEditor = SUNEDITOR.create(document.getElementById('about_description_editor'), {
        height: '300px',
        plugins: plugins,
        minHeight: '300px',
        width: '100%',
        buttonList: [
            ['undo', 'redo'],
            ['bold', 'underline', 'italic', 'strike'],
            ['fontColor', 'backgroundColor'],
            ['list', 'align', 'fontSize'],
            ['link', 'removeFormat'],
            ['fullScreen']
        ],
        placeholder: 'Write about content...',
        resizingBar: false,
        charCounter: true,
        katex: 'window.katex',
        imagePaste: false
    });

    aboutEditor.onChange = function(contents) {
        document.getElementById('about_description_hidden').value = contents;
    };

    aboutEditor.onBlur = function() {
        let contents = '';
        if (typeof aboutEditor.getContents === 'function') {
            contents = aboutEditor.getContents();
        } else {
            contents = aboutEditor.$.frameContext.get('wysiwyg').innerHTML;
        }

        document.getElementById('about_description_hidden').value = contents;
    };

    window.setStatus = function(status) {

        // ensure latest content
        let contents = '';
        if (typeof aboutEditor.getContents === 'function') {
            contents = aboutEditor.getContents();
        } else {
            contents = aboutEditor.$.frameContext.get('wysiwyg').innerHTML;
        }

        document.getElementById('about_description_hidden').value = contents;
        document.getElementById('statusInput').value = status;

        document.getElementById('aboutForm').submit();
    };

    document.getElementById('aboutForm').addEventListener('submit', function() {

        let contents = '';
        if (typeof aboutEditor.getContents === 'function') {
            contents = aboutEditor.getContents();
        } else {
            contents = aboutEditor.$.frameContext.get('wysiwyg').innerHTML;
        }

        document.getElementById('about_description_hidden').value = contents;
    });

</script>
@endpush
