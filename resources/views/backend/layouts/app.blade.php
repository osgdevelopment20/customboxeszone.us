<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>@yield('title') | DashBoard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    @include('backend.partials.favicon')

    @include('backend.partials.styles')
    <!-- jsvectormap css -->
    <link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />

    <!--Swiper slider css-->
    <link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    @stack('styles')
    @livewireStyles

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('backend.partials.menu')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">@yield('title') </h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">@yield('title') </li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            @if (\Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {!! \Session::get('success') !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            @if (\Session::has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {!! \Session::get('error') !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                        </div>
                    </div>
                    @yield('contents')
                </div>
            </div>
            @include('backend.partials.footer')
        </div>
    </div>

    @include('backend.partials.scripts')
    <script>
        // Delete routes only accept POST (with the CSRF token), so a link a third
        // party plants elsewhere cannot delete anything. Turn clicks on existing
        // ".../delete" links into a POST form submit. Runs after inline
        // onclick="return confirm(...)", which cancels by preventing default.
        document.addEventListener('click', function (event) {
            var link = event.target.closest('a[href]');
            if (!link || event.defaultPrevented) return;

            var url = new URL(link.href, window.location.href);
            if (url.origin !== window.location.origin || !/\/delete$/.test(url.pathname)) return;

            event.preventDefault();
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = url.href;
            var token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(token);
            document.body.appendChild(form);
            form.submit();
        });
    </script>
    @stack('scripts')
  
    @livewireScripts
</body>

</html>
