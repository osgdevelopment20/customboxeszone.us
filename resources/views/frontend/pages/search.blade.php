@extends('frontend.layouts.app')
@section('title')
Search
@endsection
@push('styles')
    <style>.faq-content h1{font-size:18px}.faq-content p{font-size:20px}.card .card-header{background-color:#08817821!important;border-top-left-radius:1rem;border-top-right-radius:1rem}.card{border:1px solid #cce7d0!important;border-radius:1rem!important;text-align:center}.description{height:400px;overflow-y:scroll}.zoomContainer{z-index:99}.accordion-header{background-color:#c1c1c129}.accordion-button{font-weight:600}.accordion-body{font-size:18px}.accordion-button:not(.collapsed){color:#000;background-color:#08817821}.accordion-button:focus{border-color:transparent;box-shadow:0 0 0 .25rem rgba(13,110,253,.25)}.card h2{font-size:18px;font-weight:bold;margin-bottom:10px}.card p{font-size:14px}.text-span,.text-span2{font-size:18px;font-weight:700}.member-info-content span{font-size:16px}</style>
@endpush
@section('content')
    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shop-product-fillter">
                        <div class="totall-product">
                            <p> We found <strong class="text-brand">{{$products->count()}}</strong> items for you!</p>
                        </div>
                    </div>
                    <div class="row product-grid-3">
                        @foreach($products as $product)
                        <div class="col-lg-3 col-md-4">
                            <div class="product-cart-wrap mb-30">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">
                                        <a href="{{route('page',$product->slug)}}">
                                            @if ($product->getMedia('product_front_image')->isNotEmpty())
                                                @foreach($product->getMedia('product_front_image') as $media)
                                                    <img class="default-img" src="{{ $media->getUrl('l') }}" alt="{{ $media->name }}" width="300" height="300" loading="lazy" style="aspect-ratio:1/1;object-fit:cover">
                                                @endforeach
                                            @endif
                                        </a>
                                    </div>

                                </div>
                                <div class="product-content-wrap">
                                    <div class="product-category">
                                       Box By Industry
                                    </div>
                                    <h2><a href="{{route('page',$product->slug)}}">{{$product->product_name}}</a></h2>

                                    <div class="product-action-1 show">
                                        <a aria-label="Check Categories" class="action-btn hover-up" href="{{route('page',$product->slug)}}"><i class="fi-rs-shopping-bag-add"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!--pagination-->
{{--                    <div class="pagination-area mt-15 mb-sm-5 mb-lg-0">--}}
{{--                        <nav aria-label="Page navigation example">--}}
{{--                            <ul class="pagination justify-content-start">--}}
{{--                                <li class="page-item active"><a class="page-link" href="#">01</a></li>--}}
{{--                                <li class="page-item"><a class="page-link" href="#">02</a></li>--}}
{{--                                <li class="page-item"><a class="page-link" href="#">03</a></li>--}}
{{--                                <li class="page-item"><a class="page-link dot" href="#">...</a></li>--}}
{{--                                <li class="page-item"><a class="page-link" href="#">16</a></li>--}}
{{--                                <li class="page-item"><a class="page-link" href="#"><i class="fi-rs-angle-double-small-right"></i></a></li>--}}
{{--                            </ul>--}}
{{--                        </nav>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </section>

@endsection
