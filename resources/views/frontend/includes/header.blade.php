<style>
/* Main navigation bar styling - Desktop only */
@media (min-width: 992px) {
    .header-bottom {
        background-color: #E89651 !important;
    }

    .main-menu > nav > ul > li > a {
        color: #1A3D52 !important;
        font-weight: 600 !important;
    }

    .main-menu > nav > ul > li > a:hover {
        color: white !important;
    }

    .main-menu > nav > ul > li > a.active {
        color: #1A3D52 !important;
    }

    .hotline p {
        color: #003D52 !important;
        font-weight: 700 !important;
    }

    .hotline p i {
        color: #003D52 !important;
    }
}

/* Search-style-2 responsive margin for screens 1399px and below */
@media (max-width: 1399px) {
    .search-style-2 {
        margin-right: 100px !important;
    }
}
</style>
<header class="header-area header-style-3 header-height-2">
    <div class="header-middle header-middle-ptb-1 d-none d-lg-block">
        <div class="container">
            <div class="header-wrap">
                <div class="logo logo-width-1">
                    <a title="{{config('app.name')}}" href="{{route('index')}}"><img title="{{config('app.name')}} logo"  src="https://res.cloudinary.com/dhxnzgb0d/image/upload/v1749666522/logo_iiokuk.svg" alt="{{config('app.name')}} logo" width="200" height="60" fetchpriority="high" decoding="async"></a>
                </div>
                <div class="header-right">
                    <div class="search-style-2">
                        <form action="{{route('searchpage')}}" method="get"  id="search-form-submit">
                            <input type="text" id="search-box" name="search" placeholder="Search for packaging boxes">
                            <div id="search-list"></div>
                        </form>
                    </div>
                     <div class="hotline d-none d-lg-block">
                    @livewire('cart-counter')
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-bottom header-bottom-bg-color sticky-bar" id="desktopView">
        <div class="container">
            <div class="header-wrap header-space-between position-relative  main-nav pt-2">
                <div class="logo logo-width-1 d-block d-lg-none">
                    <a title="{{config('app.name')}} logo" href="{{ route('index') }}"><img title="{{config('app.name')}} logo" src="{{asset('logo.svg')}}" alt="{{config('app.name')}} logo" width="140" height="40" decoding="async"></a>
                </div>
                <div class="header-nav d-none d-lg-flex">
                    <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block">
                        <nav>
                            <ul>
                                <li><a title="home" class="active" href="{{route('index')}}">Home</a>
                                </li>

                                <li class="position-static"><a href="{{route('allcategories')}}">Box by Industry <i
                                            class="fi-rs-angle-down"></i></a>
                                    <ul class="mega-menu">
                                        @foreach($byIndustries as $byIndustry)
                                            <li class="sub-mega-menu sub-mega-menu-width-20">
                                                <a title="Action link for {{ $byIndustry->name }}" class="menu-title" href="{{ route('page', $byIndustry->slug) }}">{{ $byIndustry->name }}</a>
                                                <ul style="height: 200px;overflow: auto">
                                                    @foreach($byIndustry->categories as $subcate)
                                                        <li><a title="Action link for {{ $subcate->name }}" href="{{ route('page', $subcate->slug) }}"> {{ $subcate->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li class="position-static"><a href="#">Box by Material<i
                                            class="fi-rs-angle-down"></i></a>
                                    <ul class="mega-menu">
                                        @foreach($byMaterials as $byMaterial)
                                            <li class="sub-mega-menu sub-mega-menu-width-20">
                                                <a title="Action link for {{ $byMaterial->name }}" class="menu-title" href="{{ route('page', $byMaterial->slug) }}">{{ $byMaterial->name }}</a>
                                                <ul style="height: 200px;overflow: auto">
                                                    @foreach($byMaterial->categories as $subcate)
                                                        <li><a title="Action link for {{ $subcate->name }}" href="{{ route('page', $subcate->slug) }}"> {{ $subcate->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li class="position-static"><a href="#">Box by Style<i
                                            class="fi-rs-angle-down"></i></a>
                                    <ul class="mega-menu">
                                        @foreach($byStyles as $byStyle)
                                            <li class="sub-mega-menu sub-mega-menu-width-20">
                                                <a title="Action link for {{ $byStyle->name }}" class="menu-title" href="{{ route('page', $byStyle->slug) }}">{{ $byStyle->name }}</a>
                                                <ul style="height: 200px;overflow: auto">
                                                    @foreach($byStyle->categories as $subcate)
                                                        <li><a title="Action link for {{ $subcate->name }}" href="{{ route('page', $subcate->slug) }}"> {{ $subcate->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="hotline d-none d-lg-block">
                    <p><i class="fi-rs-envelope"></i>{{sys_settings('email')}}</p>
                </div>
                <div class="hotline d-none d-lg-block">
                    <p><i class="fi-rs-headset"></i> {{sys_settings('phone')}}</p>
                </div>
                <div class="hotline d-none d-lg-block">
                    <div class="">
                        <a title="Get a Quote" class="btn btn-brand btn-lg font-weight-bold text-white border-radius-5 btn-shadow-brand hover-up" href="{{ route('get.quotes') }}">Get a Quote</a>
                    </div>
                </div>
                

{{--                <p class="mobile-promotion">Happy <span class="text-brand">Mother's Day</span>. Big Sale Up to 40%</p>--}}
                <div class="header-action-right d-block d-lg-none">
                    <div class="header-action-2">
                        <div class="header-action-icon-2 d-block d-lg-none cbz-cartlink--mobile">
                            @livewire('cart-counter', key('cart-counter-mobile'))
                        </div>

                        <div class="header-action-icon-2 d-block d-lg-none">
                            <div class="burger-icon burger-icon-white">
                                <span class="burger-icon-top"></span>
                                <span class="burger-icon-mid"></span>
                                <span class="burger-icon-bottom"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="mobile-header-active mobile-header-wrapper-style" id="mobileView">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-top">
            <div class="mobile-header-logo">
                <a title="{{config('app.name')}}" href="{{ route('index') }}"><img src="https://res.cloudinary.com/dhxnzgb0d/image/upload/v1749666522/logo_iiokuk.svg" alt="{{config('app.name')}} logo"  title="{{config('app.name')}}" width="200" height="60" decoding="async"></a>
            </div>
            <div class="mobile-menu-close close-style-wrap close-style-position-inherit">
                <button class="close-style search-close">
                    <i class="icon-top"></i>
                    <i class="icon-bottom"></i>
                </button>
            </div>
        </div>
        <div class="mobile-header-content-area">
            <div class="mobile-search search-style-3 mobile-header-border">
                <form action="{{route('searchpage')}}" method="get"  id="search-form-submit">
                    <input type="text" id="search-box" placeholder="Search for packaging boxes">
                    <button type="submit"><i class="fi-rs-search"></i></button>
                    <div id="search-list"></div>
                </form>
            </div>
            <div class="mobile-menu-wrap mobile-header-border">

                <nav>
                    <ul class="mobile-menu">
                        <li class="menu-item-has-children"><a
                            title="Home"    href="{{ route('index') }}">Home</a>
                        </li>

                        <li class="menu-item-has-children"><span class="menu-expand"></span><a href="{{route('allcategories')}}">Box by Industry</a>
                            <ul class="dropdown">
                                @foreach($byIndustries as $byIndustry)
                                <li class="menu-item-has-children"><span class="menu-expand"></span><a
                                     title="Action link for {{ $byIndustry->name }}"   href="{{ route('page', $byIndustry->slug) }}">{{ $byIndustry->name }}</a>
                                    <ul class="dropdown">
                                        @foreach($byIndustry->categories as $subcate)
                                        <li><a title="Action link for {{ $subcate->name }}" href="{{ route('page', $subcate->slug) }}">{{ $subcate->name }}</a></li>
                                      @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>

                        <li class="menu-item-has-children"><span class="menu-expand"></span><a href="#">Box by Material</a>
                            <ul class="dropdown">
                                @foreach($byMaterials as $byMaterial)
                                <li class="menu-item-has-children"><span class="menu-expand"></span><a
                                   title="Action link for {{ $byMaterial->name }}" href="{{ route('page', $byMaterial->slug) }}">{{ $byMaterial->name }}</a>
                                    <ul class="dropdown">
                                        @foreach($byMaterial->categories as $subcate)
                                        <li><a title="Action link for {{ $subcate->name }}" href="{{ route('page', $subcate->slug) }}">{{ $subcate->name }}</a></li>
                                      @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>

                        <li class="menu-item-has-children"><span class="menu-expand"></span><a href="#">Box by Style</a>
                            <ul class="dropdown">
                                @foreach($byStyles as $byStyle)
                                <li class="menu-item-has-children"><span class="menu-expand"></span><a
                                  title="Action link for {{ $byStyle->name }}"  href="{{ route('page', $byStyle->slug) }}">{{ $byStyle->name }}</a>
                                    <ul class="dropdown">
                                        @foreach($byStyle->categories as $subcate)
                                        <li><a title="Action link for {{ $subcate->name }}" href="{{ route('page', $subcate->slug) }}">{{ $subcate->name }}</a></li>
                                      @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>


                       <li><a title="About us" href="{{ route('aboutUs') }}">About Us</a></li>
                       <li><a title="Privacy Policy" href="{{ route('SecurityAndPrivacy') }}">Privacy Policy</a></li>
                       <li><a title="Terms &amp; Conditions" href="{{ route('termsAndCondition') }}">Terms &amp; Conditions</a></li>
                       <li><a title="Contact Us" href="{{ route('contactUs') }}">Contact Us</a></li>
                       <li><a title="Blogs" href="{{ route('blog.page') }}">Blogs</a></li>

                    </ul>
                </nav>
                <!-- mobile menu end -->
            </div>
            <div class="mobile-header-info-wrap mobile-header-border">
                <div class="single-mobile-header-info mt-30">
                    <a title="address" href="#"> {{ sys_settings('address') }} </a>
                </div>
                <div class="single-mobile-header-info">
                    <a title="Email" href="mailto:{{ sys_settings('email') }}">{{ sys_settings('email') }} </a>
                </div>
                <div class="single-mobile-header-info">
                    <a title="Phone" href="tel:{{ sys_settings('phone') }}">{{ sys_settings('phone') }} </a>
                </div>
            </div>
            <div class="mobile-social-icon">
                <span class="mb-15 text-grey-4">Follow Us</span>
                <div class="d-flex">
                <a target="_blank" href="{{ sys_settings('facebook') }}"><img src="{{asset('assets/imgs/theme/icons/icon-facebook.svg')}}" alt="Facebook Icon" title="Follow us on Facebook" width="32" height="32" decoding="async"></a>
                <a target="_blank" href="{{ sys_settings('x') }}"><img src="{{asset('assets/imgs/theme/icons/icon-twitter.svg')}}" alt="X Icon" title="Follow us on X" width="32" height="32" decoding="async"></a>
                <a target="_blank" href="{{ sys_settings('instagram') }}"><img src="{{asset('assets/imgs/theme/icons/icon-instagram.svg')}}" alt="Instagram Icon" title="Follow us on Instagram" width="32" height="32" decoding="async"></a>
                <a target="_blank" href="{{ sys_settings('pinterest') }}"><img src="{{asset('assets/imgs/theme/icons/icon-pinterest.svg')}}" alt="Pinterest Icon" title="Follow us on Pinterest" width="32" height="32" decoding="async"></a>
                <a target="_blank" href="{{ sys_settings('youtube') }}"><img src="{{asset('assets/imgs/theme/icons/icon-youtube.svg')}}" alt="YouTube Icon" title="Follow us on YouTube" width="32" height="32" decoding="async"></a>
                </div>
            </div>
        </div>
    </div>
</div>
