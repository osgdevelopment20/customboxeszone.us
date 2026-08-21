@extends('frontend.layouts.app')
@section('title')
{{ $blog->title }}
@endsection
@section('schema')
    {!! $blogSchema ?? ''  !!}
@endsection

@section('content')


<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a title="Home breadcrumb" href="{{ route('index') }}" rel="nofollow">Home</a>
            <span></span> <a title="blogs breadcrumb"  href="{{ route('blog.page') }}" rel="nofollow">blogs</a>
            <span></span> {{ $blog->title }}
        </div>
    </div>
</div>
<section class="mt-50 mb-50">
    <div class="container custom">
        <div class="row">
            <div class="col-lg-10 m-auto">
                <div class="single-page pl-30">
                    <div class="single-header style-2">
                        <h1 class="mb-30">{{ $blog->title }}</h1>
                        <div class="single-header-meta">
                            <div class="entry-meta meta-1 font-xs mt-15 mb-15">
                                <span class="post-by">By <a href="javascript:void(0)">admin</a></span>
                                <span class="post-on has-dot">{{date('jS F Y', strtotime($blog->posted_date))}}</span>
                                <span class="time-reading has-dot">6 mins read</span>

                            </div>
                            {{-- <div class="social-icons single-share">
                                <ul class="text-grey-5 d-inline-block">
                                    <li><strong class="mr-10">Share this:</strong></li>
                                    <li class="social-facebook"><a href="#"><img src="assets/imgs/theme/icons/icon-facebook.svg" alt=""></a></li>
                                    <li class="social-twitter"> <a href="#"><img src="assets/imgs/theme/icons/icon-twitter.svg" alt=""></a></li>
                                    <li class="social-instagram"><a href="#"><img src="assets/imgs/theme/icons/icon-instagram.svg" alt=""></a></li>
                                    <li class="social-linkedin"><a href="#"><img src="assets/imgs/theme/icons/icon-pinterest.svg" alt=""></a></li>
                                </ul>
                            </div> --}}
                        </div>
                    </div>
                    <figure class="single-thumbnail">
                        <img title="{{ $blog->title }}" src="{{ asset('blogs/'.$blog->blog_image) }}" alt="{{$blog->blog_img_title  }}">
                    </figure>
                    <div class="single-content">
                       {!! $blog->blog_ldis !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
