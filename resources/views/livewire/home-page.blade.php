<div>
   <section class="home-slider position-relative pt-25 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <div class="d-flex">
                             <div class="header-content px-2">
                                 {!! sys_settings('header_content') !!}
                                 <div class="image mt-50 w-75">
                                   <img src="https://res.cloudinary.com/dhxnzgb0d/image/upload/f_auto,q_auto,w_800/v1749757805/cbz-banner_ciqlmm.jpg"
                                        srcset="https://res.cloudinary.com/dhxnzgb0d/image/upload/f_auto,q_auto,w_400/v1749757805/cbz-banner_ciqlmm.jpg 400w, https://res.cloudinary.com/dhxnzgb0d/image/upload/f_auto,q_auto,w_800/v1749757805/cbz-banner_ciqlmm.jpg 800w"
                                        sizes="(max-width: 768px) 100vw, 75vw"
                                        alt="banner 1" title="banner 1" width="800" height="800" fetchpriority="high" decoding="async" data-critical="true" style="aspect-ratio:1/1;width:100%;height:auto;object-fit:cover">
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-md-none d-lg-block">
{{--                    @livewire('get-quote-step-form')--}}
                    <livewire:get-quote-for-product-page/>
                </div>
            </div>
        </div>
    </section>


    <style>
        @media (max-width:767px)
        {
            .home-slider image{
                display:none;
            }
        }
        .WidgetTitle__Header-sc-c581efe-2 {
            display: none !important;
        }

    </style>

    @include('frontend.includes.brands-icons', ['showArrows' => false])
    <section class="product-tabs wow fadeIn animated">
        <div class="container">
            <div class="tab-header">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach($productWithCategory as $index => $category)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="nav-tab-{{ $index }}" data-bs-toggle="tab" data-bs-target="#tab-{{ $index }}" type="button" role="tab" aria-controls="tab-{{ $index }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                {{ $category->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="tab-content wow fadeIn animated" id="myTabContent">
                @foreach($productWithCategory as $index => $category)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="tab-{{ $index }}" role="tabpanel" aria-labelledby="nav-tab-{{ $index }}">
                        <div class="row product-grid-4">

                            @foreach($category->products as $product)
                                @if (!$product)
                                    <p>No product found for this category.</p>
                                    @continue
                                @endif
                                <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                    <div class="product-cart-wrap mb-30">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img product-img-zoom">
                                                <a href="{{route('page',$product->slug)}}" title="{{ $product->product_title ?? $product->product_name }}">
                                                    @if ($product->getMedia('product_front_image')->isNotEmpty())
                                                        @foreach($product->getMedia('product_front_image') as $media)
                                                            <img title="{{ $product->product_name }}" class="hover-img" src="{{ $media->getUrl('s') }}" sizes="(max-width: 576px) 50vw, (max-width: 992px) 33vw, 280px" alt="{{ $media->name ?? 'Product image' }}" loading="lazy" width="280" height="280" style="aspect-ratio:1/1;object-fit:cover">
                                                        @endforeach
                                                    @endif
                                                    @if($product->getMedia('product_back_image')->isNotEmpty())
                                                        @foreach($product->getMedia('product_back_image') as $media)
                                                            <img title="{{ $product->product_name }}" class="default-img" src="{{ $media->getUrl('s') }}" sizes="(max-width: 576px) 50vw, (max-width: 992px) 33vw, 280px" alt="{{ $media->name ?? 'Product image' }}" loading="lazy" width="280" height="280" style="aspect-ratio:1/1;object-fit:cover">
                                                        @endforeach
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap">
                                            <div class="product-category">
                                                <a title="{{ $category->name }}" href="{{route('page',$product->slug)}}">{{ $category->name }}</a>
                                            </div>
                                            <span><a title="{{ $product->product_name }}" href="{{route('page',$product->slug)}}">{{ $product->product_name }}</a></span>
                                            <div class="product-action-1 show">
                                                <a title="product page action link" aria-label="Check Categories" class="action-btn hover-up" href="{{route('page',$product->slug)}}"><i class="fi-rs-shopping-bag-add"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    {{--    @include('frontend.includes.products-with-category')--}}
    @include('frontend.includes.blow-banner')
    <section class="banners mb-50">
        <div class="container">
            <div class="row">
                @foreach($homepageSubcategories as $category)
                    <div class="col-lg-4 col-md-6 mt-3">
                        <div class="banner-img wow fadeIn animated animated animated" style="visibility: visible;">
                            @if ($category->getMedia('homepage')->isNotEmpty())
                                @foreach($category->getMedia('homepage') as $media)
                                    <img src="{{ $media->getUrl('homepage') }}"
                                         sizes="(max-width: 768px) 100vw, 416px"
                                         alt="{{ $media->name }}"
                                         title="{{ $category->name }}"
                                         loading="lazy"
                                         width="416"
                                         height="177"
                                         style="aspect-ratio:416/177;object-fit:cover">
                                @endforeach
                            @endif
                            <div class="banner-text">
                                <span></span>
                                <p>{{$category->name}}</p>
                                <a title="category page action link" href="{{route('page',$category->slug)}}">Shop Now <i class="fi-rs-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    {{--    <livewire:homepage-categories>--}}
    @include('frontend.includes.reviews')
    <!-- Elfsight Google Reviews | Untitled Google Reviews -->
