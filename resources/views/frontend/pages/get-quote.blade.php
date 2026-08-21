@extends('frontend.layouts.app')
@section('title')
    Get Instant Custom Quote - {{config('app.name')}}
@endsection
@section('description')
    Get instant custom quote for your packaging needs. Fill out our form and receive a personalized quote for your custom boxes.
@endsection

@push('styles')
@endpush

@section('content')
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a title="Home Breadcrumb" href="{{ route('index') }}" rel="nofollow">Home</a>
                <span></span> Proceed to Checkout
            </div>
        </div>
    </div>
    
    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="single-page pr-30">
                        <!-- <div class="single-header style-2">
                            <h2>Proceed to Checkout</h2>
                            <div class="entry-meta meta-1 font-xs mt-15 mb-15">
                                <span class="post-by">By <a href="javascript:void(0)">{{config('app.name')}}</a></span>
                            
                            </div>
                        </div> -->
                        <div class="single-content">
                            @livewire('get-checkout-form', ['item_id' => request()->query('item_id')])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
