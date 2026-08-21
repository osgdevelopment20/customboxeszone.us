<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://res.cloudinary.com" crossorigin>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Disable Cloudflare Rocket Loader to prevent layout shifts -->
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <link rel="icon" href="{{asset('favicon.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('favicon.png')}}">
<meta name="google-site-verification" content="U3RA1S8tX7Yi6dQ_6o2hNGNAWKny5V7yPOT_4Lr-Wgc" />

    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//www.googletagmanager.com">

    <!-- Non-blocking web fonts (Lato + Spartan) -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900&family=Spartan:wght@300;400;500;600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900&family=Spartan:wght@300;400;500;600;700&display=swap"></noscript>

    <!-- Preload critical images to prevent CLS -->
    <link rel="preload" as="image" href="https://res.cloudinary.com/dhxnzgb0d/image/upload/v1749666522/logo_iiokuk.svg" fetchpriority="high">
    <link rel="preload" as="image" href="https://res.cloudinary.com/dhxnzgb0d/image/upload/f_auto,q_auto,w_800/v1749757805/cbz-banner_ciqlmm.jpg" fetchpriority="high" imagesrcset="https://res.cloudinary.com/dhxnzgb0d/image/upload/f_auto,q_auto,w_400/v1749757805/cbz-banner_ciqlmm.jpg 400w, https://res.cloudinary.com/dhxnzgb0d/image/upload/f_auto,q_auto,w_800/v1749757805/cbz-banner_ciqlmm.jpg 800w" imagesizes="(max-width: 768px) 100vw, 75vw">

    <!-- Scripts are now loaded in scripts.blade.php -->

    @if(isset($schemaWebsite) and isset($schemaOrganization) and isset($schemaBusiness))
        {!! $schemaWebsite->toScript() !!}
        {!! $schemaOrganization->toScript() !!}
        {!! $schemaBusiness->toScript() !!}
    @endif

    <!-- CSS Assets - non-blocking load -->
    <!--<link rel="preload" href="{{ asset('assets/css/main.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">-->
    <!--<noscript><link rel="stylesheet" href="{{ asset('assets/css/main.min.css') }}"></noscript>-->
    
    <link rel="stylesheet" href="{{ asset('assets/css/main.min.css') }}">
    <!-- Cart, checkout, product enquiry and browse screens -->
    <link rel="stylesheet" href="{{ asset('assets/css/cart-checkout.css') }}">

    <!-- Critical CSS with font-display swap and CLS prevention -->
 <style>
@font-face{font-display:swap}
body{margin:0;opacity:1;visibility:visible}

/* Prevent header layout shift - increased specificity */
.header-area{min-height:148px}
.header-middle{min-height:68px}
.header-bottom{min-height:80px}
.header-top{min-height:38px}

/* Reserve space for mega menus to prevent shifts */
.mega-menu{position:absolute;visibility:hidden;opacity:0;transition:opacity 0.3s ease}
.mega-menu.show{visibility:visible;opacity:1}

/* Prevent content shift */
/*.featured.section-padding{min-height:400px}*/
.home-slider{min-height:450px}

/* Product grid min-height to prevent layout shift */
.product-cart-wrap{min-height:380px}
.product-img-action-wrap{min-height:280px;display:flex;align-items:center;justify-content:center}


