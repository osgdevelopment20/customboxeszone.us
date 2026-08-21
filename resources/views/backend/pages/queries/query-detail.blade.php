@extends('backend.layouts.app')
@section('title')
    Query Details
@endsection
@section('contents')
<div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title flex-grow-1 mb-0">Query Details</h5>
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
                                        @if($order->product_name)
                                            <th scope="col">Product Name</th>
                                        @endif
                                        @if($order->length)
                                            <th scope="col">Length</th>
                                        @endif
                                        @if($order->width)
                                            <th scope="col">Width</th>
                                        @endif
                                        @if($order->height)
                                            <th scope="col">Height</th>
                                        @endif
                                        @if($order->quantity)
                                            <th scope="col">Quantity</th>
                                        @endif
                                        @if($order->quantity2)
                                            <th scope="col">Quantity 2</th>
                                        @endif
                                        @if($order->quantity3)
                                            <th scope="col">Quantity 3</th>
                                        @endif
                                        @if($order->units)
                                            <th scope="col">Units</th>
                                        @endif
                                        @if($order->stock)
                                            <th scope="col">Stock</th>
                                        @endif
                                            @if($order->colors)
                                                <th scope="col">Colors</th>
                                            @endif
                                        @if($order->box_style)
                                            <th scope="col">Box Style</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @if($order->product_name)
                                            <td>{{$order->product_name}}</td>
                                        @endif
                                        @if($order->length)
                                            <td>{{$order->length}}</td>
                                        @endif
                                        @if($order->width)
                                            <td>{{$order->width}}</td>
                                        @endif
                                        @if($order->height)
                                            <td>{{$order->height}}</td>
                                        @endif
                                        @if($order->quantity)
                                            <td>{{$order->quantity}}</td>
                                        @endif
                                        @if($order->quantity2)
                                            <td>{{$order->quantity2}}</td>
                                        @endif
                                        @if($order->quantity3)
                                            <td>{{$order->quantity3}}</td>
                                        @endif
                                        @if($order->units)
                                            <td>{{$order->units}}</td>
                                        @endif
                                        @if($order->stock)
                                            <td>{{$order->stock}}</td>
                                        @endif
                                        @if($order->colors)
                                            <td>{{$order->colors}}</td>
                                        @endif
                                        @if($order->box_style)
                                            <td>{{$order->box_style}}</td>
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
                                        <h5 class="card-title flex-grow-1 mb-0">Query Details</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0 vstack gap-3">
                                        <li><i class="ri-luggage-cart-fill me-2 align-middle text-muted fs-16"></i>
                                         {{$order->source}}
                                        </li>
                                        @if($order->referrer_page)
                                        <li><i class="ri-link me-2 align-middle text-muted fs-16"></i>
                                            <strong>Referrer Page:</strong> <a href="{{$order->referrer_page}}" target="_blank">{{$order->referrer_page}}</a>
                                        </li>
                                        @endif
                                    </ul>
                                    @if($order->art_design)
                                        <h6 class="mt-3">Art Work</h6>
                                        <ul>
                                           @foreach(json_decode($order->art_design) as $art)
                                                <li><a target="_blank" href="{{config('app.url').'/'.'storage/'.$art}}">{{config('app.url').'/'.'storage/'.$art}}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    @if($order->other_products)
                                        <h6 class="mt-3">Other Products</h6>
                                        <ul>
                                        @foreach(json_decode($order->other_products) as $pro)
                                          <li>{{$pro}}</li>
                                        @endforeach
                                        </ul>
                                    @endif
                                    @if($order->notes)
                                        <h6 class="mt-3">Notes</h6>
                                        <p>{{$order->notes}}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
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
                                        <li><strong>IP Address: </strong> {{$order->ip_address}}</li>
                                        <li><strong>City:</strong> {{$order->city}}</li>
                                        <li><strong>Region:</strong> {{$order->region}}</li>
                                        <li><strong>Country:</strong> {{$order->country}}</li>
                                        <li><strong>Location:</strong> {{$order->location}}</li>
                                        <li><strong>Postal:</strong> {{$order->postal}}</li>
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
