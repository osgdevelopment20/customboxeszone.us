@extends('frontend.layouts.app')
@section('title')
    {{sys_settings('contact_meta_title')}}
@endsection
@section('description')
    {{sys_settings('contact_meta_description')}}
@endsection
    @push('styles')
    @endpush
    @section('content')
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a title="Home Breadcrumb" href="{{ route('index') }}" rel="nofollow">Home</a>
                    <span></span>   Contact Us
                </div>
            </div>
        </div>
        <section class="mt-50 mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="single-page pr-30">
                            <div class="single-header style-2">
                                <h2>  Contact Us</h2>
                                <div class="entry-meta meta-1 font-xs mt-15 mb-15">
                                    <span class="post-by">By <a href="javascript:void(0)">{{config('app.name')}}</a></span>
                                    <span class="time-reading has-dot">2 mins read</span>
                                </div>
                            </div>
                            <div class="single-content">
                               {!! merchant_policy_content('contact_us', '
                                   <p>Questions about a custom packaging quote, artwork, production, shipping, or an existing order? Contact our support team and we will help you confirm the details before you place an order.</p>
                                   <p>Email: <a href="mailto:' . merchant_contact_email() . '">' . merchant_contact_email() . '</a></p>
                                   <p>Phone: <a href="tel:' . merchant_contact_phone() . '">' . merchant_contact_phone() . '</a></p>
                                   <p>Address: ' . sys_settings('address', 'East Meadow, NY 11554, USA') . '</p>
                               ') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('frontend.includes.merchant-transparency')
    @endsection
