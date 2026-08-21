<div >
  {{--  <section class="popular-categories section-padding mt-15" >
        <div class="container wow fadeIn animated">
            <span class="section-title"><span>Popular</span> Categories</span>

            <div class="carausel-6-columns-cover position-relative mt-20">
                <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-arrows"></div>
                <div class="carausel-6-columns" id="carausel-6-columns">
                    @foreach($homepageSubcategories as $category)
                        <div class="card-1">
                            <figure class="img-hover-scale overflow-hidden">
                                <a title="{{$category->name}}" href="{{route('page',$category->slug)}}">
                                    @if ($category->getMedia('category_img')->isNotEmpty())
                                        @foreach($category->getMedia('category_img') as $media)
                                            <img src="{{$media->getUrl('l')}}" alt="{{$media->name}}" title="{{$category->name}}">
                                        @endforeach
                                    @endif
                                </a>

                            </figure>
                            <span><a title="category page action link" href="{{route('page',$category->slug)}}">{{$category->name}}</a></span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>--}}
    <section class="banners mb-50 mt-50">
        <div class="container">
            <div class="row">
                @foreach($homepageSubcategories as $category)
                <div class="col-lg-4 col-md-6 mt-3">
                    <div class="banner-img wow fadeIn animated animated animated" style="visibility: visible;">
                        @if ($category->getMedia('homepage')->isNotEmpty())
                            @foreach($category->getMedia('homepage') as $media)
                                <img src="{{$media->getUrl('homepage')}}" alt="{{$media->name}}" title="{{$category->name}}">
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
</div>
