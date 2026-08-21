// resources/views/livewire/components/product-card.blade.php
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
