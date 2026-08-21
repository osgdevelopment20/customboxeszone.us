@extends('backend.layouts.app')
@section('title')
    Add Product
@endsection
@push('styles')
    <link href="{{asset('backend/assets/libs/dropzone/dropzone.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('contents')
    <form id="productForm" action="{{route('dashboard.product.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="product_title">Product Title (For SEO H1)</label><span class="text-danger">*</span>
                            <input type="text" class="form-control  @error('product_title') is-invalid @enderror" id="product_title" name="product_title" value="{{old('product_title')}}" placeholder="Enter product title" required>
                            @error('product_title')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="product_name">Product Name</label><span class="text-danger">*</span>
                            <input type="text" class="form-control  @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{old('product_name')}}" placeholder="Enter product name" required>
                            @error('product_name')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label> <span class="text-danger">*</span>
                            <input type="text"  id="slug"
                                   class="form-control  @error('slug') is-invalid @enderror"
                                   value="{{old('slug')}}" name="slug"
                                   placeholder="Slug">
                            @error('slug')
                            <div class="invalid-feedback">{{$message}}</div>
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
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Description</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">Add Detailed description for product</p>
                        <x-text-editor name="product_description" :value="old('product_description', $product->product_description ?? '')" />

                        @error('product_description')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>

                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Gallery</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="formFile1" class="form-label">Product Front Image</label><span class="text-danger">*</span>
                            <input name="product_front_image" class="form-control  @error('product_front_image') is-invalid @enderror"  value="{{old('product_front_image')}}" type="file" id="formFile1">
                            @error('product_front_image')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="formFile2" class="form-label">Product Hover Image</label><span class="text-danger">*</span>
                            <input name="product_back_image" class="form-control  @error('product_back_image') is-invalid @enderror"  value="{{old('product_back_image')}}" type="file" id="formFile2">

                            @error('product_back_image')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div>
                            <h5 class="fs-14 mb-1">Product Gallery</h5>
                            <p class="text-muted">Add Product Gallery Images <span class="text-danger">*</span></p>
                            <input name="product_multiple_images[]" class=" form-control @error('product_multiple_images') is-invalid @enderror"  type="file" multiple="multiple">

                            @error('product_multiple_images')
                            <div class="invalid-feedback">{{$message}}</div>
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
                                        <input class="form-check-input" value="value="{{ old('display_homepage') }}"" name="display_homepage" type="checkbox" role="switch" id="display_homepage" >
                                        <label class="form-check-label" for="display_homepage">Display on Homepage</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input"  value="{{ old('by_style') }}" name="by_style" type="checkbox" role="switch" id="by_style" {{ old('by_style') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="by_style">Box By Style?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input" value="{{ old('by_material') }}" name="by_material" type="checkbox" role="switch" id="by_material" {{ old('by_material') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="by_material">Box By Material?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input" value="{{ old('by_industry') }}" name="by_industry" type="checkbox" role="switch" id="by_industry" {{ old('by_industry') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="by_industry">Box by Industry</label>
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
                                    <label class="form-label" for="meta-title-input">Meta title</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="{{old('meta_title')}}" placeholder="Enter meta title" id="meta-title-input">
                                    @error('meta_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="meta-keywords-input">Meta Keywords</label>
                                    <input type="text" class="form-control  @error('meta_keywords') is-invalid @enderror" name="meta_keywords" value="{{old('meta_keywords')}}" placeholder="Enter meta keywords" id="meta-keywords-input">
                                    @error('meta_keywords')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label" for="meta-description-input">Meta Description</label><span class="text-danger">*</span>
                                <textarea class="form-control  @error('meta_description') is-invalid @enderror" id="meta-description-input" name="meta_description"  placeholder="Enter meta description" rows="3">{{old('meta_description')}}</textarea>
                                @error('meta_description')
                                <div class="invalid-feedback">{{$message}}</div>
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
                                    <div class="border-bottom-dashed mb-3">
                                        <p class="text-primary">Client Story: 1</p>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="client-story-image-1" class="form-label">Image</label>
                                                <input class="form-control" type="file" name="client_story[1][image]" id="client-story-image-1" accept="image/*" />
                                            </div>
                                            <div class="col-md-12 mt-2">
                                                <label for="client-story-description-1" class="form-label">Description</label>
                                                <x-text-editor name="client_story[1][description]" :value="old('client_story.1.description', '')" height="150px" />
                                            </div>
                                        </div>
                                    </div>
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
                                <div id="formRepeater">
                                    <div class="border-bottom-dashed mb-3">

                                    </div>
                                </div>
                                <a href="javascript:void(0);" id="addButton" class="btn btn-success mt-3" title="Add field"> Add FAQ</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mb-3">
                    <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light">Submit</button>
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
                            <select class="form-select" name="status" id="choices-publish-status-input" data-choices data-choices-search-false>
                                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Enable</option>
                                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Disable</option>
                            </select>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Categories <span class="text-danger">*</span></h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2"> <a href="{{route('dashboard.category.create')}}" target="_blank" class="float-end text-decoration-underline">Add
                                New</a>Select product category</p>
                        <select class="js-example-basic-multiple @error('product_category_id') is-invalid @enderror" name="product_category_id[]" id="product_category_id" multiple="multiple">
                            @foreach($categories as $category)
                                @foreach($category->categories as $subcate)
                                    <option value="{{$subcate->id}}" {{ in_array($subcate->id, old('product_category_id', [])) ? 'selected' : '' }}>{{$subcate->title}}</option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('product_category_id')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Related Product</h5>
                    </div>
                    <div class="card-body">
                        <select class="js-example-basic-multiple @error('related_products') is-invalid @enderror" multiple="multiple" name="related_products[]" id="related_products">
                            @foreach($products as $productItem)
                                <option value="{{$productItem->id}}" {{ in_array($productItem->id, old('related_products', [])) ? 'selected' : '' }}>{{$productItem->product_title}}</option>
                            @endforeach
                        </select>
                        @error('related_products')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')


    <!-- dropzone js -->
    <script src="{{asset('backend/assets/libs/dropzone/dropzone-min.js')}}"></script>

    <script src="{{asset('backend/assets/js/pages/ecommerce-product-create.init.js')}}"></script>
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
        // Your existing old FAQs
        const oldFaqs = @json(old('faqs', [])); // This will convert the old FAQs to a JavaScript object

        // New FAQs from the provided JSON data
        const defaultFaqs = {
            "1": {
                "question": "How to Calculate or Measure the Sizes Boxes?",
                "answer": "To measure the size of your Boxes, follow these steps: Diameter: Measure the circular width of the box from one edge to the opposite edge. Height: Measure from the bottom of the box to the top."
            },
            "2": {
                "question": "What is Your Standard Turnaround Time?",
                "answer": "Our standard production turnaround time is 8 to 12 business days after final design approval. This includes printing, die-cutting, and finishing processes. Shipping times may vary based on your location."
            },
            "3": {
                "question": "What is Your Urgent Turnaround Time?",
                "answer": "For urgent orders, we offer rush production with a 4 to 6 business days turnaround. If you need expedited delivery, let us know at the time of ordering, and we’ll prioritize your request."
            },
            "4": {
                "question": "How Many Colors Can I Print on My Boxes?",
                "answer": "We offer full-color CMYK and PMS printing, meaning you can print as many colors as you like without limitations. Whether you want a single-color minimalist look or a vibrant, multi-color design, we have advanced printing technology to bring your vision to life."
            },
            "5": {
                "question": "What is the Maximum Order You Can Process Per Month?",
                "answer": "We can handle bulk orders efficiently, processing up to 500,000+ Boxes per month. Whether you need a small batch or a large-scale production run, we have the capacity and resources to meet your demands."
            },
            "6": {
                "question": "Where is Your Packaging Manufactured?",
                "answer": "Our Boxes are proudly manufactured in our state-of-the-art production facility, located in the USA, UK and Middle East."
            },
            "7": {
                "question": "How Can I Order a Physical Sample Before Placing a Mass Order?",
                "answer": "We offer physical samples for bulk orders so you can check the quality, size, and design before placing a bulk order."
            },
            "8": {
                "question": "How Does Your Design Team Help with My Project?",
                "answer": "Our expert in-house design team provides 100% free design support to bring your packaging ideas to life."
            },
            "9": {
                "question": "How Can I Review the Box Before It Is Printed?",
                "answer": "Before printing, we provide a 3D digital proof (mockup) or a physical sample (upon request). This ensures you can review every detail—colors, design alignment, and overall look—before final production. We only proceed once you approve the design."
            }
        };

        //repeated form
        let repeaterCount = Object.keys(oldFaqs).length; // Start from the number of old FAQs
        const formRepeater = document.getElementById('formRepeater');
        const addButton = document.getElementById('addButton');

        // Populate existing FAQs if any
        for (const [index, faq] of Object.entries(oldFaqs)) {
            addFaqRow(index, faq.question, faq.answer);
        }

        // Populate default FAQs
        for (const [index, faq] of Object.entries(defaultFaqs)) {
            if (!oldFaqs[index]) { // Only add if it doesn't already exist
                addFaqRow(index, faq.question, faq.answer);
                repeaterCount++; // Increment after adding each new FAQ
            }
        }

        addButton.addEventListener('click', () => {
            repeaterCount++;
            addFaqRow(repeaterCount);
        });

        function addFaqRow(count, question = '', answer = '') {
            const newRow = document.createElement('div');
            newRow.classList.add('border-bottom-dashed', 'mb-3');
            newRow.innerHTML = `
            <p class="text-primary">FAQ: ${count}</p>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="question-${count}" class="form-label">Question</label>
                    <input class="form-control" type="text" value="${question}" name="faqs[${count}][question]" id="question-${count}" placeholder="Question" />
                </div>
                <div class="col-md-12 mt-2">
                    <label for="answer-${count}" class="form-label">Answer</label>
                    <input class="form-control" type="text" value="${answer}" name="faqs[${count}][answer]" id="answer-${count}" placeholder="Answer" />
                </div>
                <div class="col-md-4">
                    <a href="javascript:void(0);" onclick="removeRow(this)" class="btn btn-danger mt-4" title="Remove"> Remove</a>
                </div>
            </div>
        `;
            formRepeater.appendChild(newRow);
        }

        function removeRow(button) {
            if (confirm('Are you sure you want to delete this element?')) {
                const row = button.closest('.border-bottom-dashed');
                formRepeater.removeChild(row);
            }
        }
    </script>
    <script>
        // Client Story Repeater
        const oldClientStories = @json(old('client_story', []));
        let clientStoryCount = Object.keys(oldClientStories).length > 0 ? Math.max(...Object.keys(oldClientStories).map(Number)) : 1;
        const clientStoryRepeater = document.getElementById('clientStoryRepeater');
        const addClientStoryButton = document.getElementById('addClientStoryButton');

        // Function to get next available client story number
        function getNextClientStoryNumber() {
            const existingNumbers = [];

            // Get all existing story numbers from the DOM
            const storyElements = clientStoryRepeater.querySelectorAll('.text-primary');
            storyElements.forEach(element => {
                const match = element.textContent.match(/Client Story: (\d+)/);
                if (match) {
                    existingNumbers.push(parseInt(match[1]));
                }
            });

            // Sort the numbers to find gaps
            existingNumbers.sort((a, b) => a - b);

            // Find the first gap or return the next sequential number
            for (let i = 1; i <= existingNumbers.length + 1; i++) {
                if (!existingNumbers.includes(i)) {
                    return i;
                }
            }

            return existingNumbers.length + 1;
        }

        // Populate existing client stories if any
        for (const [index, story] of Object.entries(oldClientStories)) {
            if (index > 1) { // Skip the first one as it's already in HTML
                addClientStoryRow(index, story.description || '');
            }
        }

        addClientStoryButton.addEventListener('click', () => {
            const nextNumber = getNextClientStoryNumber();
            addClientStoryRow(nextNumber);
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
                        <textarea name="client_story[${count}][description]" id="${editorId}" style="min-height: 150px;">${description}</textarea>
                    </div>
                    <div class="col-md-4">
                        <a href="javascript:void(0);" onclick="removeClientStoryRow(this)" class="btn btn-danger mt-4" title="Remove"> Remove</a>
                    </div>
                </div>
            `;
            clientStoryRepeater.appendChild(newRow);

            // Initialize CKEditor for the new textarea
            setTimeout(function() {
                if (typeof CKEDITOR !== 'undefined') {
                    // Destroy existing instance if present
                    if (CKEDITOR.instances[editorId]) {
                        try {
                            CKEDITOR.instances[editorId].destroy(true);
                        } catch (e) {}
                    }

                    // Create new CKEditor instance
                    var editor = CKEDITOR.replace(editorId, {
                        height: 150,
                        filebrowserUploadUrl: "{{ route('editor.image.upload') }}?_token={{ csrf_token() }}",
                        filebrowserImageUploadUrl: "{{ route('editor.image.upload') }}?_token={{ csrf_token() }}",
                        filebrowserUploadMethod: 'form'
                    });

                    // Handle form submission
                    var textarea = document.getElementById(editorId);
                    var form = textarea ? textarea.closest('form') : null;
                    if (form && !form.hasAttribute('data-ckeditor-handled-' + editorId)) {
                        form.setAttribute('data-ckeditor-handled-' + editorId, 'true');
                        form.addEventListener('submit', function() {
                            if (editor) {
                                editor.updateElement();
                            }
                        });
                    }
                }
            }, 100);
        }

        function removeClientStoryRow(button) {
            if (confirm('Are you sure you want to delete this client story?')) {
                const row = button.closest('.border-bottom-dashed');

                // Find and destroy CKEditor instance before removing the row
                const textarea = row.querySelector('textarea[id^="client-story-description-"]');
                if (textarea && textarea.id && CKEDITOR.instances[textarea.id]) {
                    try {
                        CKEDITOR.instances[textarea.id].destroy(true);
                    } catch (e) {
                        console.warn('Error destroying CKEditor instance:', e);
                    }
                }

                clientStoryRepeater.removeChild(row);
            }
        }
    </script>
@endpush
