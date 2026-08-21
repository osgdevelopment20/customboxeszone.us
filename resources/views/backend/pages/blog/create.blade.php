@extends('backend.layouts.app')
@section('title')
    Add Blog
@endsection
@push('styles')
    <!-- Plugins css -->
    <link href="{{ asset('backend/assets/libs/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('contents')
    <form action="{{ route('dashboard.blog.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Add Blog</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <label for="title" class="form-label">Blog Title</label> <span
                                        class="text-danger">*</span>
                                    <input type="text" id="title"
                                        class="form-control  @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" name="title" placeholder="Blog Title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="author_name" class="form-label">Author Name</label> <span
                                        class="text-danger">*</span>
                                    <input type="text" id="author_name"
                                        class="form-control  @error('author_name') is-invalid @enderror"
                                        value="{{ old('author_name') }}" name="author_name" placeholder="Author Name">
                                    @error('author_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Blog Gallery</h5>
                    </div>
                    <div class="card-body ">
                        <div class="mb-4">
                            <h5 class="fs-14 mb-1">Blog Image<span class="text-danger">*</span></h5>
                            <p class="text-muted">Add Blog thumbnail.</p>
                            <input name="blog_image" class="form-control    @error('blog_image') is-invalid @enderror"
                                value="{{ old('blog_image') }}" type="file" id="formFile2">
                            @error('blog_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>
                        {{-- <div class="mb-4 @error('blog_detail_image') is-invalid @enderror">
                            <h5 class="fs-14 mb-1">Blog Detail Image<span class="text-danger">*</span></h5>
                            <p class="text-muted">Add Blog Detail page image.</p>
                            <input name="blog_detail_image"
                                class="form-control    @error('blog_detail_image') is-invalid @enderror"
                                value="{{ old('blog_detail_image') }}" type="file" id="blog_detail_image">
                            @error('blog_detail_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div> --}}
                        <div class="col-12 mt-3">
                            <label for="blog_img_title" class="form-label">Image Title Text</label> <span class="text-danger">*</span>
                            <input type="text"  id="blog_img_title"
                                   class="form-control  @error('blog_img_title') is-invalid @enderror"
                                   value="{{old('blog_img_title')}}" name="blog_img_title"
                                   placeholder="Image Title Text">
                            @error('blog_img_title')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    @error('blog_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    {{-- @error('blog_detail_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror --}}
                </div>
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#addproduct-general-info"
                                    role="tab">
                                    Blog Content <span class="text-danger">*</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#addproduct-metadata" role="tab">
                                    Meta Data<span class="text-danger">*</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- end card header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="addproduct-general-info" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <x-text-editor name="blog_ldis" :value="old('blog_ldis')" />
                                        @error('blog_ldis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <!-- end tab-pane -->

                            <div class="tab-pane" id="addproduct-metadata" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="meta-title-input">Meta title</label><span
                                                class="text-danger">*</span>
                                            <input type="text" value="{{ old('meta_title') }}" name="meta_title"
                                                class="form-control @error('meta_title') is-invalid @enderror"
                                                placeholder="Enter meta title" id="meta-title-input">
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- end col -->

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="meta-keywords-input">Meta Keywords</label>
                                            <input type="text" value="{{ old('meta_keywords') }}"
                                                name="meta_keywords"
                                                class="form-control @error('meta_keywords') is-invalid @enderror"
                                                placeholder="Enter meta keywords" id="meta-keywords-input">
                                            @error('meta_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                                <!-- end row -->

                                <div>
                                    <label class="form-label" for="meta-description-input">Meta Description</label><span
                                        class="text-danger">*</span>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" name="meta_description"
                                        id="meta-description-input" placeholder="Enter meta description" rows="3">{{ old('meta_description') }}</textarea>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- end tab pane -->
                        </div>
                        <!-- end tab content -->
                    </div>
                    <!-- end card body -->
                </div>
                <div class="m-3" style="text-align:end">
                    <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light">
                        Save
                    </button>
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
                            <select name="status" class="form-select" id="choices-publish-status-input" data-choices
                                data-choices-search-false>
                                <option value="1" selected>Enable</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')

    <!-- dropzone js -->
    <script src="{{ asset('backend/assets/libs/dropzone/dropzone-min.js') }}"></script>

    <script src="{{ asset('backend/assets/js/pages/ecommerce-product-create.init.js') }}"></script>

@endpush
