@extends('backend.layouts.app')
@section('title')
    Queries
@endsection
@section('contents')
    <div class="row">
        <div class="col-xl-12">

            <div class="card widget widget-stats">
                <div class="card-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