.whatsapp-float{position:fixed;bottom:20px;left:20px;background-color:#25d366;color:#FFF;width:60px;height:60px;border-radius:50px;text-align:center;font-size:30px;box-shadow:2px 2px 3px #999;z-index:100;display:flex;align-items:center;justify-content:center;text-decoration:none;transition:all 0.3s ease}
.whatsapp-float:hover{background-color:#128C7E;color:#FFF;transform:translateY(-3px)}

#preloader-active{position:fixed;top:0;left:0;width:100%;height:100%;z-index:99999;background-color:#fff;display:flex;align-items:center;justify-content:center}

.faq-content h1{font-size:18px}
.faq-content p{font-size:20px}
.accordion-header{background-color:#c1c1c129}
.accordion-button{font-weight:600}
.accordion-body{font-size:18px}
.accordion-button:not(.collapsed){color:#000;background-color:#08817821}
.accordion-button:focus{border-color:transparent;box-shadow:0 0 0 .25rem rgba(15,148,64,0.41)}

/* Prevent image layout shifts - reserve space */
img{height:auto;max-width:100%;display:block}
img[width][height]{aspect-ratio:attr(width)/attr(height)}

/* Ensure lazy images have aspect ratio */
img[loading="lazy"]{background-color:#f0f0f0}
</style>


    @stack('styles')
    @yield('schema')
    @livewireStyles
</head>

<body>
    <x-menu />
    <main class="main">
        @yield('content')
    </main>
    @include('frontend.includes.footer')
    @include('frontend.includes.scripts')
    @stack('scripts')
    @livewireScripts

    <!-- Lazy loading script - moved to bottom for better FCP -->
    <!--<script data-cfasync="false">-->
    <!--    document.addEventListener("DOMContentLoaded", function() {-->
    <!--        var lazyImages = [].slice.call(document.querySelectorAll("img.lazy, img[data-src]")).filter(function(img){-->
    <!--            return !img.dataset.critical;-->
    <!--        });-->

    <!--        if ("IntersectionObserver" in window) {-->
    <!--            let lazyImageObserver = new IntersectionObserver(function(entries, observer) {-->
    <!--                entries.forEach(function(entry) {-->
    <!--                    if (entry.isIntersecting) {-->
    <!--                        let lazyImage = entry.target;-->
    <!--                        lazyImage.src = lazyImage.dataset.src;-->
    <!--                        if (lazyImage.dataset.srcset) {-->
    <!--                            lazyImage.srcset = lazyImage.dataset.srcset;-->
    <!--                        }-->
    <!--                        lazyImage.classList.remove("lazy");-->
    <!--                        lazyImageObserver.unobserve(lazyImage);-->
    <!--                    }-->
    <!--                });-->
    <!--            });-->
    <!--            lazyImages.forEach(function(lazyImage) {-->
    <!--                lazyImageObserver.observe(lazyImage);-->
    <!--            });-->
    <!--        } else {-->
                // Fallback for old browsers
    <!--            lazyImages.forEach(function(lazyImage) {-->
    <!--                lazyImage.src = lazyImage.dataset.src;-->
    <!--                if (lazyImage.dataset.srcset) lazyImage.srcset = lazyImage.dataset.srcset;-->
    <!--            });-->
    <!--        }-->
    <!--    });-->
    <!--</script>-->

    <!-- Google Analytics - single deferred load -->
    <script data-cfasync="false">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        function loadAnalytics() {
            if (window.__gaLoaded) return;
            window.__gaLoaded = true;
            var s = document.createElement('script');
            s.async = true;
            s.src = 'https://www.googletagmanager.com/gtag/js?id=G-292914182';
            s.onload = function() {
                gtag('js', new Date());
                gtag('config', 'G-292914182');
                gtag('config', 'G-TLMSNZERH4');
            };
            document.head.appendChild(s);
        }
        if ('requestIdleCallback' in window) {
            requestIdleCallback(loadAnalytics, {timeout: 4000});
        } else {
            setTimeout(loadAnalytics, 3000);
        }
        ['scroll', 'click', 'touchstart'].forEach(function(evt) {
            window.addEventListener(evt, loadAnalytics, {once: true, passive: true});
        });
    </script>

    <a title="whatsapp chat" href="https://wa.me/{{sys_settings('whatsapp')}}?text=Hi there! I'm interested in your custom packaging solutions" class="whatsapp-float" target="_blank" aria-label="WhatsApp chat">
        <svg width="30" height="30" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
            <path d="M19.11 17.346c-.278-.139-1.642-.809-1.896-.9-.254-.093-.439-.14-.624.14-.186.278-.717.9-.88 1.085-.162.186-.324.21-.602.07-.278-.14-1.176-.434-2.24-1.385-.828-.737-1.387-1.648-1.55-1.927-.162-.278-.017-.429.123-.568.127-.126.278-.324.417-.486.14-.162.186-.278.278-.463.093-.186.047-.349-.023-.488-.07-.14-.624-1.507-.855-2.064-.225-.54-.455-.466-.624-.475l-.532-.01c-.186 0-.487.07-.741.349s-.973.951-.973 2.318 1 2.69 1.137 2.876c.139.186 1.965 3.003 4.762 4.21.666.287 1.186.458 1.592.586.668.212 1.276.182 1.757.11.536-.08 1.642-.67 1.875-1.316.232-.647.232-1.2.162-1.317-.07-.116-.254-.186-.532-.325zM16.04 28c-2.112 0-4.073-.62-5.716-1.69l-3.988 1.041 1.066-3.886C5.26 21.76 4.64 19.81 4.64 17.72 4.64 11.807 9.807 6.64 15.72 6.64c3.023 0 5.86 1.177 7.994 3.31 2.133 2.134 3.31 4.971 3.31 7.994 0 5.913-5.167 11.18-11.98 11.18zm9.164-21.344C22.733 4.185 19.383 2.88 15.72 2.88 8.75 2.88 3 8.63 3 15.6c0 2.744.747 5.298 2.164 7.56L3 30l6.989-2.132c2.186 1.197 4.665 1.83 7.05 1.83 6.97 0 12.72-5.75 12.72-12.72 0-3.664-1.433-7.013-3.955-9.322z"/>
        </svg>
    </a>

   <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6a626250ab56b61d4772470e/1ju85036n';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

</body>

</html>
