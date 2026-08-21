@extends('frontend.layouts.app')

@section('content')
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                {!! $AllProductBreadcrumb !!}
            </div>
        </div>
    </div>

    @livewire('all-products-page')
@endsection
