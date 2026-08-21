@extends('backend.layouts.app')
@section('title')
   Products
@endsection
@section('contents')
    <div class="row">
        <div class="col-xl-12">

            <div class="card widget widget-stats">
                <div class="m-3 border-bottom " style="text-align:end">
                    <a href="{{route('dashboard.product.create')}}"
                       class="btn rounded-pill btn-primary waves-effect waves-light mb-2"> <i
                            class="ri-add-fill ri-1x"></i>Add Product
                    </a>
                </div>
                                   @include('backend.partials.datatable-status-filter', [
                    'table' => 'product-table',
                    'id' => \App\DataTables\ProductDataTable::STATUS_FILTER_ID,
                ])
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
