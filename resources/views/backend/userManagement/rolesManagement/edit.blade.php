@extends('backend.layouts.app')

@section('contents')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Role</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('dashboard.home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('dashboard.role.index')}}">Roles</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Role: {{$role->name}}</h5>
            </div>
            <div class="card-body">
                <form action="{{route('dashboard.role.update', $role)}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Role Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{old('name', $role->name)}}" required>
                        @error('name')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row">
                            @foreach($permissions as $key => $permission)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permission[]" value="{{$key}}" id="permission_{{$key}}" {{$role->hasPermissionTo($key) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="permission_{{$key}}">
                                            {{$permission}}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('permission')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <a href="{{route('dashboard.role.index')}}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection