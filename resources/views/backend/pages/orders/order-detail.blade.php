@extends('backend.layouts.app')
@section('title')
    Order Details
@endsection
@section('contents')
<div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title flex-grow-1 mb-0">Order</h5>
                                {{--<div class="flex-shrink-0">
                                    <a href="apps-invoices-details.html" class="btn btn-success btn-sm"><i class="ri-download-2-fill align-middle me-1"></i> Invoice</a>
                                </div>--}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-nowrap align-middle table-borderless mb-0">
                                    <thead class="table-light text-muted">
                                    <tr>
                                        @if($order->flag === "Product Detail Page Quote Form")
                                        <th scope="col">Product Name</th>
                                        @else
                                            <th scope="col">Source</th>
                                         @endif
                                        <th scope="col">Length</th>
                                        <th scope="col">Width</th>
                                        <th scope="col">Height</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Units</th>
                                            @if($order->stock)
                                            <th scope="col">Stock</th>
                                            @endif
                                            @if($order->box_style)
                                        <th scope="col">Box Style</th>
                                            @endif
                                            @if($order->notes)
                                        <th scope="col">Notes</th>
                                            @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @if($order->flag === "Product Detail Page Quote Form")
                                        <td>
                                            <a target="_blank" href="{{$order->product_url}}"> {{$order->product_name}}</a>

                                        </td>
                                        @else
                                            <td>
                                                {{$order->flag}}
                                            </td>
                                        @endif
                                        <td>{{$order->length}}</td>
                                        <td>{{$order->width}}</td>
                                        <td>{{$order->height}}</td>
                                        <td>{{$order->quantity}}</td>
                                        <td>{{$order->units}}</td>
                                            @if($order->stock)
                                        <td>{{$order->stock}}</td>
                                            @endif
                                            @if($order->box_style)
                                        <td>{{$order->box_style}}</td>
                                            @endif
                                            @if($order->notes)
                                        <td>{{$order->notes}}</td>
                                                @endif
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex">
                                        <h5 class="card-title flex-grow-1 mb-0">Customer Details</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0 vstack gap-3">
                                        <li><i class="ri-user-2-line me-2 align-middle text-muted fs-16"></i>{{$order->name}}</li>
                                        <li><i class="ri-mail-line me-2 align-middle text-muted fs-16"></i>
                                            <a href="mailto:{{$order->email}}">{{$order->email}}</a>
                                        </li>
                                        <li><i class="ri-phone-line me-2 align-middle text-muted fs-16"></i>{{$order->phone}}</li>
                                        <li><i class="ri-luggage-cart-fill me-2 align-middle text-muted fs-16"></i>{{date_format($order->created_at,"g:ia
                                            \o\n l jS F Y")}}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>



                </div>

            </div>
@endsection
@push('scripts')

@endpush