<!-- <script src="https://elfsightcdn.com/platform.js" async></script>
<div class="elfsight-app-3e8e5fc1-4547-4fe1-a331-4d43d8170e53" data-elfsight-app-lazy></div> -->
    {{-- <section class="section-padding">
        <div class="container pt-25 pb-20">
            <div class="row">
                <div class="col-lg-6">
                    <span class="section-title mb-20"><span>From</span> blog</span>
                    <div class="post-list mb-4 mb-lg-0 mt-20" >
                        @foreach ($blogs as $blog )
                            <article class="wow fadeIn animated">
                                <div class="d-md-flex d-block">
                                    <div class="post-thumb d-flex mr-15">
                                        <a target="_blank" title="{{ $blog->title }}" class="color-white" href="{{ asset('blogs/'.$blog->blog_image) }}">
                                            <img src="{{ asset('blogs/'.$blog->blog_image) }}"
                                                 title="{{ $blog->blog_img_title }}"
                                                 alt="{{ $blog->blog_img_title }}"
                                                 loading="lazy"
                                                 width="221"
                                                 height="auto"
                                                 style="aspect-ratio:1/1;object-fit:cover;">
                                        </a>
                                    </div>
                                    <div class="post-content">

                                    <span style="font-size: 20px" class="post-title mb-25 text-limit-2-row">
                                        <a title="{{ $blog->title }}" target="_blank" href="{{ route('blog.detail',$blog->slug) }}">{{ $blog->title }}</a>
                                    </span>
                                        <div class="entry-meta meta-1 font-xs color-grey mt-10 pb-10">
                                            <div>
                                                <span class="post-on">{{date('jS F Y', strtotime($blog->posted_date))}}</span>
                                            </div>
                                            <a title="{{ $blog->title }}" target="_blank" href="{{ route('blog.detail',$blog->slug) }}">Continue reading: {{ Str::limit($blog->title, 50) }}</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="banner-img banner-1 wow fadeIn animated">
                                <img title="bath bomb boxes" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/v1749666903/1_wbvcqp.png" alt="bath-bomb-boxes">
                                <div class="banner-text">
                                    <span>Retail Packaging</span>
                                    <p style="font-size: 1.5rem; font-weight: 500; margin: 0;">Bath Bomb Boxes </p>
                                    <a title="bath-bomb-boxes" href="{{config('app.url')}}/bath-bomb-boxes">Shop Now <i class="fi-rs-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="banner-img mb-15 wow fadeIn animated">
                                <img title="Retail Packaging" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/v1749666903/2_se6qhq.png" alt="Retail Packaging">
                                <div class="banner-text">
                                    <span>Retail Packaging</span>
                                    <p style="font-size: 1.5rem; font-weight: 500; margin: 0;">Cigarette <br> Boxes</p>
                                    <a title="Retail Packaging" href="{{config('app.url')}}/cigarette-boxes">Shop Now <i class="fi-rs-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="banner-img banner-2 wow fadeIn animated mb-0">
                                <img title="Food Packaging" src="https://res.cloudinary.com/dhxnzgb0d/image/upload/v1749666903/3_e9fjqu.png" alt="Food Packaging">
                                <div class="banner-text">
                                    <span>Food Packaging</span>
                                    <p style="font-size: 1.5rem; font-weight: 500; margin: 0;">Burger Boxes</p>
                                    <a title="Food Packaging" href="{{config('app.url')}}/burger-boxes">Shop Now <i class="fi-rs-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
   <!--  <section class="mb-50">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="banner-bg wow fadeIn animated"
                         style="background-image: url(https://res.cloudinary.com/dhxnzgb0d/image/upload/v1749667090/banner-8_vv7el7.jpg)">
                        <div class="banner-content">
                            <p class="text-grey-4 mb-15">Shop Today’s Deals</p>
                            <span style="font-size: 30px" class="fw-600">Happy <span class="text-brand">Independence's Day</span>. Big Sale Up to 40%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    @include('frontend.includes.process-icons')
    <x-specification-tabs/>
</div>
