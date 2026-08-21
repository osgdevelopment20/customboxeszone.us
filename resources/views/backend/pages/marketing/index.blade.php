@extends('backend.layouts.app')
@section('title')
   Add Contacts
@endsection
@section('contents')
    <div class="col-xl-6">
        <form action="{{route('dashboard.contacts.store')}}" method="post" >
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Contacts</h4>
                </div>
                <div class="card-body">
                    <div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Name</label> <span class="text-danger">*</span>
                                <input type="text"  id="name"
                                       class="form-control  @error('name') is-invalid @enderror"
                                       value="{{old('name')}}" name="name"
                                       placeholder="Name">
                                @error('name')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="email" class="form-label">Email</label> <span class="text-danger">*</span>
                                <input type="email"  id="email"
                                       class="form-control  @error('email') is-invalid @enderror"
                                       value="{{old('email')}}" name="email"
                                       placeholder="Email">
                                @error('email')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="phone" class="form-label">Phone</label> <span class="text-danger">*</span>
                                <input type="text"  id="phone"
                                       class="form-control  @error('phone') is-invalid @enderror"
                                       value="{{old('phone')}}" name="phone"
                                       placeholder="Phone">
                                @error('email')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')

@endpush
