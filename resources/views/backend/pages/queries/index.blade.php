@extends('backend.layouts.app')
@section('title')
    Queries
@endsection
@push('styles')
    <style>
        .cbz-artwork { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; }
        .cbz-artwork__item { position: relative; display: inline-flex; width: 48px; height: 48px; border: 1px solid #e9ebec; border-radius: 6px; overflow: hidden; background: #f3f6f9; align-items: center; justify-content: center; }
        .cbz-artwork__item:hover { border-color: #405189; box-shadow: 0 0 0 2px rgba(64, 81, 137, .15); }
        .cbz-artwork__item img { width: 100%; height: 100%; object-fit: cover; }
        .cbz-artwork__file { font-size: 11px; font-weight: 700; color: #405189; }
        .cbz-artwork__missing { display: none; font-size: 10px; font-weight: 600; color: #f06548; }
        .cbz-artwork__item.is-missing img { display: none; }
        .cbz-artwork__item.is-missing .cbz-artwork__missing { display: inline; }
        .cbz-artwork__more { font-size: 12px; color: #878a99; }
        .cbz-copy-toast { position: fixed; right: 24px; bottom: 24px; z-index: 2000; padding: 10px 16px; border-radius: 6px; background: #0ab39c; color: #fff; font-size: 13px; box-shadow: 0 4px 12px rgba(0, 0, 0, .15); opacity: 0; transition: opacity .2s; pointer-events: none; }
        .cbz-copy-toast.is-visible { opacity: 1; }
    </style>
@endpush
@section('contents')
    <div class="row">
        <div class="col-xl-12">

            <div class="card widget widget-stats">
                <div class="card-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        // Artwork links open in a new tab (target="_blank") and copy their URL.
        (function () {
            function fallbackCopy(text) {
                var field = document.createElement('textarea');
                field.value = text;
                field.setAttribute('readonly', '');
                field.style.position = 'fixed';
                field.style.opacity = '0';
                document.body.appendChild(field);
                field.select();
                try { document.execCommand('copy'); } catch (e) {}
                document.body.removeChild(field);
            }

            function toast(message) {
                var el = document.querySelector('.cbz-copy-toast');
                if (!el) {
                    el = document.createElement('div');
                    el.className = 'cbz-copy-toast';
                    document.body.appendChild(el);
                }
                el.textContent = message;
                el.classList.add('is-visible');
                clearTimeout(el._timer);
                el._timer = setTimeout(function () { el.classList.remove('is-visible'); }, 2000);
            }

            document.addEventListener('click', function (event) {
                var link = event.target.closest('.js-copy-artwork');
                if (!link) return;

                var url = link.href;
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(url).catch(function () { fallbackCopy(url); });
                } else {
                    fallbackCopy(url);
                }
                toast('Artwork link copied');
            });
        })();
    </script>
@endpush
