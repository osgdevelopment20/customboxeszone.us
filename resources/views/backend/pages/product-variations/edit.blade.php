@extends('backend.layouts.app')
@section('title')
    Edit Product Variations
@endsection
@section('contents')
    <form action="{{route('dashboard.variation.update', $product->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Selection</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="product_id">Product</label>
                            <span class="text-danger">*</span>
                            <select class="js-example-basic-single @error('product_id') is-invalid @enderror"
                                    id="product_id"
                                    name="product_id"
                                    required
                                    onchange="loadProductVariations(this.value)">
                                <option value="">Select a product</option>
                                @foreach($products as $prod)
                                    <option value="{{$prod->id}}"
                                            {{ old('product_id', $product->id) == $prod->id ? 'selected' : '' }}>
                                        {{$prod->product_name}}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Product Variations</h5>
                        <button type="button" class="btn btn-sm btn-success addVariation" id="addVariation">
                            <i class="ri-add-line"></i> Add Another Variation
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="variationsContainer">
                            @php
                                $variations = $product->variations;
                            @endphp

                            @if($variations->count() > 0)
                                @foreach($variations as $index => $var)
                                <div class="variation-item border rounded p-3 mb-3" data-index="{{ $index }}" data-id="{{ $var->id }}">
                                    <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $var->id }}">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Variation #<span class="variation-number">{{ $index + 1 }}</span></h6>
                                    <button type="button" class="btn btn-sm btn-danger removeVariation" data-delete-url="{{ route('dashboard.variation.delete', $var) }}">
                                        <i class="ri-delete-bin-line"></i> Remove
                                    </button>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Variation Title <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="variations[{{ $index }}][title]"
                                                   value="{{ old('variations.'.$index.'.title', $var->title) }}"
                                                   placeholder="Enter variation title"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Sort Order</label>
                                            <input type="number"
                                                   class="form-control"
                                                   name="variations[{{ $index }}][sort_order]"
                                                   value="{{ old('variations.'.$index.'.sort_order', $var->sort_order) }}"
                                                   placeholder="0">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="variations[{{ $index }}][status]">
                                                <option value="1" {{ old('variations.'.$index.'.status', $var->status) == 1 ? 'selected' : '' }}>Enable</option>
                                                <option value="0" {{ old('variations.'.$index.'.status', $var->status) == 0 ? 'selected' : '' }}>Disable</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Specifications</label>
                                            <textarea class="form-control"
                                                      id="specifications_existing_{{ $index }}"
                                                      name="variations[{{ $index }}][specifications]"
                                                      rows="3"
                                                      placeholder="Enter specifications">{{ old('variations.'.$index.'.specifications', $var->specifications) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Variation Image</label>
                                            @if($var->getMedia('variation_image')->isNotEmpty())
                                                <div class="mb-2">
                                                    <img src="{{$var->getFirstMediaUrl('variation_image')}}"
                                                         alt="Current Image"
                                                         class="img-thumbnail"
                                                         style="max-width: 150px;">
                                                    <p class="text-muted small mt-1">Current Image (Upload new to replace)</p>
                                                </div>
                                            @endif
                                            <input type="file"
                                                   class="form-control"
                                                   name="variations[{{ $index }}][image]"
                                                   accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                                @endforeach
                            @else
                                <p class="text-muted">No variations found. Click "Add Another Variation" to create one.</p>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-success addVariation" id="addVariation">
                            <i class="ri-add-line"></i> Add Another Variation
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{route('dashboard.variation.index')}}"
                               class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update All Variations</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
<script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/ckeditor/styles.js') }}"></script>
<script src="{{ asset('assets/ckeditor/adapters/jquery.js') }}"></script>
<script>
    let variationIndex = {{ $variations->count() }};
    const deletedVariations = [];

    $(document).ready(function() {
        $('#product_id').select2({
            placeholder: "Select a product",
            allowClear: true
        });

        // Initialize CKEditor for existing variations
        if (typeof CKEDITOR !== 'undefined') {
            $('.variation-item').each(function(index) {
                var editorId = 'specifications_existing_' + index;
                if (document.getElementById(editorId)) {
                    CKEDITOR.replace(editorId, {
                        height: 200,
                        filebrowserUploadUrl: "{{ route('editor.image.upload') }}?_token={{ csrf_token() }}",
                        filebrowserImageUploadUrl: "{{ route('editor.image.upload') }}?_token={{ csrf_token() }}",
                        filebrowserUploadMethod: 'form'
                    });
                }
            });
        }

        // Add first variation if no variations exist
        if ($('.variation-item').length === 0) {
            addVariationRow();
        }

        // Update CKEditor data before form submit
        $('form').on('submit', function() {
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        });

        updateVariationNumbers();

        // Add variation button
        $('.addVariation').on('click', function() {
            addVariationRow();
        });

        // Delete variation
        $(document).on('click', '.removeVariation', function() {
            const $item = $(this).closest('.variation-item');
            const variationId = $item.data('id');
            const deleteUrl = $(this).data('delete-url');

            if (confirm('Are you sure you want to delete this variation?')) {
                if (variationId && deleteUrl) {
                    // Get the editor ID and destroy it if exists
                    const index = $item.data('index');
                    const editorId = 'specifications_existing_' + index;
                    if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[editorId]) {
                        CKEDITOR.instances[editorId].destroy(true);
                    }

                    // If variation exists in DB, send delete request
                    $.ajax({
                        url: deleteUrl,
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $item.remove();
                            updateVariationNumbers();
                            alert('Variation deleted successfully');
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            alert('Error deleting variation: ' + (xhr.responseJSON?.message || 'Unknown error'));
                        }
                    });
                } else {
                    // If new variation (not saved yet), just remove from DOM
                    if ($('.variation-item').length > 1) {
                        // Destroy CKEditor instance if exists
                        const index = $item.data('index');
                        const editorId = 'specifications_' + index;
                        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[editorId]) {
                            CKEDITOR.instances[editorId].destroy(true);
                        }
                        $item.remove();
                        updateVariationNumbers();
                    } else {
                        alert('At least one variation is required!');
                    }
                }
            }
        });
    });

    function addVariationRow() {
        const index = variationIndex++;
        const html = `
            <div class="variation-item border rounded p-3 mb-3" data-index="${index}">
                <input type="hidden" name="variations[${index}][id]" value="">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Variation #<span class="variation-number">1</span></h6>
                    <button type="button" class="btn btn-sm btn-danger removeVariation">
                        <i class="ri-delete-bin-line"></i> Remove
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Variation Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control"
                                   name="variations[${index}][title]"
                                   placeholder="Enter variation title"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number"
                                   class="form-control"
                                   name="variations[${index}][sort_order]"
                                   value="0"
                                   placeholder="0">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="variations[${index}][status]">
                                <option value="1" selected>Enable</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Specifications</label>
                            <textarea class="form-control"
                                      id="specifications_${index}"
                                      name="variations[${index}][specifications]"
                                      rows="3"
                                      placeholder="Enter specifications"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Variation Image</label>
                            <input type="file"
                                   class="form-control"
                                   name="variations[${index}][image]"
                                   accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#variationsContainer').append(html);
        updateVariationNumbers();

        // Initialize CKEditor for the new variation
        initializeEditor(index);
    }

    function initializeEditor(index) {
        // Initialize CKEditor for the specifications field
        if (typeof CKEDITOR !== 'undefined') {
            setTimeout(function() {
                var editorId = 'specifications_' + index;
                // Destroy if already exists
                if (CKEDITOR.instances[editorId]) {
                    CKEDITOR.instances[editorId].destroy(true);
                }
                CKEDITOR.replace(editorId, {
                    height: 200,
                    filebrowserUploadUrl: "{{ route('editor.image.upload') }}?_token={{ csrf_token() }}",
                    filebrowserImageUploadUrl: "{{ route('editor.image.upload') }}?_token={{ csrf_token() }}",
                    filebrowserUploadMethod: 'form'
                });
            }, 100);
        }
    }

    function updateVariationNumbers() {
        $('.variation-item').each(function(index) {
            $(this).find('.variation-number').text(index + 1);
        });
    }

    function loadProductVariations(productId) {
        if (productId && confirm('Changing the product will reload the page. Continue?')) {
            window.location.href = `{{ url('dashboard/variations/product') }}/${productId}/edit`;
        }
    }
</script>
@endpush
