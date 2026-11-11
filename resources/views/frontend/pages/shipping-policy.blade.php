@extends('frontend.layouts.app')


@section('content')
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('index') }}" rel="nofollow">Home</a>
                <span></span> Shipping Policy
            </div>
        </div>
    </div>

    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="single-page pr-30">
                        <div class="single-header style-2">
                            <h1>Shipping Policy</h1>
                            <div class="entry-meta meta-1 font-xs mt-15 mb-15">
                                <span class="post-by">By <a href="javascript:void(0)">{{ config('app.name') }}</a></span>
                            </div>
                        </div>
                        <div class="single-content">
                            {!! merchant_policy_content('shipping_policy', '
                                <p>We ship custom packaging orders across the United States. Because every order is custom manufactured, shipping details are confirmed with the written quote before payment is collected.</p>
                                <h2>Production and Handling Time</h2>
                                <p>Most standard custom box orders move into production after artwork approval and payment confirmation. Estimated production time is usually 7 to 10 business days unless a different timeline is stated in your written quote.</p>
                                <h2>Delivery Time</h2>
                                <p>Transit time depends on destination, order size, carrier availability, and freight requirements. We share estimated delivery timing before the order is finalized.</p>
                                <h2>Shipping Cost</h2>
                                <p>Shipping may be free, included, or separately quoted depending on box type, quantity, package weight, and delivery location. Any shipping cost is disclosed before payment.</p>
                                <h2>Order Tracking</h2>
                                <p>When tracking is available, we provide tracking details after the order ships.</p>
                                <h2>Damaged or Missing Shipments</h2>
                                <p>Please contact us as soon as possible at ' . merchant_contact_email() . ' or ' . merchant_contact_phone() . ' if your shipment arrives damaged, incomplete, or delayed. Keep packaging and photos so we can help investigate with the carrier.</p>
                            ') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.includes.merchant-transparency')
@endsection
