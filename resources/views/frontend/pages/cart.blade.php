@extends('frontend.layouts.app')

@section('content')
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                {!! $CartBreadcrumb !!}
            </div>
        </div>
    </div>

    @livewire('cart-page')
@endsection
