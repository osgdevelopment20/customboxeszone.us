@props([
    'name', // the name of the input/textarea
    'value' => '', // initial value (HTML)
    'id' => null, // optional, for multiple editors on one page
    'height' => '120px'
])

@php
    $editorId = $id ?? 'ckeditor_' . uniqid();
    $numericHeight = preg_replace('/[^0-9]/', '', $height) ?: 120;
@endphp

<textarea name="{{ $name }}" id="{{ $editorId }}" style="min-height: {{ $height }};">{!! $value !!}</textarea>

@once
    @push('styles')

    @endpush
    @push('scripts')
        <script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ asset('assets/ckeditor/styles.js') }}"></script>
        <script src="{{ asset('assets/ckeditor/adapters/jquery.js') }}"></script>
    @endpush
@endonce

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof CKEDITOR === 'undefined') {
        console.error('CKEditor not loaded');
        return;
    }

    // Destroy existing instance with same ID if present (safety for Livewire/Turbolinks etc.)
    if (CKEDITOR.instances['{{ $editorId }}']) {
        try { CKEDITOR.instances['{{ $editorId }}'].destroy(true); } catch (e) {}
    }

    var editor = CKEDITOR.replace('{{ $editorId }}', {
        height: {{ $numericHeight }},
        filebrowserUploadUrl: "{{ route('editor.image.upload') }}?_token={{ csrf_token() }}",
        filebrowserImageUploadUrl: "{{ route('editor.image.upload') }}?_token={{ csrf_token() }}",
        filebrowserUploadMethod: 'form'
    });

    var textarea = document.getElementById('{{ $editorId }}');
    var form = textarea ? textarea.closest('form') : null;
    if (form) {
        form.addEventListener('submit', function() {
            if (editor) {
                editor.updateElement();
            }
        });
    }
});
</script>
@endpush
