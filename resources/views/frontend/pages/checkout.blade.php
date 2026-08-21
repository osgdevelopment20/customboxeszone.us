@extends('frontend.layouts.app')

@section('content')
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                {!! $CheckoutBreadcrumb !!}
            </div>
        </div>
    </div>

    @livewire('checkout-page')
@endsection
