<div>
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                {!! $breadcrumbData !!}
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
                                    <!-- MAIN SLIDES -->
                                    <div class="product-image-slider">
                                        @if ($product->getMedia('product_gallery')->isNotEmpty())
                                            @foreach ($product->getMedia('product_gallery') as $media)
                                                <figure class="border-radius-10">
                                                    <img class="product-image" title="{{ $product->product_title }}" src="{{ $media->getUrl('l') }}"
                                                         alt="{{ $media->name }}" loading="lazy" decoding="async" width="500" height="500" style="aspect-ratio:1/1;object-fit:contain" />
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
                                                <div><img title="{{ $product->product_title }}" src="{{ $media->getUrl('s') }}"
                                                          alt="{{ $media->name }}" loading="lazy" decoding="async" width="100" height="100" style="aspect-ratio:1/1;object-fit:cover" /></div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="detail-info">
                                    <livewire:product-order-form :product-id="$product->id"
                                                                 :product-name="$product->product_title ?: $product->product_name"/>
                                </div>
                            </div>
                        </div>

                        @include('frontend.includes.brands-icons')

                        {{-- Product Variations Section --}}
                        @if($variations && $variations->count() > 0)
                            <div class="product-variations-section mt-30 mb-30">
                                <div class="container">
                                    <div class="row mb-20">
                                        <div class="col-12">
                                            <h3 class="section-title style-1 mb-20">Standard Sizes & Estimated Prices</h3>
                                        </div>
                                    </div>

                                    <div class="variations-tab-style">
                                        <ul class="nav nav-pills mb-3" id="variationsTabs" role="tablist">
                                            @foreach($variations as $index => $variation)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                                            id="variation-{{ $variation->id }}-tab"
                                                            data-bs-toggle="pill"
                                                            data-bs-target="#variation-{{ $variation->id }}"
                                                            type="button"
                                                            role="tab"
                                                            aria-controls="variation-{{ $variation->id }}"
                                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                                        {{ $variation->title }}
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <div class="tab-content" id="variationsTabContent">
                                            @foreach($variations as $index => $variation)
                                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                                     id="variation-{{ $variation->id }}"
                                                     role="tabpanel"
                                                     aria-labelledby="variation-{{ $variation->id }}-tab">
                                                    <div class="row align-items-center">

                                                         <!-- Specifications Section -->
                                                        <div class="col-md-12">
                                                            <div class="variation-specifications">
                                                                @if($variation->specifications)
                                                                    <div class="specification-content">
                                                                        {!! $variation->specifications !!}
                                                                    </div>
                                                                @else
                                                                    <p class="text-muted">No specifications available for this variation.</p>
                                                                @endif

                                                                <!-- Pricing Information -->
                                                                <div class="pricing-info-box  p-3">
                                                                    <p class="pricing-title mb-3">
                                                                        <i class="fi-rs-info me-2"></i>Need premium add-ons, special finishes, or custom materials? Click the "<a
                                                                            href="{{route('get.quotes')}}">Get a Quote</a>" button to submit your requirements for a customized quotation.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                             <div class="text-center mt-3">
                                                                <button type="button" class="btn btn-primary get-quote-btn" data-bs-toggle="modal" data-bs-target="#variationQuoteModal">
                                                                    <i class="fi-rs-shopping-cart me-2"></i>Order Now
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Variations Styling --}}
                            <style>
                                .product-variations-section {
                                    background: #f8f9fa;
                                    padding: 30px 0;
                                    border-radius: 10px;
                                }

                                .variations-tab-style .nav-pills .nav-link {
                                    background-color: #fff;
                                    color: #253D4E;
                                    border: 2px solid #e0e0e0;
                                    border-radius: 8px;
                                    padding: 12px 24px;
                                    margin-right: 10px;
                                    margin-bottom: 10px;
                                    font-weight: 600;
                                    transition: all 0.3s ease;
                                }

                                .variations-tab-style .nav-pills .nav-link:hover {
                                    background-color: #088178;
                                    color: #fff;
                                    border-color: #088178;
                                    transform: translateY(-2px);
                                    box-shadow: 0 4px 8px rgba(8, 129, 120, 0.2);
                                }

                                .variations-tab-style .nav-pills .nav-link.active {
                                    background-color: #088178;
                                    color: #fff;
                                    border-color: #088178;
                                    box-shadow: 0 4px 12px rgba(8, 129, 120, 0.3);
                                }

                                .variation-image-wrapper {
                                    position: relative;
                                    overflow: hidden;
                                    border-radius: 10px;
                                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                                    max-width: 400px;
                                    margin: 0 auto;
                                }

                                .variation-img {
                                    width: 100%;
                                    height: auto;
                                    max-height: 350px;
                                    object-fit: contain;
                                    transition: transform 0.3s ease;
                                }

                                .variation-image-wrapper:hover .variation-img {
                                    transform: scale(1.05);
                                }

                                .no-image-placeholder {
                                    text-align: center;
                                    padding: 60px 20px;
                                    background: #f5f5f5;
                                    border-radius: 10px;
                                    border: 2px dashed #ddd;
                                }

                                .variation-specifications {
                                    background: #fff;
                                    padding: 25px;
                                    border-radius: 10px;
                                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                                }

                                .variation-spec-title {
                                    color: #088178;
                                    font-size: 1.5rem;
                                    font-weight: 700;
                                    margin-bottom: 15px;
                                    border-bottom: 3px solid #088178;
                                    padding-bottom: 10px;
                                }

                                .specification-content {
                                    line-height: 1.8;
                                    color: #555;
                                }

                                .specification-content ul {
                                    list-style: none;
                                    padding-left: 0;
                                }

                                .specification-content li {
                                    padding: 8px 0;
                                    padding-left: 25px;
                                    position: relative;
                                }

                                .specification-content li:before {
                                    content: "✓";
                                    position: absolute;
                                    left: 0;
                                    color: #088178;
                                    font-weight: bold;
                                }

                                /* Table Styling for Specifications */
                                .specification-content table {
                                    width: 100% !important;
                                    border-collapse: collapse;
                                    margin: 20px 0;
                                    background-color: #fff;
                                    border-radius: 8px;
                                    overflow: hidden;
                                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                                }

                                .specification-content table td {
                                    padding: 12px 15px;
                                    border: 1px solid #e0e0e0;
                                    font-size: 0.95rem;
                                    vertical-align: middle;
                                }

                                .specification-content table td:first-child {
                                    font-weight: 600;
                                    color: #253D4E;
                                    background-color: #f8f9fa;
                                    width: 40%;
                                }

                                .specification-content table td:last-child {
                                    color: #555;
                                }

                                .specification-content table tr:nth-child(even) {
                                    background-color: #f8f9fa;
                                }

                                .specification-content table tr:hover {
                                    background-color: #e8f5f4;
                                    transition: background-color 0.2s ease;
                                }

                                .specification-content table tr td strong {
                                    color: #088178;
                                }

                                /* Pricing Information Box Styles */
                                .pricing-info-box {
                                    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                                    border-left: 4px solid #088178;
                                    border-radius: 8px;
                                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                                }

                                .pricing-title {
                                    color: #088178;
                                    font-size: 1.1rem;
                                    font-weight: 600;
                                    margin-bottom: 15px;
                                }

                                .pricing-title i {
                                    color: #088178;
                                }

                                .pricing-features {
                                    list-style: none;
                                    padding-left: 0;
                                    margin-bottom: 15px;
                                }

                                .pricing-features li {
                                    padding: 8px 0;
                                    color: #333;
                                    font-size: 0.95rem;
                                    display: flex;
                                    align-items: center;
                                }

                                .pricing-features li i {
                                    color: #088178;
                                    font-size: 1.1rem;
                                    flex-shrink: 0;
                                }

                                .pricing-note {
                                    color: #666;
                                    font-size: 0.9rem;
                                    font-style: italic;
                                    border-top: 1px solid rgba(8, 129, 120, 0.2);
                                    padding-top: 12px;
                                }

                                .pricing-note i {
                                    color: #088178;
                                }

                                @media (max-width: 768px) {
                                    .variations-tab-style .nav-pills .nav-link {
                                        padding: 10px 16px;
                                        font-size: 0.9rem;
                                    }

                                    .variation-spec-title {
                                        font-size: 1.2rem;
                                    }

                                    .variation-specifications {
                                        margin-top: 20px;
                                    }
                                }
                            </style>
                        @endif


                        <div class="tab-style3">
                            <ul class="nav nav-tabs text-uppercase">
                              <!--   <li class="nav-item">
                                    <a title="Specifications" class="nav-link " id="Additional-info-tab" data-bs-toggle="tab"
                                       href="#Additional-info">Specifications</a>
                                </li> -->
                                <li class="nav-item">
                                    <a title="Stock Type & Thickness" class="nav-link active" id="Stock-tab" data-bs-toggle="tab"
                                       href="#Stock">Stock Type & Thickness</a>
                                </li>
                                <li class="nav-item">
                                    <a title="Finishing Assortment" class="nav-link" id="Finishing-tab" data-bs-toggle="tab"
                                       href="#Finishing">Finishing Assortment</a>
                                </li>
                                <li class="nav-item">
                                    <a title="Description" class="nav-link" id="Description-tab" data-bs-toggle="tab"
                                       href="#Description">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a title="FAQs" class="nav-link" id="Reviews-tab" data-bs-toggle="tab" href="#Faqs">FAQs</a>
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
                                            <th>Minimum Run ( For Boxes and Mylar Bags)</th>
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
                                            <td>Eco-Friendly, Recycled Boxes, Biodegradable</td>
                                        </tr>
                                        <tr>
                                            <th>Proof</th>
                                            <td>Flat View, 3D Mock-up, Physical Sampling (On request)</td>
                                        </tr>
                                        <tr>
                                            <th>Turnaround</th>
                                            <td>7 - 8 Business Days</td>
                                        </tr>
                                        <tr>
                                            <th>Shipping</th>
                                            <td>FLAT Position</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="tab-pane fade show active" id="Stock">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                                <div class="member">
                                                    <img loading="lazy" width="292" height="235" src="{{asset('frontend/product-cards/cardboard.jpg')}}" class="img-fluid" alt="Cardboard Material" title="Cardboard - Eco-friendly packaging material">
                                                    <div class="member-info">
                                                        <div class="member-info-content py-4">
                                                            <span class="text-span">Cardboard</span> <br>
                                                            <span>We use eco-friendly cardboard materials designed to minimize environmental impact and reduce waste.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                                <div class="member">
                                                    <img loading="lazy" width="292" height="235" src="{{asset('frontend/product-cards/kraft.jpg')}}" class="img-fluid" alt="Kraft Material" title="Kraft - Sturdy and durable packaging">
                                                    <div class="member-info">
                                                        <div class="member-info-content py-4">
                                                            <span class="text-span">Kraft</span> <br>
                                                            <span>Kraft paper is a sturdy, durable, and dependable material used across multiple industries.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                                <div class="member">
                                                    <img loading="lazy" width="292" height="235" src="{{asset('frontend/product-cards/rigid.jpg')}}" class="img-fluid" alt="Rigid Material" title="Rigid - Maximum durability and security">
                                                    <div class="member-info">
                                                        <div class="member-info-content py-4">
                                                            <span class="text-span">Rigid</span> <br>
                                                            <span>Rigid material is highly protective, cost-effective, and recyclable, offering maximum durability and security.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                                <div class="member">
                                                    <img loading="lazy" width="292" height="235" src="{{asset('frontend/product-cards/corrugated.jpg')}}" class="img-fluid" alt="Corrugated Material" title="Corrugated - Customizable and sustainable packaging">
                                                    <div class="member-info">
                                                        <div class="member-info-content py-4">
                                                            <span class="text-span">Corrugated</span> <br>
                                                            <span>Corrugated material is customizable, cost-effective, and sustainable, making it an excellent choice for branding and shipping.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="Finishing">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                                <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/foil-stamping.jpg')}}" class="img-fluid" alt="Foil Stamping Finish" title="Premium Foil Stamping for Sophisticated Packaging">
                                                <div class="member-info">
                                                    <div class="member-info-content py-3 text-center pb-3">
                                                        <span class="text-span2">Foil Stamping</span> <br>
                                                        <span>Enhance your product packaging with our premium foil stamping for a sophisticated touch.</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                                <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/spot-uv.jpg')}}" class="img-fluid" alt="Spot UV Coating" title="High-Gloss UV Coating for Enhanced Shine">
                                                <div class="member-info">
                                                    <div class="member-info-content py-3 text-center pb-3">
                                                        <span class="text-span2">Spot UV</span> <br>
                                                        <span>Our high-gloss UV coating enhances shine and provides a sleek, protective finish to your printed materials.</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                                <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/gloss-lamination.jpg')}}" class="img-fluid" alt="Gloss Lamination" title="Eye-Catching Glossy Finish">
                                                <div class="member-info">
                                                    <div class="member-info-content py-3 text-center pb-3">
                                                        <span class="text-span2">Gloss</span> <br>
                                                        <span>Our glossy finish adds a sleek, eye-catching shine to your packaging.</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                                <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/holographic-foiling.jpg')}}" class="img-fluid" alt="Holographic Foiling Effect" title="Rainbow Effect Holographic Foiling">
                                                <div class="member-info">
                                                    <div class="member-info-content py-3 text-center pb-3">
                                                        <span class="text-span2">Holographic Foiling</span> <br>
                                                        <span>Holographic foil refracts light, creating a stunning rainbow effect from different angles.</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
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
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                                <img loading="lazy" width="306" height="155" src="{{asset('frontend/product-cards/debossing.jpg')}}" class="img-fluid" alt="Debossing Technique" title="Refined Debossing Effect">
                                                <div class="member-info">
                                                    <div class="member-info-content py-3 text-center pb-1">
                                                        <span class="text-span2">Debossing</span><br>
                                                        <span>Our debossing technique imprints unique graphics into your packaging, creating a refined and tactile effect.</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
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
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
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

                                <div class="tab-pane fade description" id="Description">
                                    <div class="container after-p test" style=" overflow-y: auto; max-height: 350px;">
                                        {!! $product->product_description !!}
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="Faqs">
                                    @if($productFaqs)
                                        <div class="faq-content mb-20 container">
                                            <div class="accordion" id="faqAccordion">
                                                @php $i = 1; @endphp
                                                @foreach($productFaqs as $key => $faq)
                                                    <div class="accordion-item">
                                                        <h4 class="accordion-header" id="heading{{ $key }}">
                                                            <button class="accordion-button {{ $key === 0 ? '' : 'collapsed' }}"
                                                                    type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapse{{ $key }}"
                                                                    aria-expanded="{{ $key === 0 ? 'true' : 'false' }}"
                                                                    aria-controls="collapse{{ $key }}">
                                                                Q{{$i++}}. {{ $faq['question'] }}
                                                            </button>
                                                        </h4>
                                                        <div id="collapse{{ $key }}"
                                                             class="accordion-collapse collapse {{ $key === 0 ? 'show' : '' }}"
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

                         @include('frontend.includes.process-icons')

                         @include('frontend.includes.reviews')

                        {{-- Client Stories Section --}}

                        @if($clientStories)

                            @php
                                                                $validStories = array_filter($clientStories, function($story) {
                                                                    return !empty($story['description']) || !empty($story['image']);
                                                                });
                            @endphp

                            @if(count($validStories) > 0)
                                <section class="client-stories-section">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12 mb-50 mt-3">
                                                <span class="section-title style-1 mb-30">
                                                    <span class="section-heading">Our Client Case Study</span>
                                                </span>
                                                <div class="bt-1 border-color-1 mt-15 mb-15 mx-auto" style="width: 100px;"></div>
                                            </div>
                                        </div>

                                        @foreach($validStories as $index => $story)
                                            @php
                                                $isEven = ($index + 1) % 2 == 0;
                                                $hasImage = !empty($story['image']);
                                                $hasDescription = !empty($story['description']);
                                            @endphp

                                            <div class="row client-story-item mb-50 align-items-center {{ $isEven ? 'flex-row-reverse' : '' }}">
                                                {{-- Image Column --}}
                                                @if($hasImage)
                                                    <div class="col-md-6 mb-4 mb-md-0">
                                                        <div class="client-story-image {{ $isEven ? 'text-start' : 'text-end' }}">
                                                            <div class="story-image-wrapper">
                                                                <img src="{{ asset('storage/' . $story['image']) }}"
                                                                     alt="Client Story {{ $index + 1 }}"
                                                                     loading="lazy" decoding="async"
                                                                     class="img-fluid rounded-3 shadow-lg client-story-img">
                                                                <div class="image-overlay">
                                                                    <div class="overlay-content">
                                                                        <i class="fi-rs-eye"></i>
                                                                        <span>Case Study {{ $index }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- Description Column --}}
                                                <div class="col-md-{{ $hasImage ? '6' : '12' }} {{ !$hasImage ? 'text-center' : '' }}">
                                                    <div class="client-story-content {{ $isEven ? 'pe-md-4' : 'ps-md-4' }}">
                                                        <div class="story-number mb-3">
                                                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                                                <i class="fi-rs-star me-1"></i>
                                                                Case Study {{ $index  }}
                                                            </span>
                                                        </div>

                                                        @if($hasDescription)
                                                            <div class="story-description-wrapper">
                                                                <div class="story-description scrollable-content">
                                                                    {!! $story['description'] !!}
                                                                </div>
                                                                <div class="read-more-gradient"></div>
                                                            </div>
                                                        @endif

                                                        <div class="story-actions mt-3">
                                                            <button class="btn btn-outline-primary btn-sm read-more-btn">
                                                                <i class="fi-rs-eye me-1"></i>
                                                                Read More
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>

                                {{-- Custom Styles for Client Stories --}}
                                <style>
                                    .client-stories-section {
                                        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                                        position: relative;
                                        overflow: hidden;
                                    }

                                    .client-stories-section::before {
                                        content: '';
                                        position: absolute;
                                        top: 0;
                                        left: -50%;
                                        width: 200%;
                                        height: 100%;
                                        background: radial-gradient(circle at 50% 50%, rgba(58, 123, 213, 0.05) 0%, transparent 50%);
                                        z-index: 0;
                                    }

                                    .client-stories-section .container {
                                        position: relative;
                                        z-index: 1;
                                    }

                                    .section-heading {
                                        font-size: 2.5rem;
                                        font-weight: 700;
                                        background: linear-gradient(135deg, #088178 0%, #3a6073 100%);
                                        -webkit-background-clip: text;
                                        -webkit-text-fill-color: transparent;
                                        background-clip: text;
                                        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                    }

                                    .client-story-item {
                                        margin-bottom: 4rem;
                                        animation: fadeInUp 0.6s ease-out;
                                    }

                                    .story-image-wrapper {
                                        position: relative;
                                        overflow: hidden;
                                        border-radius: 15px;
                                        transition: all 0.3s ease;
                                    }

                                    .story-image-wrapper:hover {
                                        transform: translateY(-5px);
                                        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
                                    }

                                    .client-story-img {
                                        width: 100%;
                                        height: 350px;
                                        object-fit: cover;
                                        transition: transform 0.3s ease;
                                    }

                                    .story-image-wrapper:hover .client-story-img {
                                        transform: scale(1.05);
                                    }

                                    .image-overlay {
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                        right: 0;
                                        bottom: 0;
                                        background: linear-gradient(135deg, #088178 0%, rgba(58, 96, 115, 0.8) 100%);
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        opacity: 0;
                                        transition: opacity 0.3s ease;
                                        border-radius: 15px;
                                    }

                                    .story-image-wrapper:hover .image-overlay {
                                        opacity: 1;
                                    }

                                    .overlay-content {
                                        text-align: center;
                                        color: white;
                                    }

                                    .overlay-content i {
                                        font-size: 2rem;
                                        margin-bottom: 10px;
                                        display: block;
                                    }

                                    .overlay-content span {
                                        font-size: 1.2rem;
                                        font-weight: 600;
                                    }

                                    .client-story-content {
                                        padding: 20px 0;
                                    }

                                    .story-number .badge {
                                        font-size: 0.9rem;
                                        font-weight: 600;
                                        background: linear-gradient(135deg, #088178 0%, #3a6073 100%) !important;
                                    }

                                    .story-description-wrapper {
                                        position: relative;
                                        margin: 20px 0;
                                    }

                                    .scrollable-content {
                                        max-height: 300px;
                                        overflow-y: auto;
                                        padding-right: 15px;
                                        line-height: 1.8;
                                        color: #555;
                                        font-size: 1rem;
                                        border-left: 4px solid #088178;
                                        padding-left: 20px;
                                        background: rgba(255, 255, 255, 0.8);
                                        border-radius: 8px;
                                        padding: 20px;
                                        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
                                    }

                                    .scrollable-content::-webkit-scrollbar {
                                        width: 6px;
                                    }

                                    .scrollable-content::-webkit-scrollbar-track {
                                        background: #f1f1f1;
                                        border-radius: 3px;
                                    }

                                    .scrollable-content::-webkit-scrollbar-thumb {
                                        background: linear-gradient(135deg, rgba(8, 129, 120, 0.83) 0%, #3a6073 100%);
                                        border-radius: 3px;
                                    }

                                    .scrollable-content::-webkit-scrollbar-thumb:hover {
                                        background: #088178;
                                    }

                                    .read-more-gradient {
                                        position: absolute;
                                        bottom: 0;
                                        left: 0;
                                        right: 0;
                                        height: 30px;
                                        background: linear-gradient(transparent, rgba(255, 255, 255, 0.9));
                                        pointer-events: none;
                                    }

                                    .read-more-btn {
                                        border: 2px solid #088178;
                                        color: white;
                                        transition: all 0.3s ease;
                                        font-weight: 600;
                                    }

                                    .read-more-btn:hover {
                                        background: linear-gradient(135deg, #f3f3f3 0%, #3a6073 100%);
                                        border-color: #088178;
                                        color: white;
                                        transform: translateY(-2px);
                                        box-shadow: 0 5px 15px rgba(58, 123, 213, 0.3);
                                    }

                                    @keyframes fadeInUp {
                                        from {
                                            opacity: 0;
                                            transform: translateY(30px);
                                        }
                                        to {
                                            opacity: 1;
                                            transform: translateY(0);
                                        }
                                    }

                                    @media (max-width: 768px) {
                                        .section-heading {
                                            font-size: 2rem;
                                        }

                                        .client-story-item.flex-row-reverse {
                                            flex-direction: column !important;
                                        }

                                        .client-story-content {
                                            padding: 20px 0 0 0;
                                            text-align: center;
                                        }

                                        .client-story-img {
                                            height: 250px;
                                        }

                                        .scrollable-content {
                                            max-height: 150px;
                                            padding: 15px;
                                        }
                                    }
                                </style>

                                {{-- JavaScript for Read More functionality --}}
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const readMoreBtns = document.querySelectorAll('.read-more-btn');

                                        readMoreBtns.forEach(btn => {
                                            btn.addEventListener('click', function() {
                                                const content = this.closest('.client-story-content').querySelector('.scrollable-content');
                                                const isExpanded = content.style.maxHeight === 'none';

                                                if (isExpanded) {
                                                    content.style.maxHeight = '200px';
                                                    this.innerHTML = '<i class="fi-rs-eye me-1"></i>Read More';
                                                } else {
                                                    content.style.maxHeight = 'none';
                                                    this.innerHTML = '<i class="fi-rs-eye-off me-1"></i>Read Less';
                                                }
                                            });
                                        });
                                    });
                                </script>
                            @endif
                        @endif




                        @if($relatedProducts->isNotEmpty())
                            <div class="row">
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
                                                            <a title="Action link for {{$related_product->product_name}} detail page"
                                                               href="{{route('page',$related_product->slug)}}"
                                                               tabindex="0">
                                                                @if ($related_product->getMedia('product_front_image')->isNotEmpty() || $related_product->getMedia('product_back_image')->isNotEmpty())
                                                                    @foreach ($related_product->getMedia('product_front_image') as $media)
                                                                        <img title="{{$related_product->product_name}}"
                                                                             class="default-img"
                                                                             loading="lazy" decoding="async"
                                                                             src="{{ $media->getUrl('s') }}"
                                                                             alt="{{ $media->name }}">
                                                                    @endforeach
                                                                    @foreach ($related_product->getMedia('product_back_image') as $media)
                                                                        <img title="{{$related_product->product_name}}"
                                                                             class="hover-img"
                                                                             loading="lazy" decoding="async"
                                                                             src="{{ $media->getUrl('s') }}"
                                                                             alt="{{ $media->name }}">
                                                                    @endforeach
                                                                @endif
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="product-content-wrap">
                                                        <span>
                                                            <a title="Action link for {{$related_product->product_name}} detail page"
                                                               href="{{route('page',$related_product->slug)}}"
                                                               tabindex="0">
                                                                {{$related_product->product_name}}
                                                            </a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- <div class="banner-img banner-big wow fadeIn f-none animated mt-50">
                            <img title="Blow banner" class="border-radius-10"
                                 src="{{asset('frontend/assets/imgs/banner/blow-banner.jpg')}}"
                                 alt="Blow banner"
                                 loading="lazy" decoding="async">
                            <div class="banner-text"></div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Variation Quote Modal - Livewire Component -->
    @livewire('variation-quote-form', ['productId' => $product->id])

    @section('schema')
        {!! $schemaData['webpage'] !!}
        {!! $schemaData['breadcrumb'] !!}
        {!! $product->schema_org->toScript() !!}
        {!! $schemaData['faq'] !!}
    @endsection

    @push('scripts')
        <script src="{{asset('assets/js/extm-vanilla-zoom.js')}}" defer></script>

        <!-- Variation Quote Modal Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Set the variation ID when a variation tab is clicked
                const variationTabs = document.querySelectorAll('[id^="variation-"][id$="-tab"]');

                variationTabs.forEach(tab => {
                    tab.addEventListener('shown.bs.tab', function(event) {
                        const variationId = event.target.id.replace('variation-', '').replace('-tab', '');
                        const inputField = document.getElementById('variation_id_livewire');
                        if (inputField) {
                            inputField.value = variationId;
                            inputField.dispatchEvent(new Event('input'));
                        }
                        window.Livewire.emit('variationChanged', variationId);
                    });
                });

                // Set the first variation as default
                if (variationTabs.length > 0) {
                    const firstVariationId = variationTabs[0].id.replace('variation-', '').replace('-tab', '');
                    setTimeout(() => {
                        const inputField = document.getElementById('variation_id_livewire');
                        if (inputField) {
                            inputField.value = firstVariationId;
                            inputField.dispatchEvent(new Event('input'));
                        }
                        window.Livewire.emit('variationChanged', firstVariationId);
                    }, 100);
                }
            });
        </script>

        <!-- Custom Product Image Slider with Zoom for Product Detail Page (No Library Dependencies) -->
        <script>
            document.addEventListener('livewire:load', function() {
                // Custom slider implementation without external libraries
                const initProductGallery = function() {
                    const mainSlider = document.querySelector('.product-image-slider');
                    const thumbnailSlider = document.querySelector('.slider-nav-thumbnails');
                    if (!mainSlider || !thumbnailSlider) return;
                    // Get all slides and thumbnails
                    const slides = mainSlider.querySelectorAll('figure');
                    const thumbnails = thumbnailSlider.querySelectorAll('div');
                    if (!slides.length || !thumbnails.length) return;
                    // Initialize variables
                    let currentSlide = 0;
                    let isAnimating = false;
                    let touchStartX = 0;
                    let touchEndX = 0;
                    // Hide all slides except the first one
                    slides.forEach((slide, index) => {
                        if (index !== 0) {
                            slide.style.display = 'none';
                        }
                    });
                    // Add active class to first thumbnail
                    if (thumbnails.length > 0) {
                        thumbnails[0].classList.add('active');
                    }
                    // Function to show a specific slide
                    const showSlide = function(index) {
                        if (isAnimating) return;
                        isAnimating = true;
                        // Hide current slide
                        slides[currentSlide].style.display = 'none';
                        thumbnails[currentSlide].classList.remove('active');
                        // Update current slide index
                        currentSlide = index;
                        // Show new slide
                        slides[currentSlide].style.display = 'block';
                        thumbnails[currentSlide].classList.add('active');
                        // Reset animation flag after transition
                        setTimeout(() => {
                            isAnimating = false;
                        }, 300);
                    };
                    // Function to go to the next slide
                    const nextSlide = function() {
                        const newIndex = (currentSlide + 1) % slides.length;
                        showSlide(newIndex);
                    };
                    // Function to go to the previous slide
                    const prevSlide = function() {
                        const newIndex = (currentSlide - 1 + slides.length) % slides.length;
                        showSlide(newIndex);
                    };
                    // Add click event listeners to thumbnails
                    thumbnails.forEach((thumbnail, index) => {
                        // Add click event to the thumbnail div
                        thumbnail.addEventListener('click', () => {
                            if (index !== currentSlide) {
                                showSlide(index);
                            }
                        });
                        // Also add click event to the img inside the thumbnail div
                        const img = thumbnail.querySelector('img');
                        if (img) {
                            img.addEventListener('click', (e) => {
                                e.stopPropagation(); // Prevent triggering the parent div's click event
                                if (index !== currentSlide) {
                                    showSlide(index);
                                }
                            });
                        }
                    });
                    // Add touch event listeners for swipe functionality
                    mainSlider.addEventListener('touchstart', (e) => {
                        touchStartX = e.changedTouches[0].screenX;
                    });
                    mainSlider.addEventListener('touchend', (e) => {
                        touchEndX = e.changedTouches[0].screenX;
                        handleSwipe();
                    });
                    // Handle swipe direction
                    const handleSwipe = function() {
                        const swipeThreshold = 50;
                        if (touchEndX < touchStartX - swipeThreshold) {
                            // Swipe left, go to next slide
                            nextSlide();
                        } else if (touchEndX > touchStartX + swipeThreshold) {
                            // Swipe right, go to previous slide
                            prevSlide();
                        }
                    };
                    // Add responsive behavior for thumbnails
                    const updateThumbnailsDisplay = function() {
                        const containerWidth = thumbnailSlider.offsetWidth;
                        const thumbnailWidth = thumbnails[0].offsetWidth;
                        const visibleThumbnails = Math.floor(containerWidth / thumbnailWidth);
                        // Show/hide thumbnails based on screen size
                        thumbnails.forEach((thumbnail, index) => {
                            if (window.innerWidth <= 575) {
                                // Show only 3 thumbnails on small screens
                                thumbnail.style.display = index < 3 ? 'block' : 'none';
                            } else if (window.innerWidth <= 991) {
                                // Show only 3 thumbnails on medium screens
                                thumbnail.style.display = index < 3 ? 'block' : 'none';
                            } else {
                                // Show 4 thumbnails on large screens
                                thumbnail.style.display = index < 4 ? 'block' : 'none';
                            }
                        });
                    };
                    // Update thumbnails display on window resize
                    window.addEventListener('resize', updateThumbnailsDisplay);
                    // Initial update
                    updateThumbnailsDisplay();
                };
                // Initialize the product gallery
                initProductGallery();

                // --- Zoom logic is now fully independent of the slider ---
                // Observe changes in the product-image-slider to update zoom
                const sliderContainer = document.querySelector('.product-image-slider');
                if (sliderContainer) {
                    // Initial zoom for the first visible image
                    extmZoomForVisibleImage('.product-image');
                    // Observe for changes in child list or style
                    const observer = new MutationObserver(() => {
                        extmZoomForVisibleImage('.product-image');
                    });
                    observer.observe(sliderContainer, { childList: true, subtree: true, attributes: true, attributeFilter: ['style', 'class'] });
                }
            });
        </script>
        <style>
            #search-list{transition:opacity 0.3s ease}

            /* Get Quote Button Styles */
            .get-quote-btn {
                background: linear-gradient(135deg, #088178 0%, #3a6073 100%);
                border: none;
                color: white;
                padding: 12px 30px;
                font-weight: 600;
                border-radius: 8px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(8, 129, 120, 0.3);
            }

            .get-quote-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(8, 129, 120, 0.4);
                background: linear-gradient(135deg, #3a6073 0%, #088178 100%);
            }

            /* Custom Product Image Slider Styles */
            .product-image-slider {
                position: relative;
                overflow: hidden;
                margin-bottom: 10px;
            }

            .product-image-slider figure {
                margin: 0;
                transition: opacity 0.3s ease;
            }

            .product-image-slider img {
                width: 100%;
                height: auto;
                cursor: crosshair;
                border-radius: 10px;
            }

            .slider-nav-thumbnails {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                justify-content: center;
            }

            .slider-nav-thumbnails div {
                width: calc(25% - 10px);
                cursor: pointer;
                opacity: 0.6;
                transition: opacity 0.3s ease;
            }

            .slider-nav-thumbnails div.active {
                opacity: 1;
            }

            .slider-nav-thumbnails img {
                width: 100%;
                height: auto;
                border-radius: 10px;
            }

            .slider-nav-thumbnails div.active img {
                border: 2px solid #3BB77E;
                box-sizing: border-box;
            }

            .slider-btn {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 40px;
                height: 40px;
                background: rgba(255, 255, 255, 0.8);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 10;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .slider-prev {
                left: 10px;
            }

            .slider-next {
                right: 10px;
            }


            @media (max-width: 991px) {
                .slider-nav-thumbnails div {
                    width: calc(33.333% - 10px);
                }
            }

            @media (max-width: 575px) {
                .slider-nav-thumbnails div {
                    width: calc(33.333% - 10px);
                }
            }
        </style>
    @endpush
</div>
