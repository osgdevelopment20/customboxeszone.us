@extends('backend.layouts.app')
@section('title')
   Add Campaign
@endsection
@section('contents')
    <div class="row">
        <div class="col-xl-12">

            <div class="card widget widget-stats">
                <div class="m-3 border-bottom " style="text-align:end">
                    <a href="{{route('dashboard.campaign.create')}}"
                       class="btn rounded-pill btn-primary waves-effect waves-light mb-2"> <i
                            class="ri-add-fill ri-1x"></i>Add Campaign
                    </a>
                </div>
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
