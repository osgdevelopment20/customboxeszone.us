@extends('backend.layouts.app')
@section('title')
    Add Product Variations
@endsection
@section('contents')
    <form action="{{route('dashboard.variation.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Selection</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="product_id">Select Product</label>
                            <span class="text-danger">*</span>
                            <select class="js-example-basic-single @error('product_id') is-invalid @enderror"
                                    id="product_id"
                                    name="product_id"
                                    required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                    <option value="{{$product->id}}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{$product->product_name}}
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
                            <!-- Variation items will be added here -->
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
                            <button type="submit" class="btn btn-primary">Save All Variations</button>
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
    let variationIndex = 0;

    $(document).ready(function() {
        $('#product_id').select2({
            placeholder: "Select a product",
            allowClear: true
        });

        // Add first variation by default
        addVariationRow();

        // Update CKEditor data before form submit
        $('form').on('submit', function() {
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        });

        // Add variation button
        $('.addVariation').on('click', function() {
            addVariationRow();
        });

        // Delete variation
        $(document).on('click', '.removeVariation', function() {
            if ($('.variation-item').length > 1) {
                const $item = $(this).closest('.variation-item');
                const index = $item.data('index');
                const editorId = 'specifications_' + index;

                // Destroy CKEditor instance if exists
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[editorId]) {
                    CKEDITOR.instances[editorId].destroy(true);
                }

                $item.remove();
                updateVariationNumbers();
            } else {
                alert('At least one variation is required!');
            }
        });
    });

    function addVariationRow() {
        const index = variationIndex++;
        const html = `
            <div class="variation-item border rounded p-3 mb-3" data-index="${index}">
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
                            @error('variations.*.title')
                            <div class="text-danger small">{{$message}}</div>
                            @enderror
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
</script>
@endpush
