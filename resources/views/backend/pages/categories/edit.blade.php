@extends('backend.layouts.app')
@section('title')
    Edit Category
@endsection
@push('styles')
    <!-- Plugins css -->
    <link href="{{asset('backend/assets/libs/dropzone/dropzone.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('contents')
    <form  action="{{route('dashboard.category.update',$category)}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Edit Category</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row mt-3">
                                <div class="col-12 mt-3">
                                    <label for="parent_id" class="form-label">Select Category Level</label> <span class="text-danger">*</span>
                                    <select id="parent_id" class="@error('parent_id') is-invalid @enderror"  name="parent_id">
                                        <option value="0" >Main Category</option>
                                        @foreach($categories as $productCategory)
                                            <option value="{{$productCategory->id}}" @if($productCategory->id == $category->parent_id) selected @endif>{{$productCategory->title}}</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="title" class="form-label">Category Title (For SEO H1)</label> <span class="text-danger">*</span>
                                    <input type="text"  id="title"
                                           class="form-control  @error('title') is-invalid @enderror"
                                           value="{{old('title',$category->title)}}" name="title"
                                           placeholder="Category Title">
                                    @error('title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="name" class="form-label">Category Name </label> <span class="text-danger">*</span>
                                    <input type="text"  id="name"
                                           class="form-control  @error('name') is-invalid @enderror"
                                           value="{{old('name',$category->name)}}" name="name"
                                           placeholder="Name">
                                    @error('name')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="slug" class="form-label">Slug</label> <span class="text-danger">*</span>
                                    <input type="text"  id="slug"
                                           class="form-control  @error('slug') is-invalid @enderror"
                                           value="{{old('slug',$category->slug)}}" name="slug"
                                           placeholder="Slug">
                                    @error('slug')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Category Gallery  (440x440)</h5>
                    </div>
                    <div class="card-body  @error('category_img') is-invalid @enderror">
                        <div class="mb-4">
                            <h5 class="fs-14 mb-1">Category Image</h5>
                            <p class="text-muted">Add Category main Image.</p>
                            <div class="text-center">
                                <div class="position-relative d-inline-block">
                                    <div class="position-absolute top-100 start-100 translate-middle">
                                        <label for="product-image-input" class="mb-0" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                    <i class="ri-image-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                        <input class="form-control d-none" name="category_img" value="" id="product-image-input" type="file" accept="image/png, image/gif, image/jpeg, image/webp">
                                    </div>
                                    <div class="avatar-lg">
                                        <div class="avatar-title bg-light rounded">
                                                @if ($category->getMedia('category_img')->isNotEmpty())
                                                    @foreach ($category->getMedia('category_img') as $media)
                                                            <img src="{{ $media->getUrl('dropdown-icon') }}"
                                                                 alt="{{ $media->name }}"
                                                                 id="product-img" class="avatar-md h-auto"
                                                            />
                                                    @endforeach
                                                @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('category_img')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Category Short Description</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">Add short description for category</p>
                        <x-text-editor name="description" :value="old('description', $category->description ?? '')" />

                        @error('description')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detailed Description</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">Add Detailed description for category</p>
                        <x-text-editor name="detail" :value="old('detail', $category->detail ?? '')" />
                        @error('detail')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        General Info
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input"  {{$category->display_homepage == 1 ? 'checked' : ''}} name="display_homepage" type="checkbox" role="switch" id="display_homepage" >
                                        <label class="form-check-label" for="display_homepage">Display Homepage</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input"  {{$category->homepage_category_banner == 1 ? 'checked' : ''}}  name="homepage_category_banner" type="checkbox" role="switch" id="homepage_category_banner" >
                                        <label class="form-check-label" for="homepage_category_banner">Homepage Category Banner</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input"  {{$category->by_style == 1 ? 'checked' : ''}} name="by_style" type="checkbox" role="switch" id="by_style">
                                        <label class="form-check-label" for="by_style">Box By Style</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input"  {{$category->by_material == 1 ? 'checked' : ''}} name="by_material" type="checkbox" role="switch" id="by_material">
                                        <label class="form-check-label" for="by_material">Box By Material</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-custom form-switch-success">
                                        <input class="form-check-input"  {{$category->by_industry == 1 ? 'checked' : ''}} name="by_industry" type="checkbox" role="switch" id="by_industry">
                                        <label class="form-check-label" for="by_industry">Box By Industry</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" id="homepage_image_section" style="display: none;">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Homepage Image (600x255)</h5>
                    </div>
                    <div class="card-body @error('homepage_img') is-invalid @enderror">
                        <div class="mb-4">
                            <h5 class="fs-14 mb-1">Homepage Display Image</h5>
                            <p class="text-muted">Add image to display on homepage.</p>
                            <div class="text-center">
                                <div class="position-relative d-inline-block">
                                    <div class="position-absolute top-100 start-100 translate-middle">
                                        <label for="homepage-image-input" class="mb-0" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                    <i class="ri-image-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                        <input class="form-control d-none" name="homepage_img" value="" id="homepage-image-input" type="file" accept="image/png, image/gif, image/jpeg, image/webp">
                                    </div>
                                    <div class="avatar-lg">
                                        <div class="avatar-title bg-light rounded">
                                            @if ($category->getMedia('homepage')->isNotEmpty())
                                                @foreach ($category->getMedia('homepage') as $media)
                                                    <img src="{{ $media->getUrl('homepage') }}"
                                                         alt="{{ $media->name }}"
                                                         id="homepage-img" class="avatar-md h-auto"
                                                    />
                                                @endforeach
                                            @else
                                                <img alt="" src="" id="homepage-img" class="avatar-md h-auto" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('homepage_img')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                        <div class="card">
                            <div class="card-header">
                                Meta Data
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="meta-title-input">Meta title</label>
                                    <input type="text" name="meta_title" value="{{old('meta_title',$category->meta_title)}}" class="form-control @error('meta_title') is-invalid @enderror" placeholder="Enter meta title" id="meta-title-input">
                                    @error('meta_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="meta-keywords-input">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" value="{{old('meta_keywords',$category->meta_keywords)}}" class="form-control @error('meta_keywords') is-invalid @enderror" placeholder="Enter meta keywords" id="meta-keywords-input">
                                    @error('meta_keywords')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label" for="meta-description-input">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" name="meta_description" id="meta-description-input" placeholder="Enter meta description" rows="3">{{old('meta_description',$category->meta_description)}}</textarea>
                                @error('meta_description')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Category FAQs</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <div id="formRepeater">
                                    @if($category->faqs)
                                        @foreach(json_decode($category->faqs, true) as $key => $faq)
                                            <div class="border-bottom-dashed mb-3">
                                                <p class="text-primary">FAQ: {{$key}}</p>
                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <label for="question-{{$key}}" class="form-label">Question</label>
                                                        <input class="form-control" type="text" name="faqs[{{$key}}][question]" id="question-{{$key}}" placeholder="Question" value="{{ $faq['question'] }}" />
                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        <label for="answer-{{$key}}" class="form-label">Answer</label>
                                                        <input class="form-control" type="text" name="faqs[{{$key}}][answer]" id="answer-{{$key}}" placeholder="Answer" value="{{ $faq['answer'] }}" />
                                                    </div>
                                                    @if($key!== 1)
                                                        <div class="col-md-2 mt-2">
                                                            <a href="javascript:void(0);" onclick="removeRow({{$key}})" id="removeButton-{{$key}}"
                                                               class="  btn btn-danger mt-4" title="Add field"> Remove</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <a href="javascript:void(0);" id="addButton" class="btn btn-success mt-3" title="Add field"> Add FAQ</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-3" style="text-align:end">
                    <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light">
                        Update
                    </button>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Publish</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="choices-publish-status-input" class="form-label">Status</label>
                            <select name="status" class="form-select" id="choices-publish-status-input" data-choices data-choices-search-false>
                                <option value="1" {{$category->status == 1 ? 'selected' : ''}} >Enable</option>
                                <option value="0" {{$category->status == 0 ? 'selected' : ''}}>Disable</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

@endsection
@push('scripts')
    <!-- ckeditor -->
{{--    <script src="{{asset('backend/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js')}}"></script>--}}

    <!-- dropzone js -->
    <script src="{{asset('backend/assets/libs/dropzone/dropzone-min.js')}}"></script>

    <script src="{{asset('backend/assets/js/pages/ecommerce-product-create.init.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('#parent_id').select2({
                placeholder: "Select category",
                allowClear: true,
                multiple: false,
            });

            // Toggle homepage image section based on homepage_category_banner checkbox
            function toggleHomepageImageSection() {
                if ($('#homepage_category_banner').is(':checked')) {
                    $('#homepage_image_section').show();
                } else {
                    $('#homepage_image_section').hide();
                }
            }

            // Initial check
            toggleHomepageImageSection();

            // Add event listener for checkbox change
            $('#homepage_category_banner').change(function() {
                toggleHomepageImageSection();
            });

            // Product image preview
            $("#product-image-input").change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $("#product-img").attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Homepage image preview
            $("#homepage-image-input").change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $("#homepage-img").attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

    </script>
    <script>
        @php
            $faqs = json_decode($category->faqs, true);
            $repeaterCount = is_array($faqs) ? count($faqs) : 1;
        @endphp
        //repeated form
        let repeaterCount = {{$repeaterCount}};
        const formRepeater = document.getElementById('formRepeater');
        const addButton = document.getElementById('addButton');

        addButton.addEventListener('click', () => {
            repeaterCount++;
            const newRow = document.createElement('div');
            newRow.classList.add('border-bottom-dashed', 'mb-3');
            newRow.innerHTML = `
        <p class="text-primary">FAQ: ${repeaterCount}</p>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="question-${repeaterCount}" class="form-label">Question</label>
                <input class="form-control" type="text" name="faqs[${repeaterCount}][question]" id="question-${repeaterCount}" placeholder="Question" />
            </div>
            <div class="col-md-12 mt-2">
                <label for="answer-${repeaterCount}" class="form-label">Answer</label>
                <input class="form-control" type="text" name="faqs[${repeaterCount}][answer]" id="answer-${repeaterCount}" placeholder="Answer" />
            </div>
         <div class="col-md-4">
        <a href="javascript:void(0);" onclick="removeRow(${repeaterCount})" id="removeButton-${repeaterCount}"
           class="  btn btn-danger mt-4" title="Add field"> Remove</a>
    </div>
        </div>
    `;
            formRepeater.appendChild(newRow);
        });

        function removeRow(repeaterCount) {
            if (repeaterCount > 0) {
                if (confirm('Are you sure you want to delete this element?')) {
                    formRepeater.removeChild(formRepeater.lastElementChild);
                    repeaterCount--;
                }
            }
        }

    </script>

@endpush
