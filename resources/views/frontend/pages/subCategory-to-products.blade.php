@extends('frontend.layouts.app')
{{--@section('title')--}}
{{--{{$subCategoryToProducts->title}}--}}
{{--@endsection--}}
@section('schema')
    {{--  webpage Schema  --}}
    {!! $schemaWebPage->toScript() !!}
    {{--  BreadCrumb Schema  --}}
    {!! $breadcrumbScript !!}
    {{--  list Schema  --}}
    {!! $itemList->toScript() !!}

@stop
@section('content')
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                {!! $subCategoryToProductsBreadcrumb !!}
            </div>
        </div>
    </div>
    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shop-product-fillter">
                        <h1 class="text-center" style="font-size: 28px!important;">{{$subCategoryToProducts->title}}</h1>
                        <div class="totall-product">
                            <p> We found <strong class="text-brand">{{$subCategoryToProducts->products->count()}}</strong> items for you!</p>
                        </div>
                    </div>
                    <div class="infinite-scroll">
                    <div class="row product-grid-3">
                        @foreach($subCategoryToProducts->products as $product)
                        <div class="col-lg-3 col-md-4">
                            <div class="product-cart-wrap mb-30">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">
                                        <a title="Action link for {{$product->product_name}} detail page" href="{{route('page',$product->slug)}}">
                                            @if ($product->getMedia('product_front_image')->isNotEmpty())
                                                @foreach($product->getMedia('product_front_image') as $media)
                                                    <img title="{{$product->product_title}}" class="hover-img" src="{{ route('media.show', [$media->id, 'l', $media->file_name]) }}" alt="{{ $media->name }}">
                                                @endforeach
                                            @endif
                                            @if ($product->getMedia('product_back_image')->isNotEmpty())
                                                @foreach($product->getMedia('product_back_image') as $media)
                                                    <img title="{{$product->product_title}}" class="default-img" src="{{ route('media.show', [$media->id, 'l', $media->file_name]) }}" alt="{{ $media->name }}">
                                                @endforeach
                                            @endif
                                        </a>
                                    </div>

                                </div>
                                <div class="product-content-wrap">
                                    <div class="product-category">
                                        <a title="Action link for {{$subCategoryToProducts->product_name}} category page" href="{{route('page',$subCategoryToProducts->slug)}}">{{$subCategoryToProducts->name}}</a>
                                    </div>
                                    <span><a title="Action link for {{$product->product_name}} detail page" href="{{route('page',$product->slug)}}">{{$product->product_name}}</a></span>

                                    <div class="product-action-1 show">
                                        <a title="Action link for {{$product->product_name}} detail page" aria-label="See Product Detail" class="action-btn hover-up" href="{{route('page',$product->slug)}}"><i class="fi-rs-shopping-bag-add"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                        <div class="pagination-area mt-20 mb-20">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-start">
                                    <li>
{{--                                        {!! $subCategoryToProducts->links() !!}--}}
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
       
        </div>
    </section>

    <section class="mb-50">
        <div class="container">
    <div class="tab-style3">
        <ul class="nav nav-tabs text-uppercase">
           <!--  <li class="nav-item">
                <a class="nav-link " id="Additional-info-tab" data-bs-toggle="tab"
                   href="#Additional-info">Specifications</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link active " id="Description-tab" data-bs-toggle="tab"
                   href="#Description">Description</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="Reviews-tab" data-bs-toggle="tab" href="#Faqs">FAQs</a>
            </li>
        </ul>
        <div class="tab-content shop_info_tab entry-main-content">
            <div class="tab-pane fade " id="Additional-info">
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
            <div class="tab-pane fade show active description" id="Description">
                <div class="container after-p test" style=" overflow-y: auto; max-height: 350px;">
                     {!! $subCategoryToProducts->detail !!}
                </div>
            </div>

            <div class="tab-pane fade" id="Faqs">
                @php
                    $faqs = [
                        [
                            'question' => 'How to Calculate or Measure the Sizes Boxes?',
                            'answer' => 'To measure the size of your Boxes, follow these steps: Diameter: Measure the circular width of the box from one edge to the opposite edge. Height: Measure from the bottom of the box to the top.'
                        ],
                        [
                            'question' => 'What is Your Standard Turnaround Time?',
                            'answer' => 'Our standard production turnaround time is 8 to 12 business days after final design approval. This includes printing, die-cutting, and finishing processes. Shipping times may vary based on your location.'
                        ],
                        [
                            'question' => 'What is Your Urgent Turnaround Time?',
                            'answer' => 'For urgent orders, we offer rush production with a 4 to 6 business days turnaround. If you need expedited delivery, let us know at the time of ordering, and we’ll prioritize your request.'
                        ],
                        [
                            'question' => 'How Many Colors Can I Print on My Boxes?',
                            'answer' => 'We offer full-color CMYK and PMS printing, meaning you can print as many colors as you like without limitations. Whether you want a single-color minimalist look or a vibrant, multi-color design, we have advanced printing technology to bring your vision to life.'
                        ],
                        [
                            'question' => 'What is the Maximum Order You Can Process Per Month?',
                            'answer' => 'We can handle bulk orders efficiently, processing up to 500,000+ Boxes per month. Whether you need a small batch or a large-scale production run, we have the capacity and resources to meet your demands.'
                        ],
                        [
                            'question' => 'Where is Your Packaging Manufactured?',
                            'answer' => 'Our Boxes are proudly manufactured in our state-of-the-art production facility, located in the USA, UK and Middle East.'
                        ],
                        [
                            'question' => 'How Can I Order a Physical Sample Before Placing a Mass Order?',
                            'answer' => 'We offer physical samples for bulk orders so you can check the quality, size, and design before placing a bulk order.'
                        ],
                        [
                            'question' => 'How Does Your Design Team Help with My Project?',
                            'answer' => 'Our expert in-house design team provides 100% free design support to bring your packaging ideas to life.'
                        ],
                        [
                            'question' => 'How Can I Review the Box Before It Is Printed?',
                            'answer' => 'Before printing, we provide a 3D digital proof (mockup) or a physical sample (upon request). This ensures you can review every detail—colors, design alignment, and overall look—before final production. We only proceed once you approve the design.'
                        ]
                    ];
                @endphp

                @if(!empty($faqs))
                    <div class="faq-content mb-20 container">
                        <div class="accordion" id="faqAccordion">
                            @php $i = 1; @endphp
                            @foreach($faqs as $index => $faq)
                                @php
                                    $isFirst = $loop->first;
                                    $headingId = 'heading' . $index;
                                    $collapseId = 'collapse' . $index;
                                @endphp
                                <div class="accordion-item">
                                    <h4 class="accordion-header" id="{{ $headingId }}">
                                        <button class="accordion-button {{ !$isFirst ? 'collapsed' : '' }}" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#{{ $collapseId }}"
                                                aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                                aria-controls="{{ $collapseId }}">
                                            Q{{ $i++ }}. {{ $faq['question'] }}
                                        </button>
                                    </h4>
                                    <div id="{{ $collapseId }}"
                                         class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                                         aria-labelledby="{{ $headingId }}"
                                         data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            {!! nl2br(e($faq['answer'])) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-center">No FAQs available at the moment.</p>
                @endif
            </div>

        </div>
    </div>
        </div>
    </section>

@endsection
@push('scripts')
    @include('frontend.includes.infinite-scroll')
@endpush
