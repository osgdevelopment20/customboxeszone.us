@extends('backend.layouts.app')
@section('title')
    Edit Product
@endsection
@push('styles')
    <link href="{{ asset('backend/assets/libs/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css" />

@endpush
@section('contents')
    <form id="editProductForm" action="{{ route('dashboard.product.update', $product) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="product-title-input">Product Title (For SEO H1)</label><span
                                class="text-danger">*</span>
                            <input type="text" class="form-control  @error('product_title') is-invalid @enderror"
                                id="product-title-input" name="product_title"
                                value="{{ old('product_title', $product->product_title) }}" placeholder="Enter product title"
                                required>
                            @error('product_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="product-title-input1">Product Name</label><span
                                class="text-danger">*</span>
                            <input type="text" class="form-control  @error('product_name') is-invalid @enderror"
                                id="product-title-input1" name="product_name"
                                value="{{ old('product_name', $product->product_name) }}" placeholder="Enter product name"
                                required>
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="slug">Slug</label><span class="text-danger">*</span>
                            <input type="text" class="form-control  @error('slug') is-invalid @enderror" id="slug"
                                name="slug" value="{{ old('slug', $product->slug) }}" placeholder="Product slug" required>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Short Description</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">Add short description for product</p>
                       <x-text-editor name="product_detail" :value="old('product_detail', $product->product_detail ?? '')" />

                        @error('product_detail')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Description</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">Add Detailed description for product</p>
                        <x-text-editor name="product_description" :value="old('product_description', $product->product_description ?? '')" height="200px" />
                        @error('product_description')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Gallery</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="formFile1" class="form-label">Product Front Image</label><span
                                class="text-danger">*</span>
                            <input name="product_front_image"
                                class="form-control  @error('product_front_image') is-invalid @enderror"
                                value="{{ old('product_front_image') }}" type="file" id="formFile1">
                            @error('product_front_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="row">
                                @if ($product->getMedia('product_front_image')->isNotEmpty())
                                    @foreach ($product->getMedia('product_front_image') as $media)
                                        <div class="col-2">
                                            <img src="{{ $media->getUrl('s') }}"
                                                 alt="{{ $media->name }}"
                                                 class="img-thumbnail mt-3 w-100"
                                            />
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                        </div>
                        <div class="mb-4">
                            <label for="formFile2" class="form-label">Product Hover Image</label><span
                                class="text-danger">*</span>
                            <input name="product_back_image"
                                class="form-control  @error('product_back_image') is-invalid @enderror"
                                value="{{ old('product_back_image') }}" type="file" id="formFile2">
                            @error('product_back_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="row">
                                @if ($product->getMedia('product_back_image')->isNotEmpty())
                                    @foreach ($product->getMedia('product_back_image') as $media)
                                        <div class="col-2">
                                            <img src="{{ $media->getUrl('s') }}"
                                                 alt="{{ $media->name }}"
                                                 class="img-thumbnail mt-3 w-100"
                                            />
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div>
                            <h5 class="fs-14 mb-1">Product Gallery</h5>
                            <p class="text-muted">Add Product Gallery Images <span class="text-danger">*</span></p>
                            <input name="product_multiple_images[]"
                                class=" form-control @error('product_multiple_images[]') is-invalid @enderror"
                                type="file" multiple="multiple">
                            <div class="row">
                            @if ($product->getMedia('product_gallery')->isNotEmpty())
                                @foreach ($product->getMedia('product_gallery') as $media)
                                        <div class="col-2 position-relative">
                                        <img src="{{ $media->getUrl('s') }}"
                                              alt="{{ $media->name }}"
                                             class="img-thumbnail mt-3 w-100"
                                        />
                                         <form action="{{ route('dashboard.product.media.delete', [$product->slug, $media->id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button class="btn btn-danger btn-sm" type="submit" style="position: absolute;right: 10px;top: 15px;">x</button>
                                         </form>
                                       </div>
                                @endforeach
                            @endif
                            </div>

                            @error('product_multiple_images[]')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        General Info
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input"
                                               {{ $product->display_homepage == 1 ? 'checked' : '' }}
                                                name="display_homepage"
                                               type="checkbox" role="switch" id="display_homepage">
                                        <label class="form-check-label" for="display_homepage">Display on
                                            Homepage</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input"
                                               {{ $product->by_style == 1 ? 'checked' : '' }}
                                                name="by_style" type="checkbox"
                                               role="switch" id="by_style">
                                        <label class="form-check-label" for="by_style">Box By Style?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input"
                                               {{ $product->by_material == 1 ? 'checked' : '' }}
                                               name="by_material" type="checkbox"
                                               role="switch" id="by_material">
                                        <label class="form-check-label" for="by_material">Box By Material?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input"
                                               {{ $product->by_industry == 1 ? 'checked' : '' }}
                                                name="by_industry" type="checkbox"
                                               role="switch" id="by_industry">
                                        <label class="form-check-label" for="by_industry">Box By Industry</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        Meta Data
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="meta-title-input">Meta title</label><span
                                        class="text-danger">*</span>
                                    <input type="text"
                                           class="form-control @error('meta_title') is-invalid @enderror"
                                           name="meta_title" value="{{ old('meta_title', $product->meta_title) }}"
                                           placeholder="Enter meta title" id="meta-title-input">
                                    @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="meta-keywords-input">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords"
                                           value="{{ old('meta_keywords', $product->meta_keywords) }}"
                                           placeholder="Enter meta keywords" id="meta-keywords-input">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label" for="meta-description-input">Meta Description</label><span
                                    class="text-danger">*</span>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta-description-input"
                                          name="meta_description" placeholder="Enter meta description" rows="3">{{ old('meta_description', $product->meta_description) }}</textarea>
                                @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Client Stories</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <div id="clientStoryRepeater">
                                    @if($product->client_story)
                                        @foreach($product->clientStoriesResolved() as $key => $story)
                                            <div class="border-bottom-dashed mb-3">
                                                <p class="text-primary">Client Story: {{$key}}</p>
                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <label for="client-story-image-{{$key}}" class="form-label">Image</label>
                                                        <input class="form-control" type="file" name="client_story[{{$key}}][image]" id="client-story-image-{{$key}}" accept="image/*" />
                                                        <input type="hidden" name="client_story[{{$key}}][media_id]" value="{{ $story['media_id'] }}" />
                                                        @if(!empty($story['image']))
                                                            <div class="mt-2">
                                                                <img src="{{ $story['image'] }}" alt="Client Story Image" class="img-thumbnail" style="max-width: 200px;">
                                                                <p class="small text-muted">Current image (upload new to replace)</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        <label for="client-story-description-{{$key}}" class="form-label">Description</label>
                                                        <x-text-editor name="client_story[{{$key}}][description]" :value="$story['description'] ?? ''" height="150px" />
                                                    </div>
                                                    <div class="col-md-2 mt-2">
                                                        <a href="javascript:void(0);" onclick="removeClientStoryRow(this)" class="btn btn-danger mt-4" title="Remove"> Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <a href="javascript:void(0);" id="addClientStoryButton" class="btn btn-success mt-3" title="Add field"> Add Client Story</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product FAQs</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <div id="formRepeater"></div>
                                <a href="javascript:void(0);" id="addButton" class="btn btn-success mt-3" title="Add field"> Add FAQ</a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="text-end mb-3">
                    <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light">Update</button>
                </div>


            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Publish</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="choices-publish-status-input" class="form-label">Status</label>
                            <select class="form-select" name="status" id="choices-publish-status-input" data-choices
                                data-choices-search-false>
                                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Enable</option>
                                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Disable</option>
                            </select>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Categories<span class="text-danger">*</span></h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2"> <a href="{{ route('dashboard.category.create') }}" target="_blank"
                                class="float-end text-decoration-underline">Add
                                New</a>Select product category</p>
                        <select class="js-example-basic-multiple @error('product_category_id[]') is-invalid @enderror"
                            name="product_category_id[]" id="product_category_id" multiple="multiple">
                            @foreach ($categories as $category)
                                @foreach ($category->categories as $subcate)
                                    <option value="{{ $subcate->id }}"
                                        {{ in_array($subcate->id, $product->category()->pluck('id')->toArray()) == true ? 'selected' : '' }}>
                                        {{ $subcate->title }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('product_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Related Product</h5>
                    </div>
                    <div class="card-body">
                        <select class="js-example-basic-multiple @error('related_products[]') is-invalid @enderror"
                            multiple="multiple" name="related_products[]" id="related_products">
                            @foreach ($products as $productItem)
                                <option value="{{ $productItem->id }}"
                                    {{ in_array($productItem->id, $related_products) == true ? 'selected' : '' }}>
                                    {{ $productItem->product_name }}</option>
                            @endforeach
                        </select>
                        @error('related_products')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <!-- ckeditor -->
    {{-- <script src="{{ asset('backend/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script> --}}

    <!-- dropzone js -->
    <script src="{{ asset('backend/assets/libs/dropzone/dropzone-min.js') }}"></script>

    <script src="{{ asset('backend/assets/js/pages/ecommerce-product-create.init.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#product_category_id').select2({
                placeholder: "Select categories",
                allowClear: true,
                multiple: true,
            });
            $('#related_products').select2({
                placeholder: "Select Related products",
                allowClear: true,
                multiple: true,
            });
        });


    </script>


    <script>
        // FAQ repeater — auto-renumbers on add/remove; prefills existing FAQs
        (function () {
            const formRepeater = document.getElementById('formRepeater');
            const addButton = document.getElementById('addButton');

            function renumber() {
                formRepeater.querySelectorAll('.faq-row').forEach(function (row, i) {
                    const n = i + 1;
                    row.querySelector('.faq-label').textContent = 'FAQ: ' + n;
                    const q = row.querySelector('.faq-question');
                    const a = row.querySelector('.faq-answer');
                    q.name = 'faqs[' + n + '][question]';
                    q.id = 'question-' + n;
                    a.name = 'faqs[' + n + '][answer]';
                    a.id = 'answer-' + n;
                });
            }

            function addRow(question, answer) {
                const row = document.createElement('div');
                row.className = 'faq-row border-bottom-dashed mb-3';
                row.innerHTML =
                    '<p class="text-primary faq-label">FAQ:</p>' +
                    '<div class="row mb-3">' +
                        '<div class="col-md-12">' +
                            '<label class="form-label">Question</label>' +
                            '<input class="form-control faq-question" type="text" placeholder="Question" />' +
                        '</div>' +
                        '<div class="col-md-12 mt-2">' +
                            '<label class="form-label">Answer</label>' +
                            '<input class="form-control faq-answer" type="text" placeholder="Answer" />' +
                        '</div>' +
                        '<div class="col-md-4 mt-2">' +
                            '<a href="javascript:void(0);" class="btn btn-danger faq-remove" title="Remove">Remove</a>' +
                        '</div>' +
                    '</div>';
                row.querySelector('.faq-question').value = question || '';
                row.querySelector('.faq-answer').value = answer || '';
                row.querySelector('.faq-remove').addEventListener('click', function () {
                    if (confirm('Are you sure you want to delete this FAQ?')) {
                        row.remove();
                        renumber();
                    }
                });
                formRepeater.appendChild(row);
                renumber();
            }

            addButton.addEventListener('click', function () { addRow(); });

            const existingFaqs = @json(old('faqs') ?: (json_decode($product->faqs, true) ?: []));
            Object.keys(existingFaqs || {}).forEach(function (k) {
                addRow((existingFaqs[k] || {}).question, (existingFaqs[k] || {}).answer);
            });
        })();

    </script>
    <script>
        // Client Story Repeater — existing rows prefilled; new rows use fresh keys
        @php
            $clientStories = json_decode($product->client_story, true);
            $maxKey = is_array($clientStories) && count($clientStories) ? max(array_map('intval', array_keys($clientStories))) : 0;
        @endphp
        let clientStoryCount = {{ $maxKey }};
        const clientStoryRepeater = document.getElementById('clientStoryRepeater');
        const addClientStoryButton = document.getElementById('addClientStoryButton');

        function renumberClientStoryLabels() {
            const labels = clientStoryRepeater.querySelectorAll(':scope > .border-bottom-dashed > p.text-primary');
            labels.forEach(function (el, i) { el.textContent = 'Client Story: ' + (i + 1); });
        }

        addClientStoryButton.addEventListener('click', () => {
            clientStoryCount++;
            addClientStoryRow(clientStoryCount);
        });

        function addClientStoryRow(count, description = '') {
            const editorId = `client-story-description-${count}`;
            const newRow = document.createElement('div');
            newRow.classList.add('border-bottom-dashed', 'mb-3');
            newRow.innerHTML = `
                <p class="text-primary">Client Story: ${count}</p>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="client-story-image-${count}" class="form-label">Image</label>
                        <input class="form-control" type="file" name="client_story[${count}][image]" id="client-story-image-${count}" accept="image/*" />
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="${editorId}" class="form-label">Description</label>
                        <textarea name="client_story[${count}][description]" id="${editorId}" style="min-height: 150px;"></textarea>
                    </div>
                    <div class="col-md-4">
                        <a href="javascript:void(0);" onclick="removeClientStoryRow(this)" class="btn btn-danger mt-4" title="Remove"> Remove</a>
                    </div>
                </div>
            `;
            newRow.querySelector('textarea').value = description || '';
            clientStoryRepeater.appendChild(newRow);
            renumberClientStoryLabels();

            setTimeout(function() {
                if (typeof CKEDITOR !== 'undefined') {
                    if (CKEDITOR.instances[editorId]) {
                        try { CKEDITOR.instances[editorId].destroy(true); } catch (e) {}
                    }
                    var editor = CKEDITOR.replace(editorId, {
                        height: 150,
                        filebrowserUploadUrl: "{{ route('editor.image.upload') }}?_token={{ csrf_token() }}",
                        filebrowserImageUploadUrl: "{{ route('editor.image.upload') }}?_token={{ csrf_token() }}",
                        filebrowserUploadMethod: 'form'
                    });
                    var textarea = document.getElementById(editorId);
                    var form = textarea ? textarea.closest('form') : null;
                    if (form && !form.hasAttribute('data-ckeditor-handled-' + editorId)) {
                        form.setAttribute('data-ckeditor-handled-' + editorId, 'true');
                        form.addEventListener('submit', function() { if (editor) { editor.updateElement(); } });
                    }
                }
            }, 100);
        }

        function removeClientStoryRow(button) {
            if (confirm('Are you sure you want to delete this client story?')) {
                const row = button.closest('.border-bottom-dashed');
                const textarea = row.querySelector('textarea[id^="client-story-description-"]');
                if (textarea && textarea.id && typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[textarea.id]) {
                    try { CKEDITOR.instances[textarea.id].destroy(true); } catch (e) {}
                }
                clientStoryRepeater.removeChild(row);
                renumberClientStoryLabels();
            }
        }

        // Ensure labels are sequential on load
        renumberClientStoryLabels();
    </script>
@endpush
