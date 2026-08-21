@props([
    'name', // the name of the hidden input
    'value' => '', // initial value (HTML)
    'id' => null, // optional, for multiple editors on one page
    'height' => '120px'
])

@php
    $editorId = $id ?? 'quill_' . uniqid();
    $inputId = $editorId . '_input';
@endphp

<input type="hidden" name="{{ $name }}" id="{{ $inputId }}" value="{{ $value }}">
<div id="{{ $editorId }}" style="min-height: {{ $height }};">{!! $value !!}</div>

@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    @endpush
@endonce

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (!window._quillEditors) window._quillEditors = {};
    const fullToolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        ['link', 'image', 'video', 'formula'],
        [{ 'header': 1 }, { 'header': 2 }],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
        [{ 'direction': 'rtl' }],
        [{ 'size': ['small', false, 'large', 'huge'] }],
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'font': [] }],
        [{ 'align': [] }],
        ['clean']
    ];

    function imageHandler() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = async () => {
            const file = input.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', '{{ csrf_token() }}');
                const response = await fetch('{{ route('quill.image.upload') }}', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.url) {
                    const range = this.quill.getSelection();
                    this.quill.insertEmbed(range.index, 'image', data.url);
                } else {
                    alert('Image upload failed');
                }
            }
        };
    }

    const quillOptions = {
        modules: {
            toolbar: {
                container: fullToolbarOptions,
                handlers: {
                    image: imageHandler
                }
            }
        },
        theme: 'snow'
    };

    const quill = new Quill('#{{ $editorId }}', quillOptions);
    window._quillEditors['{{ $editorId }}'] = quill;

    // Set initial content if value exists
    if (document.getElementById('{{ $inputId }}').value) {
        quill.root.innerHTML = document.getElementById('{{ $inputId }}').value;
    }

    // Update hidden input on form submit
    const form = document.getElementById('{{ $inputId }}').closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            document.getElementById('{{ $inputId }}').value = quill.root.innerHTML;
        });
    }
});
</script>
@endpush
