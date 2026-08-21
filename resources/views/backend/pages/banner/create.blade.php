@extends('backend.layouts.app')
@section('title')
   Add Banner
@endsection
@push('styles')
    <!-- Plugins css -->
    <link href="{{asset('assets/libs/dropzone/dropzone.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('contents')
    <form  action="{{route('dashboard.banner.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
    <div class="col-lg-8">
    <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Banner</h4>
                </div>
                <div class="card-body">
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Banner Title</label> <span class="text-danger">*</span>
                        <input type="text"  id="name"
                               class="form-control  @error('name') is-invalid @enderror"
                               value="{{old('name')}}" name="name"
                               placeholder="Name">
                        @error('name')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Banner URL</label>
                        <input type="text"  id="banner_url"
                               class="form-control  @error('banner_url') is-invalid @enderror"
                               value="{{old('banner_url')}}" name="banner_url"
                               placeholder="banner Url">
                        @error('name')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch form-switch-custom form-switch-success">
                            <input class="form-check-input" name="status"  type="checkbox" role="switch" id="SwitchCheck11" >
                            <label class="form-check-label" for="SwitchCheck11">Banner Status</label>
                        </div>
                    </div>
                </div>
            </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Category Gallery</h5>
            </div>
            <div class="card-body  @error('banner_img') is-invalid @enderror">
                <div class="mb-4">
                    <h5 class="fs-14 mb-1">Banner Image</h5>
                    <p class="text-muted">Add Banner Image.</p>
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
                                <input class="form-control d-none" name="banner_img" value="" id="product-image-input" type="file" accept="image/png, image/gif, image/jpeg, image/webp">
                            </div>
                            <div class="avatar-lg">
                                <div class="avatar-title bg-light rounded">
                                    <img src="" id="product-img" class="avatar-md h-auto" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @error('banner_img')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
        <div class="m-3" style="text-align:end">
            <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light">
                Save
            </button>
        </div>
    </div>
        </div>
    </form>

@endsection
@push('scripts')
    <script src="{{asset('assets/libs/dropzone/dropzone-min.js')}}"></script>

    <script src="{{asset('assets/js/pages/ecommerce-product-create.init.js')}}"></script>
@endpush

