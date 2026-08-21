@extends('backend.layouts.app')

@section('contents')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Create Permission</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('dashboard.home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('dashboard.permission.index')}}">Permissions</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Create New Permission</h5>
            </div>
            <div class="card-body">
                <form action="{{route('dashboard.permission.store')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Permission Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{old('name')}}" required>
                        @error('name')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                        <small class="text-muted">Use kebab-case format (e.g., manage-users)</small>
                    </div>

                    <div class="text-end">
                        <a href="{{route('dashboard.permission.index')}}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Permission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection