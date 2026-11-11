@extends('frontend.layouts.app')
@section('title')
    {{sys_settings('policy_meta_title')}}
@endsection
@section('description')
    {{sys_settings('policy_meta_title')}}
@endsection
    @push('styles')
    @endpush
    @section('content')
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a title="Home Breadcrumb" href="{{ route('index') }}" rel="nofollow">Home</a>
                    <span></span> Privacy policy
                </div>
            </div>
        </div>
        <section class="mt-50 mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="single-page pr-30">
                            <div class="single-header style-2">
                                <h2>Privacy Policy</h2>
                                <div class="entry-meta meta-1 font-xs mt-15 mb-15">
                                    <span class="post-by">By <a href="javascript:void(0)">{{config('app.name')}}</a></span>
                                    <span class="time-reading has-dot">8 mins read</span>
                                </div>
                            </div>
                            <div class="single-content">
                                {!! merchant_policy_content('privacy_policy') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('frontend.includes.merchant-transparency')
    @endsection
