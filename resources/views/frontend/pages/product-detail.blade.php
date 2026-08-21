@extends('frontend.layouts.app')

@section('schema')
{{--  WebPage Schema  --}}
{!! $data['schemaWebPage']  !!}
{{--  BreadCrumb Schema  --}}
{!! $data['breadcrumbSchema']  !!}
{{--  Faq Schema  --}}
{!! $data['faqSchema']   !!}
{{--  Product Schema  --}}
{!! $product->schema_org->toScript() !!}
@endsection

@push('styles')
    <style>.text-span,.text-span2{font-size:18px;font-weight:700}.member-info-content span{font-size:16px}</style>
@endpush
@section('content')
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                {!! $productBreadcrumb !!}
            </div>
        </div>
    </div>
    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="title-detail">{{ $product->product_title }}</h1>
                    <div class="bt-1 border-color-1 mt-15 mb-15"></div>
                    <div class="short-desc">
                        {!! $product->product_detail !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-detail accordion-detail">
                        <div class="row mb-20">
                            <div class="col-md-5 col-sm-12 col-xs-12">
                                <div class="detail-gallery">
                                    <span class="zoom-icon"><i class="fi-rs-search"></i></span>
                                    <!-- MAIN SLIDES -->
                                    <div class="product-image-slider">
                                        @if ($product->getMedia('product_gallery')->isNotEmpty())
                                            @foreach ($product->getMedia('product_gallery') as $media)
                                                <figure class="border-radius-10">
                                                    <img  title="{{ $product->product_title }}" src="{{ route('media.show', [$media->id, 'l', $media->file_name]) }}"
                                                          alt="{{ $media->name }}" />
                                                </figure>
                                            @endforeach
                                        @else
                                            <p>No images available for this product.</p>
                                        @endif
                                    </div>
                                    <!-- THUMBNAILS -->
                                    <div class="slider-nav-thumbnails pl-15 pr-15">
                                        @if ($product->getMedia('product_gallery')->isNotEmpty())
                                            @foreach ($product->getMedia('product_gallery') as $media)
                                                <div><img title="{{ $product->product_title }}" src="{{ route('media.show', [$media->id, 'l', $media->file_name]) }}"
                                                          alt="{{ $media->name }}" /></div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="detail-info">
                                 <livewire:get-quote-for-product-page/>
                                </div>
                            </div>
                        </div>

                        @include('frontend.includes.process-icons')
                        @include('frontend.includes.brands-icons')
                        @include('frontend.includes.reviews')
                        <section id="services" class="services mb-30">
                            <div class="container">
                                <div class="section-title style-1 mb-30">
                                    <span class="span-heading pb-3">Stock Type &amp; Thickness</span>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            <div class="col-xl-3 col-lg-4 col-md-6">
                                                <div class="member">
                                                    <img loading="lazy" width="292" height="235" src="{{asset('frontend/product-cards/cardboard.jpg')}}" class="img-fluid" alt="Cardboard Material" title="Cardboard - Eco-friendly packaging material">
                                                    <div class="member-info">
                                                        <div class="member-info-content py-4">
                                                            <span class="text-span">Cardboard</span> <br>
                                                            <span>We use eco-friendly cardboard materials designed to minimize environmental impact and reduce waste.
                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6">
                                                <div class="member">
                                                    <img loading="lazy" width="292" height="235" src="{{asset('frontend/product-cards/kraft.jpg')}}" class="img-fluid" alt="Kraft Material" title="Kraft - Sturdy and durable packaging">
                                                    <div class="member-info">
                                                        <div class="member-info-content py-4">
                                                            <span class="text-span">Kraft</span> <br>
                                                            <span>Kraft paper is a sturdy, durable, and dependable material used across multiple industries.
                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6">
                                                <div class="member">
                                                    <img loading="lazy" width="292" height="235" src="{{asset('frontend/product-cards/rigid.jpg')}}" class="img-fluid" alt="Rigid Material" title="Rigid - Maximum durability and security">
                                                    <div class="member-info">
                                                        <div class="member-info-content py-4">
                                                            <span class="text-span">Rigid</span> <br>
                                                            <span>Rigid material is highly protective, cost-effective, and recyclable, offering maximum durability and security.
                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6">
                                                <div class="member">
                                                    <img loading="lazy" width="292" height="235" src="{{asset('frontend/product-cards/corrugated.jpg')}}" class="img-fluid" alt="Corrugated Material" title="Corrugated - Customizable and sustainable packaging">
                                                    <div class="member-info">
                                                        <div class="member-info-content py-4">
                                                            <span class="text-span">Corrugated</span> <br>
                                                            <span>Corrugated material is customizable, cost-effective, and sustainable, making it an excellent choice for branding and shipping.
                                                         </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="services" class="f-services pt-0 mb-40">
                            <div class="container">
                                <div class="section-title style-1 mb-30">
                                    <span class="stection-two-heading">Finishing Assortment</span>
                                </div>
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/foil-stamping.jpg')}}" class="img-fluid" alt="Foil Stamping Finish" title="Premium Foil Stamping for Sophisticated Packaging">
                                            <div class="member-info">
                                                <div class="member-info-content py-3 text-center pb-3">
                                                    <span class="text-span2">Foil Stamping</span> <br>
                                                    <span>Enhance your product packaging with our premium foil stamping for a sophisticated touch.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/spot-uv.jpg')}}" class="img-fluid" alt="Spot UV Coating" title="High-Gloss UV Coating for Enhanced Shine">
                                            <div class="member-info">
                                                <div class="member-info-content py-3 text-center pb-3">
                                                    <span class="text-span2">Spot UV</span> <br>
                                                    <span>Our high-gloss UV coating enhances shine and provides a sleek, protective finish to your printed materials.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/gloss-lamination.jpg')}}" class="img-fluid" alt="Gloss Lamination" title="Eye-Catching Glossy Finish">
                                            <div class="member-info">
                                                <div class="member-info-content py-3 text-center pb-3">
                                                    <span class="text-span2">Gloss</span> <br>
                                                    <span>Our glossy finish adds a sleek, eye-catching shine to your packaging.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/holographic-foiling.jpg')}}" class="img-fluid" alt="Holographic Foiling Effect" title="Rainbow Effect Holographic Foiling">
                                            <div class="member-info">
                                                <div class="member-info-content py-3 text-center pb-3">
                                                    <span class="text-span2">Holographic Foiling</span> <br>
                                                    <span>Holographic foil refracts light, creating a stunning rainbow effect from different angles.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <div class="member">
                                                <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/embossing.jpg')}}" class="img-fluid" alt="Embossing Technique" title="Detailed Embossing for Elegant Packaging">
                                                <div class="member-info">
                                                    <div class="member-info-content py-3 text-center pb-1">
                                                        <span class="text-span2">Embossing</span> <br>
                                                        <span>Our embossing technique captures the finest details, adding depth and elegance to your packaging.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/debossing.jpg')}}" class="img-fluid" alt="Debossing Technique" title="Refined Debossing Effect">
                                            <div class="member-info">
                                                <div class="member-info-content py-3 text-center pb-1">
                                                    <span class="text-span2">Debossing</span><br>
                                                    <span>Our debossing technique imprints unique graphics into your packaging, creating a refined and tactile effect.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <div class="member">
                                                <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/matte-lamination.jpg')}}" class="img-fluid" alt="Matte Lamination" title="Clean and Refined Matte Finish">
                                                <div class="member-info">
                                                    <div class="member-info-content py-3 text-center pb-1">
                                                        <span class="text-span2">Matte</span><br>
                                                        <span>Our matte finish reduces the visibility of fingerprints and smudges, keeping your packaging looking clean and refined.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <div class="member">
                                                <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/soft-touch-lamination.jpg')}}" class="img-fluid" alt="Soft Touch Lamination" title="Protective Soft-Touch Lamination">
                                                <div class="member-info">
                                                    <div class="member-info-content py-3 text-center pb-3">
                                                        <span class="text-span2">Soft Touch Lamination</span><br>
                                                        <span>We apply a soft-touch lamination that adds an extra layer of protection, preventing toner chipping.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="tab-style3">
                            <ul class="nav nav-tabs text-uppercase">
                                <li class="nav-item">
                                    <a title="Specifications" class="nav-link active" id="Additional-info-tab" data-bs-toggle="tab"
                                       href="#Additional-info">Specifications</a>
                                </li>
                                <li class="nav-item">
                                    <a title="Description" class="nav-link " id="Description-tab" data-bs-toggle="tab"
                                        href="#Description">Description</a>
                                </li>

                                <li class="nav-item">
                                    <a title="FAQs" class="nav-link" id="Reviews-tab" data-bs-toggle="tab" href="#Faqs">FAQs</a>
                                </li>
                            </ul>
                            <div class="tab-content shop_info_tab entry-main-content">
                                <div class="tab-pane fade show active" id="Additional-info">
                                    <table class="table table-striped table-hover ">
                                        <tbody>
                                        <tr>
                                            <th>Box Style</th>
                                            <td>As Mention Above</td>
                                        </tr>
                                        <tr>
                                            <th>Dimension (L + W + H)</th>
                                            <td>All Custom Sizes & Shapes</td>
                                        </tr>
                                        <tr>
                                            <th>Minimum Run
                                                ( For Boxes and Mylar Bags)</th>
                                            <td>100 Boxes - 1000 Mylar Bags ( 500 each design)</td>
                                        </tr>
                                        <tr>
                                            <th>Paper Stock</th>
                                            <td>10pt to 28pt (60lb to 400lb) Eco-Friendly Kraft, E-flute Corrugated, Bux Board, Cardstock</td>
                                        </tr>
                                        <tr>
                                            <th>Printing</th>
                                            <td>No Printing, CMYK, CMYK + 1 PMS color, CMYK + 2 PMS colors</td>
                                        </tr>
                                        <tr>
                                            <th>Finishing</th>
                                            <td>Gloss Lamination, Matte Lamination, Gloss AQ, Gloss UV, Matte UV, Spot UV, Embossing, Foiling</td>
                                        </tr>
                                        <tr>
                                            <th>Included Options</th>
                                            <td>Die Cutting, Gluing, Scored,Perforation</td>
                                        </tr>
                                        <tr>
                                            <th>Additional Options</th>
                                            <td>
                                                Eco-Friendly, Recycled Boxes, Biodegradable</td>
                                        </tr>
                                        <tr>
                                            <th>Proof</th>
                                            <td>Flat View, 3D Mock-up, Physical Sampling (On request)</td>
                                        </tr>
                                        <tr>
                                            <th>Turnaround</th>
                                            <td>
                                                7 - 8 Business Days</td>
                                        </tr>
                                        <tr>
                                            <th>Shipping</th>
                                            <td>FLAT Position</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade description" id="Description">
                                    {!! $product->product_description !!}
                                </div>

                                <div class="tab-pane fade " id="Faqs">
                                    @if($productFaqs)
                                        <div class="faq-content mb-20 container">
                                            <div class="accordion" id="faqAccordion">
                                                @php
                                                $i =1;
                                                @endphp
                                                @foreach($productFaqs as $key => $faq)
                                                    <div class="accordion-item">
                                                        <h4 class="accordion-header" id="heading{{ $key }}">
                                                            <button class="accordion-button {{ $key === 0 ? '' : 'collapsed' }}" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapse{{ $key }}"
                                                                    aria-expanded="{{ $key === 0 ? 'true' : 'false' }}"
                                                                    aria-controls="collapse{{ $key }}">
                                                                Q{{$i++}}. {{ $faq['question'] }}
                                                            </button>
                                                        </h4>
                                                        <div id="collapse{{ $key }}" class="accordion-collapse collapse {{ $key === 0 ? 'show' : '' }}"
                                                             aria-labelledby="heading{{ $key }}"
                                                             data-bs-parent="#faqAccordion">
                                                            <div class="accordion-body">
                                                                {{ $faq['answer'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                      

                        @if($relatedProducts)
                            <div class="row mt-60">
                                <div class="col-12">
                                    <h3 class="section-title style-1 mb-30">Related products</h3>
                                </div>
                                <div class="col-12">
                                    <div class="row related-products">
                                        @foreach($relatedProducts as $related_product)
                                            <div class="col-lg-3 col-md-4 col-12 col-sm-6 mb-4">
                                                <div class="product-cart-wrap small hover-up">
                                                    <div class="product-img-action-wrap">
                                                        <div class="product-img product-img-zoom">
                                                            <a title="Action link for {{$related_product->product_name}} detail page" href="{{route('page',$related_product->slug)}}" tabindex="0">
                                                                @if ($related_product->getMedia('product_front_image')->isNotEmpty() || $related_product->getMedia('product_back_image')->isNotEmpty())
                                                                    @foreach ($related_product->getMedia('product_front_image') as $media)
                                                                        <img title="{{$related_product->product_name}}" class="default-img" src="{{ route('media.show', [$media->id, 'l', $media->file_name]) }}"
                                                                             alt="{{ $media->name }}">
                                                                    @endforeach
                                                                        @foreach ($related_product->getMedia('product_back_image') as $media)
                                                                        <img title="{{$related_product->product_name}}" class="hover-img" src="{{ route('media.show', [$media->id, 'l', $media->file_name]) }}"
                                                                             alt="{{ $media->name }}">
                                                                        @endforeach
                                                                    @endif
                                                            </a>
                                                        </div>
{{--                                                        <div class="product-action-1">--}}
{{--                                                            <a aria-label="Quick view" class="action-btn small hover-up"--}}
{{--                                                               data-bs-toggle="modal" data-bs-target="#quickViewModal"><i--}}
{{--                                                                    class="fi-rs-search"></i></a>--}}
{{--                                                        </div>--}}
                                                        {{--  <div class="product-badges product-badges-position product-badges-mrg">
                                                              <span class="hot">Hot</span>
                                                          </div>--}}
                                                    </div>
                                                    <div class="product-content-wrap">
                                                        <span><a title="Action link for {{$related_product->product_name}} detail page" href="{{route('page',$related_product->slug)}}" tabindex="0">{{$related_product->product_name}}</a></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- <div class="banner-img banner-big wow fadeIn f-none animated mt-50">
                            <img  title="Blow banner"  class="border-radius-10" src="{{asset('frontend/assets/imgs/banner/blow-banner.jpg')}}" alt="Blow banner">
                            <div class="banner-text">
{{--                                <h4 class="mb-15 mt-40">Repair Services</h4>--}}
{{--                                <h2 class="fw-600 mb-20">We're an Apple <br>Authorised Service Provider</h2>--}}
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
