@extends('frontend.layouts.app')
{{--@section('title')--}}
{{--{{$categoryToSubCategory->title}}--}}
{{--@endsection--}}
@section('schema')
    {{--  webpage Schema  --}}
    {!! $schemaWebPage->toScript() !!}
    {{--  BreadCrumb Schema  --}}
    {!! $breadcrumbScript !!}
    {{--  list  Schema  --}}
    {!! $itemList->toScript() !!}
@endsection
@section('content')
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                {!! $AllCategoriesBreadcrumb !!}
            </div>
        </div>
    </div>
    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shop-product-fillter">
                        <h1 class="text-center" style="font-size: 28px!important;">{{$categoryToSubCategory->title}}</h1>
                        <div class="totall-product">
                            <p> We found <strong class="text-brand">{{$categoryToSubCategory->categories->count()}}</strong> items for you!</p>
                        </div>
                    </div>
                    <div class="row product-grid-3">
                        @foreach($categoryToSubCategory->categories as $categories)
                        <div class="col-lg-3 col-md-4">
                            <div class="product-cart-wrap mb-30">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">
                                        <a title="Action link for {{$categories->title}} categories page" href="{{route('page',$categories->slug)}}">
                                            @if ($categories->getMedia('category_img')->isNotEmpty())
                                                @foreach($categories->getMedia('category_img') as $media)
                                                    <img title="{{$categories->title}}" class="default-img" src="{{ route('media.show', [$media->id, 'l', $media->file_name]) }}" alt="{{ $media->name }}">
                                                @endforeach
                                            @endif
                                        </a>
                                    </div>

                                </div>
                                <div class="product-content-wrap">
                                    <div class="product-category">
                                        <a title="Action link for {{$categoryToSubCategory->name}} categories page" href="{{route('page',$categoryToSubCategory->slug)}}">{{$categoryToSubCategory->name}}</a>
                                    </div>
                                    <span><a title="Action link for {{$categories->name}} categories page" href="{{route('page',$categories->slug)}}">{{$categories->name}}</a></span>

                                    <div class="product-action-1 show">
                                        <a title="Action link for {{$categories->name}} categories page" aria-label="Check Sub Categories" class="action-btn hover-up" href="{{route('page',$categories->slug)}}"><i class="fi-rs-shopping-bag-add"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    </div>
                </div>

            </div>
    </section>
    <section>
        <div class="container">
      
        </div>
    </section>

    <x-specification-tabs
        :description="$categoryToSubCategory?->detail"
        :faq-data="$categoryToSubCategory?->faqs "
    />

@endsection

