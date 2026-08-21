<div>
    <section class="product-tabs section-padding wow fadeIn animated">
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
                                                            <img title="{{ $product->product_name }}" class="hover-img" src="{{ $media->getUrl('l') }}" alt="{{ $media->name ?? 'Product image' }}" loading="lazy">
                                                        @endforeach
                                                    @endif
                                                    @if($product->getMedia('product_back_image')->isNotEmpty())
                                                        @foreach($product->getMedia('product_back_image') as $media)
                                                            <img title="{{ $product->product_name }}"  class="default-img" src="{{ $media->getUrl('l') }}" alt="{{ $media->name ?? 'Product image' }}" loading="lazy">
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
</div>
