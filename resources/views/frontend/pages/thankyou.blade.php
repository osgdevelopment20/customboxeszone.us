@extends('frontend.layouts.app')
@section('title')
    Thank You
@endsection

@section('content')
    @php
        $confirmation = session('confirmation', []);
        $heading = $confirmation['heading'] ?? 'Thank you for getting in touch';
        $message = $confirmation['message'] ?? 'We have received your request. One of our packaging specialists will be in touch shortly.';
        $reference = $confirmation['reference'] ?? null;
    @endphp

    <div class="cbz-page">
        <div class="container">
            <div class="cbz-thanks">
                <div class="cbz-thanks__badge" aria-hidden="true">
                    <i class="fi-rs-check"></i>
                </div>

                <h1 class="cbz-thanks__title">{{ $heading }}</h1>
                <p class="cbz-thanks__text">{{ $message }}</p>

                @if ($reference)
                    <div class="cbz-thanks__ref">
                        <span class="cbz-thanks__ref-label">Your reference</span>
                        <strong class="cbz-thanks__ref-value">{{ $reference }}</strong>
                        <span class="cbz-thanks__ref-hint">Quote this number when you contact us.</span>
                    </div>
                @endif

                <ol class="cbz-nextsteps" aria-label="What happens next">
                    <li>
                        <span class="cbz-nextsteps__num">1</span>
                        <span class="cbz-nextsteps__body">
                            <strong>We review your specification</strong>
                            Our team checks your sizes, quantities and artwork.
                        </span>
                    </li>
                    <li>
                        <span class="cbz-nextsteps__num">2</span>
                        <span class="cbz-nextsteps__body">
                            <strong>You receive a quote</strong>
                            A full breakdown lands in your inbox within 24 hours.
                        </span>
                    </li>
                    <li>
                        <span class="cbz-nextsteps__num">3</span>
                        <span class="cbz-nextsteps__body">
                            <strong>We produce and ship</strong>
                            Approve the proof and we'll start production right away.
                        </span>
                    </li>
                </ol>

                <div class="cbz-actions cbz-actions--center">
                    <a href="{{ route('allproducts') }}" class="cbz-btn cbz-btn--primary">
                        <i class="fi-rs-box-alt" aria-hidden="true"></i> Continue Shopping
                    </a>
                    <a href="{{ route('index') }}" class="cbz-btn cbz-btn--outline">
                        <i class="fi-rs-home" aria-hidden="true"></i> Back to Home
                    </a>
                </div>

                <p class="cbz-thanks__contact">
                    Need it sooner? Call
                    <a href="tel:{{ sys_settings('phone') }}">{{ sys_settings('phone') }}</a>
                    or email
                    <a href="mailto:{{ sys_settings('email') }}">{{ sys_settings('email') }}</a>
                </p>
            </div>
        </div>
    </div>
@endsection
