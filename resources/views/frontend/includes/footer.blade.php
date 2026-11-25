@php
    $addresses = business_addresses();
    $phone = trim((string) sys_settings('phone'));
    $email = trim((string) sys_settings('email'));
    $socials = array_filter([
        'Facebook'  => ['url' => sys_settings('facebook'), 'icon' => 'icon-facebook.svg'],
        'X'         => ['url' => sys_settings('x'), 'icon' => 'icon-twitter.svg'],
        'Instagram' => ['url' => sys_settings('instagram'), 'icon' => 'icon-instagram.svg'],
        'Pinterest' => ['url' => sys_settings('pinterest'), 'icon' => 'icon-pinterest.svg'],
        'YouTube'   => ['url' => sys_settings('youtube'), 'icon' => 'icon-youtube.svg'],
        'LinkedIn'  => ['url' => sys_settings('linkedin'), 'icon' => null],
    ], fn ($social) => filled($social['url']));
@endphp
<footer class="cbz-footer">
    <div class="cbz-footer__main">
        <div class="container">
            <div class="row g-4 g-lg-5">

                {{-- Brand --}}
                <div class="col-lg-4 col-md-6">
                    <a class="cbz-footer__logo" title="{{ config('app.name') }} logo" href="{{ route('index') }}">
                        <img src="https://res.cloudinary.com/dhxnzgb0d/image/upload/v1749666522/logo_iiokuk.svg"
                             alt="{{ config('app.name') }} logo" width="200" height="74" loading="lazy">
                    </a>
                    @if (filled(sys_settings('about_company')))
                        <p class="cbz-footer__about">{{ sys_settings('about_company') }}</p>
                    @endif

                    @if ($socials)
                        <p class="cbz-footer__title cbz-footer__title--small">Follow Us</p>
                        <div class="cbz-footer__social">
                            @foreach ($socials as $network => $social)
                                <a href="{{ $social['url'] }}" target="_blank" rel="noopener" title="Follow us on {{ $network }}" aria-label="Follow us on {{ $network }}">
                                    @if ($social['icon'])
                                        <img src="{{ asset('assets/imgs/theme/icons/' . $social['icon']) }}" alt="" width="18" height="18">
                                    @else
                                        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="#0A66C2" d="M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.94v5.67H9.36V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0z"/></svg>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Contact --}}
                <div class="col-lg-3 col-md-6">
                    <p class="cbz-footer__title">Contact Us</p>
                    <ul class="cbz-footer__contact">
                        @foreach ($addresses as $address)
                            <li>
                                <span class="cbz-footer__icon"><i class="fi-rs-marker" aria-hidden="true"></i></span>
                                <span>
                                    <a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode($address) }}" target="_blank" rel="noopener">{{ $address }}</a>
                                </span>
                            </li>
                        @endforeach
                        @if ($phone)
                            <li>
                                <span class="cbz-footer__icon"><i class="fi-rs-headset" aria-hidden="true"></i></span>
                                <span>
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">{{ $phone }}</a>
                                </span>
                            </li>
                        @endif
                        @if ($email)
                            <li>
                                <span class="cbz-footer__icon"><i class="fi-rs-envelope" aria-hidden="true"></i></span>
                                <span>
                                    <a href="mailto:{{ $email }}">{{ $email }}</a>
                                </span>
                            </li>
                        @endif
                    </ul>
                </div>

                {{-- Quick links --}}
                <div class="col-lg-2 col-md-6">
                    <p class="cbz-footer__title">Quick Links</p>
                    <ul class="cbz-footer__links">
                        <li><a href="{{ route('aboutUs') }}"><i class="fi-rs-angle-small-right" aria-hidden="true"></i>About Us</a></li>
                        <li><a href="{{ route('contactUs') }}"><i class="fi-rs-angle-small-right" aria-hidden="true"></i>Contact Us</a></li>
                        <li><a href="{{ route('SecurityAndPrivacy') }}"><i class="fi-rs-angle-small-right" aria-hidden="true"></i>Privacy Policy</a></li>
                        <li><a href="{{ route('refundPolicy') }}"><i class="fi-rs-angle-small-right" aria-hidden="true"></i>Refund Policy</a></li>
                        <li><a href="{{ route('returnPolicy') }}"><i class="fi-rs-angle-small-right" aria-hidden="true"></i>Return Policy</a></li>
                        <li><a href="{{ route('shippingPolicy') }}"><i class="fi-rs-angle-small-right" aria-hidden="true"></i>Shipping Policy</a></li>
                        <li><a href="{{ route('termsAndCondition') }}"><i class="fi-rs-angle-small-right" aria-hidden="true"></i>Terms &amp; Conditions</a></li>
                        <li><a href="{{ route('blog.page') }}"><i class="fi-rs-angle-small-right" aria-hidden="true"></i>Blogs</a></li>
                    </ul>
                </div>

                {{-- Map + payments --}}
                <div class="col-lg-3 col-md-6">
                    <p class="cbz-footer__title">Find Us</p>
                    <div class="cbz-footer__map">
                        <iframe title="{{ config('app.name') }} location map" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d49609340.39256505!2d-73.966337!3d40.631782!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2458f5f0f88df%3A0xa323ea2a45d756d6!2sCustom%20Boxes%20Zone%20-%20Custom%20Packaging%20Wholesale!5e0!3m2!1sen!2sus!4v1744664946140!5m2!1sen!2sus"
                                allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <p class="cbz-footer__secure"><i class="fi-rs-shield-check" aria-hidden="true"></i> Secured Payment Gateways</p>
                    <img class="cbz-footer__payments" src="{{ asset('assets/imgs/theme/payment-method.png') }}" alt="Accepted payment methods" loading="lazy">
                </div>

            </div>
        </div>
    </div>

    <div class="cbz-footer__bottom">
        <div class="container">
            <p>&copy; {{ date('Y') }}, {!! sys_settings('footer_content') !!}</p>
            <nav aria-label="Legal">
                <a href="{{ route('SecurityAndPrivacy') }}">Privacy</a>
                <a href="{{ route('termsAndCondition') }}">Terms</a>
                <a href="{{ route('shippingPolicy') }}">Shipping</a>
            </nav>
        </div>
    </div>
</footer>
