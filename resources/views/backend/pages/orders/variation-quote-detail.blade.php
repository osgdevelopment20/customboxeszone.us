@extends('backend.layouts.app')
@section('title')
    Variation Quote Details
@endsection
@section('contents')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0">Variation Quote Details</h5>
                    <div class="flex-shrink-0">
                        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-secondary btn-sm">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Customer Details -->
                    <div class="col-xl-6 col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0"><i class="ri-user-line me-2"></i>Customer Details</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0 vstack gap-3">
                                    <li>
                                        <strong>Name:</strong><br>
                                        <i class="ri-user-2-line me-2 align-middle text-muted fs-16"></i>{{ $quote->name }}
                                    </li>
                                    <li>
                                        <strong>Email:</strong><br>
                                        <i class="ri-mail-line me-2 align-middle text-muted fs-16"></i>
                                        <a href="mailto:{{ $quote->email }}">{{ $quote->email }}</a>
                                    </li>
                                    <li>
                                        <strong>Phone:</strong><br>
                                        <i class="ri-phone-line me-2 align-middle text-muted fs-16"></i>
                                        <a href="tel:{{ $quote->phone }}">{{ $quote->phone }}</a>
                                    </li>
                                    <li>
                                        <strong>Quantity:</strong><br>
                                        <i class="ri-shopping-cart-line me-2 align-middle text-muted fs-16"></i>
                                        {{ $quote->quantity ?? 'N/A' }}
                                    </li>
                                    <li>
                                        <strong>Submitted At:</strong><br>
                                        <i class="ri-calendar-line me-2 align-middle text-muted fs-16"></i>
                                        {{ date_format($quote->created_at, "g:ia \o\n l jS F Y") }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="col-xl-6 col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0"><i class="ri-product-hunt-line me-2"></i>Product Details</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0 vstack gap-3">
                                    <li>
                                        <strong>Product Name:</strong><br>
                                        @if($quote->product)
                                            <a href="{{ url('/' . $quote->product->slug) }}" target="_blank" class="text-primary">
                                                <i class="ri-external-link-line me-1"></i>{{ $quote->product->product_name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Product not found</span>
                                        @endif
                                    </li>
                                    <li>
                                        <strong>Product URL:</strong><br>
                                        @if($quote->product)
                                            <a href="{{ url('/' . $quote->product->slug) }}" target="_blank" class="text-break">
                                                {{ url('/' . $quote->product->slug) }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Variation Details -->
                @if($quote->variation)
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0"><i class="ri-inbox-line me-2"></i>Variation Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <h6 class="text-primary">{{ $quote->variation->title }}</h6>
                                    </div>
                                </div>

                                @if($quote->variation->specifications)
                                <div class="row">
                                    <div class="col-12">
                                        <strong>Specifications:</strong>
                                        <div class="mt-2 p-3 bg-light rounded">
                                            {!! $quote->variation->specifications !!}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="ri-alert-line me-2"></i>Variation details not found
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

@endpush
