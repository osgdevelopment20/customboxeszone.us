@extends('frontend.layouts.app')

    @section('content')
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="{{ route('index') }}" rel="nofollow">Home</a>
                    <span></span>  Refund Policy
                </div>
            </div>
        </div>
        <section class="mt-50 mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="single-page pr-30">
                            <div class="single-header style-2">
                                <h2> Refund Policy</h2>
                                <div class="entry-meta meta-1 font-xs mt-15 mb-15">
                                    <span class="post-by">By <a href="javascript:void(0)">{{config('app.name')}}</a></span>
                                    <span class="time-reading has-dot">10 mins read</span>
                                </div>
                            </div>
                            <div class="single-content">
                                {!! merchant_policy_content('refund_policy', '
                                    <p>Because custom packaging is manufactured to each customer\'s approved specifications, refund and return eligibility depends on the order status and the nature of the issue.</p>
                                    <h2>Before Production</h2>
                                    <p>If production has not started, contact us immediately and we will review whether the order can be changed or cancelled.</p>
                                    <h2>After Production</h2>
                                    <p>Custom printed or custom sized packaging cannot usually be returned for a preference change after production has started or the order has shipped.</p>
                                    <h2>Damaged, Defective, or Incorrect Orders</h2>
                                    <p>If your order arrives damaged, defective, or materially different from the approved proof, contact us within 3 business days with your order details and photos. We will review the issue and may offer a replacement, remake, credit, or refund depending on the circumstances.</p>
                                    <h2>How To Contact Us</h2>
                                    <p>Email ' . merchant_contact_email() . ' or call ' . merchant_contact_phone() . ' for refund or return help.</p>
                                ') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('frontend.includes.merchant-transparency')
    @endsection
