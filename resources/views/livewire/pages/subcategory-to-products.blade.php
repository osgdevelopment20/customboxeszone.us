{{--resources/views/livewire/pages/subcategory-to-products.blade.php--}}
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="shop-product-fillter">
                        <h1 class="text-center" style="font-size: 28px!important;">{{$subCategoryToProducts->title}}</h1>
                        <div class="totall-product">
                            <p> We found <strong class="text-brand">{{$totalProducts}}</strong> items for you!</p>
                        </div>
                    </div>
                    <div class="infinite-scroll">
                        <div class="row product-grid-3" id="products-container">
                            @foreach($displayedProducts as $product)
                                <div class="col-lg-3 col-md-4">
                                    <div class="product-cart-wrap mb-30">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img product-img-zoom">
                                                <a title="Action link for {{$product->product_name}} detail page" href="{{route('page',$product->slug)}}">
                                                    @if ($product->getMedia('product_front_image')->isNotEmpty())
                                                        @foreach($product->getMedia('product_front_image') as $media)
                                                            <img title="{{$product->product_title}}"
                                                                 class="hover-img"
                                                                 src="{{ $media->getUrl('l') }}"
                                                                 alt="{{ $media->name }}"
                                                                 width="300"
                                                                 height="300"
                                                                 loading="lazy"
                                                                 style="aspect-ratio:1/1;object-fit:cover">
                                                        @endforeach
                                                    @endif
                                                    @if ($product->getMedia('product_back_image')->isNotEmpty())
                                                        @foreach($product->getMedia('product_back_image') as $media)
                                                            <img title="{{$product->product_title}}"
                                                                 class="default-img"
                                                                 src="{{ $media->getUrl('l') }}"
                                                                 alt="{{ $media->name }}"
                                                                 width="300"
                                                                 height="300"
                                                                 loading="lazy"
                                                                 style="aspect-ratio:1/1;object-fit:cover">
                                                        @endforeach
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap">
                                            <div class="product-category">
                                                <a title="Action link for {{$subCategoryToProducts->product_name}} category page"
                                                   href="{{route('page',$subCategoryToProducts->slug)}}">
                                                    {{$subCategoryToProducts->name}}
                                                </a>
                                            </div>
                                            <span>
            <a title="Action link for {{$product->product_name}} detail page"
               href="{{route('page',$product->slug)}}">
                {{$product->product_name}}
            </a>
        </span>

                                            <div class="product-action-1 show">
                                                <a title="Action link for {{$product->product_name}} detail page"
                                                   aria-label="See Product Detail"
                                                   class="action-btn hover-up"
                                                   href="{{route('page',$product->slug)}}">
                                                    <i class="fi-rs-shopping-bag-add"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Loading Indicator with Skeleton Placeholders --}}
                        <div wire:loading wire:target="loadMore" class="row product-grid-3">
                            @for($i = 0; $i < 4; $i++)
                            <div class="col-lg-3 col-md-4">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img skeleton-box" style="height: 300px; background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite;">
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="skeleton-text" style="height: 20px; width: 60%; background: #f0f0f0; margin-bottom: 10px; border-radius: 4px;"></div>
                                        <div class="skeleton-text" style="height: 16px; width: 80%; background: #f0f0f0; margin-bottom: 10px; border-radius: 4px;"></div>
                                        <div class="skeleton-text" style="height: 30px; width: 40%; background: #f0f0f0; border-radius: 4px;"></div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>

                        <style>
                            @keyframes shimmer {
                                0% { background-position: -200% 0; }
                                100% { background-position: 200% 0; }
                            }
                        </style>

                        {{-- Load More Trigger (Hidden) --}}
                        @if($hasMoreProducts)
                            <div class="load-more-trigger" style="height: 1px; visibility: hidden;"></div>
                        @else
                            <div class="text-center my-4">
                                <p class="text-muted">You've reached the end of the products list</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Infinite Scroll Script --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if IntersectionObserver is supported
            if ('IntersectionObserver' in window) {
                const loadMoreTrigger = document.querySelector('.load-more-trigger');

                if (loadMoreTrigger) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                // Trigger Livewire loadMore method
                                @this.call('loadMore');
                            }
                        });
                    }, {
                        rootMargin: '200px' // Trigger 200px before reaching the element
                    });

                    observer.observe(loadMoreTrigger);
                }
            } else {
                // Fallback for browsers that don't support IntersectionObserver
                window.addEventListener('scroll', function() {
                    const loadMoreTrigger = document.querySelector('.load-more-trigger');
                    if (loadMoreTrigger) {
                        const triggerPosition = loadMoreTrigger.getBoundingClientRect().top;
                        const windowHeight = window.innerHeight;

                        if (triggerPosition < windowHeight + 200) {
                            @this.call('loadMore');
                        }
                    }
                });
            }
        });
    </script>
    @endpush


    <x-specification-tabs
        :description="$subCategoryToProducts->detail"
        :faq-data="$subCategoryToProducts?->faqs "
    />
     @include('frontend.includes.process-icons')

    @section('schema')
        {!! $schemaData['webpage'] !!}
        {!! $schemaData['breadcrumb'] !!}
        {!! $schemaData['itemList'] !!}
    @endsection
</div>
