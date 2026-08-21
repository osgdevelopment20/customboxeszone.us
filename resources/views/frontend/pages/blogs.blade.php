@extends('frontend.layouts.app')
@section('schema')
    {!! $blogSchema ?? ''  !!}
@endsection

@section('content')
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a title="Home Breadcrumb" href="{{ route('index') }}" rel="nofollow">Home</a>
            <span></span> Blog
        </div>
    </div>
</div>
<section class="mt-50 mb-50">
    <div class="container custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="single-header mb-50 text-center">
                    <h1 class="font-xxl text-brand">Our Blog</h1>
                </div>
                <div class="loop-grid pr-30">
                    <div class="row">
                        @foreach ($blogs as $blog)
                        <div class="col-lg-4">
                            <article class="wow fadeIn animated hover-up mb-30">
                                <div class="post-thumb img-hover-scale">
                                    <a title="Action Link for {{ $blog->title }} detail page" href="{{ route('blog.detail',$blog->slug) }}">
                                        <img title="{{ $blog->title }}" src="{{ $blog->blog_image_url }}" alt="{{$blog->blog_img_title  }}" width="400" height="300" loading="lazy" style="aspect-ratio:4/3;object-fit:cover">
                                    </a>

                                </div>
                                <div class="entry-content-2">
                                    <h3 class="post-title mb-15">
                                        <a title="Action Link for {{ $blog->title }} detail page" href="{{ route('blog.detail',$blog->slug) }}">{{ $blog->title }}</a></h3>
                                    <p class="post-exerpt mb-30">{{ Str::words($blog->blog_sdis, 10, '...') }}</p>
                                    <div class="entry-meta meta-1 font-xs color-grey mt-10 pb-10">
                                        <div>
                                            <span class="post-on"> <i class="fi-rs-clock"></i> {{date('jS F Y', strtotime($blog->posted_date))}}</span>

                                        </div>
                                        <a title="Action Link for {{ $blog->title }} detail page" href="{{ route('blog.detail',$blog->slug) }}" class="text-brand">Read more <i class="fi-rs-arrow-right"></i></a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
