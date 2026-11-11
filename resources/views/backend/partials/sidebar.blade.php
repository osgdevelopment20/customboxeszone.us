<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{asset('logo.svg')}}" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{asset('logo.svg')}}" alt="" height="50">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{asset('logo.svg')}}" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{asset('logo.svg')}}" alt="" height="50">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                
                @can('manage-users')
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.user.*') || request()->routeIs('dashboard.role.*') || request()->routeIs('dashboard.permission.*') ? 'active':''}}" href="#userManagement" data-bs-toggle="collapse" role="button" aria-expanded="{{request()->routeIs('dashboard.user.*') || request()->routeIs('dashboard.role.*') || request()->routeIs('dashboard.permission.*') ? 'true':'false'}}" aria-controls="userManagement">
                        <i class="ri-user-settings-line"></i> <span data-key="t-dashboards">User Management</span>
                    </a>
                    <div class="collapse {{request()->routeIs('dashboard.user.*') || request()->routeIs('dashboard.role.*') || request()->routeIs('dashboard.permission.*') ? 'show':''}} menu-dropdown" id="userManagement">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('dashboard.user.index')}}" class="nav-link {{request()->routeIs('dashboard.user.*') ? 'active': ''}}" data-key="t-analytics">Users</a>
                            </li>
                            @can('manage-roles')
                            <li class="nav-item">
                                <a href="{{route('dashboard.role.index')}}" class="nav-link {{request()->routeIs('dashboard.role.*') ? 'active': ''}}" data-key="t-analytics">Roles</a>
                            </li>
                            @endcan
                            @can('manage-permissions')
                            <li class="nav-item">
                                <a href="{{route('dashboard.permission.index')}}" class="nav-link {{request()->routeIs('dashboard.permission.*') ? 'active': ''}}" data-key="t-analytics">Permissions</a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcan
                
                @can('manage-marketing')
                <li class="nav-item ">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.marketing-contact.*') || request()->routeIs('dashboard.campaign.*') || request()->routeIs('dashboard.campaign.sms.campaign') ? 'active':''}}" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="{{request()->routeIs('dashboard.marketing-contact.index') || request()->routeIs('dashboard.campaign.index') || request()->routeIs('dashboard.campaign.sms.campaign') ? 'true':'false'}}" aria-controls="sidebarDashboards">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Marketing</span>
                    </a>
                    <div class="collapse {{request()->routeIs('dashboard.marketing-contact.*') || request()->routeIs('dashboard.campaign.*') || request()->routeIs('dashboard.campaign.sms.campaign') ? 'show':''}} menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item ">
                                <a href="{{route('dashboard.marketing-contact.index')}}" class="nav-link {{request()->routeIs('dashboard.marketing-contact.*')  ? 'active': ''}}" data-key="t-analytics">Marketing Contacts</a>
                            </li>
                            <li class="nav-item ">
                                <a href="{{route('dashboard.campaign.index')}}" class="nav-link {{request()->routeIs('dashboard.campaign.*') ? 'active': ''}}" data-key="t-analytics">Campaign</a>
                            </li>
                            <li class="nav-item ">
                                <a href="{{route('dashboard.campaign.sms.campaign')}}" class="nav-link {{request()->routeIs('dashboard.campaign.sms.campaign')  ? 'active': ''}}" data-key="t-analytics">SMS Campaign</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan

                   @can('manage-products')
                <li class="nav-item ">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.category.*') || request()->routeIs('dashboard.product.*') || request()->routeIs('dashboard.variation.*') }}" href="#productManagement" data-bs-toggle="collapse" role="button" aria-expanded="{{request()->routeIs('dashboard.category.index') || request()->routeIs('dashboard.product.index') || request()->routeIs('dashboard.variation.*')  ? 'true':'false'}}" aria-controls="productManagement">
                        <i class="ri-gift-2-line"></i> <span data-key="t-dashboards">Product Management</span>
                    </a>
                    <div class="collapse {{request()->routeIs('dashboard.category.*') || request()->routeIs('dashboard.product.*') || request()->routeIs('dashboard.variation.*')  ? 'show':''}} menu-dropdown" id="productManagement">
                        <ul class="nav nav-sm flex-column">
                            @can('manage-categories')
                            <li class="nav-item ">
                                <a href="{{route('dashboard.category.index')}}" class="nav-link {{request()->routeIs('dashboard.category.index')  ? 'active': ''}}" data-key="t-analytics">Category</a>
                            </li>
                            @endcan
                            <li class="nav-item ">
                                <a href="{{route('dashboard.product.index')}}" class="nav-link {{request()->routeIs('dashboard.product.*') && !request()->routeIs('dashboard.variation.*') ? 'active': ''}}" data-key="t-analytics">Products</a>
                            </li>
                            <li class="nav-item ">
                                <a href="{{route('dashboard.variation.index')}}" class="nav-link {{request()->routeIs('dashboard.variation.*') ? 'active': ''}}" data-key="t-analytics">Product Variations</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan

                @can('manage-banners')
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.banner.*')  ? 'active': ''}}" href="{{route('dashboard.banner.index')}}">
                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Banners</span>
                    </a>
                </li>
                @endcan

                @can('manage-blogs')
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.blog.*')  ? 'active': ''}}" href="{{route('dashboard.blog.index')}}">
                        <i class="ri-book-2-line"></i> <span data-key="t-widgets">Blogs</span>
                    </a>
                </li>
                @endcan
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link menu-link {{request()->routeIs('dashboard.order.*')  ? 'active': ''}}" href="{{route('dashboard.order.index')}}">--}}
{{--                        <i class="ri-luggage-cart-line"></i> <span data-key="t-widgets">Orders</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
                @can('manage-queries')
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.query.*')  ? 'active': ''}}" href="{{route('dashboard.query.index')}}">
                        <i class="ri-customer-service-2-line"></i> <span data-key="t-widgets">Queries</span>
                    </a>
                </li>
                @endcan
                @can('manage-orders')
                 <li class="nav-item">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.cart-orders.*')  ? 'active': ''}}" href="{{route('dashboard.cart-orders.index')}}">
                        <i class="ri-shopping-cart-2-line"></i> <span data-key="t-widgets">Cart Orders</span>
                    </a>
                </li>

                   <li class="nav-item">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.orders.*')  ? 'active': ''}}" href="{{route('dashboard.orders.index')}}">
                        <i class="ri-luggage-cart-line"></i> <span data-key="t-widgets">Orders</span>
                    </a>
                </li>
                @endcan

                @can('manage-settings')
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.setting.*')  ? 'active': ''}}" href="{{route('dashboard.setting.index')}}">
                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Settings</span>
                    </a>
                </li>
                @endcan

                @can('manage-reviews')
                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.google-reviews.*')  ? 'active': ''}}" href="{{route('dashboard.google-reviews.index')}}">
                        <i class="ri-star-line"></i> <span data-key="t-widgets">Google Reviews (API)</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.google-reviews.manual-import')  ? 'active': ''}}" href="{{route('dashboard.google-reviews.manual-import')}}">
                        <i class="ri-upload-line"></i> <span data-key="t-widgets">Manual Import Reviews</span>
                    </a>
                </li>
                @endcan

                <!-- <li class="nav-item">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.google-reviews-scraper.*')  ? 'active': ''}}" href="{{route('dashboard.google-reviews-scraper.index')}}">
                        <i class="ri-scissors-line"></i> <span data-key="t-widgets">Reviews Scraper (Free)</span>
                    </a>
                </li> -->


                <!-- <li class="nav-item">
                    <a class="nav-link menu-link {{request()->routeIs('dashboard.google-maps-data.*')  ? 'active': ''}}" href="{{route('dashboard.google-maps-data.debugger')}}">
                        <i class="ri-bug-line"></i> <span data-key="t-widgets">Google Maps Debugger</span>
                    </a>
                </li> -->

                @can('generate-sitemap')
                <li class="nav-item">
                    <a class="nav-link menu-link" target="_blank" href="{{route('sitemap.xml')}}">
                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Generate Sitemap</span>
                    </a>
                </li>
                @endcan
                
                @can('clear-cache')
                <li class="nav-item">
                    <a class="nav-link menu-link" target="_blank" href="{{route('fix.cache')}}">
                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Clear Website Cache</span>
                    </a>
                </li>
                @endcan
                
                @can('pull-code')
                <li class="nav-item">
                    <a class="nav-link menu-link" target="_blank" href="{{route('git.pull')}}">
                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Pull latest Code</span>
                    </a>
                </li>
                @endcan

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
