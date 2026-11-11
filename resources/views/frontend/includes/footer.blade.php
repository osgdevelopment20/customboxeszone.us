<footer style="background-color: #C8E199; color: #1A3D52;">
    <section class="section-padding footer-mid">
        <div class="container pt-15 pb-20">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="widget-about font-md mb-md-5 mb-lg-0">
                        <div class="logo logo-width-1 wow fadeIn animated">
                            <a title="{{config('app.name')}} logo" href="{{ route('index') }}"><img src="https://res.cloudinary.com/dhxnzgb0d/image/upload/v1749666522/logo_iiokuk.svg" alt="{{config('app.name')}} logo" title="{{config('app.name')}} logo"></a>
                        </div>
                        <p class="wow fadeIn animated">
                            {{ sys_settings('about_company') }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="widget-about font-md mb-md-5 mb-lg-0">
                        <span class="mt-20 mb-10 fw-600 wow fadeIn animated" style="color: #1A3D52;">Contact</span>
                        <p class="wow fadeIn animated">
                            <strong>Address: </strong> {{ sys_settings('address') }}
                        </p>
                        <p class="wow fadeIn animated">
                            <strong>Phone: </strong>{{ sys_settings('phone') }}
                        </p>
                        <p class="wow fadeIn animated">
                            <strong>Email: </strong>{{ sys_settings('email') }}
                        </p>
                    </div>
                    <span class="mb-10 mt-30 fw-600 wow fadeIn animated" style="color: #1A3D52;">Follow Us
                         <div class="d-flex justify-content-around mt-15">
                            <a  target="_blank" href="{{ sys_settings('facebook') }}" title="Follow us on Facebook"><img width="30" src="{{asset('assets/imgs/theme/icons/icon-facebook.svg')}}" alt="Facebook Icon" title="Follow us on Facebook"></a>
                            <a  target="_blank" href="{{ sys_settings('x') }}" title="Follow us on X"><img width="30" src="{{asset('assets/imgs/theme/icons/icon-twitter.svg')}}" alt="X Icon" title="Follow us on X"></a>
                            <a  target="_blank" href="{{ sys_settings('instagram') }}" title="Follow us on Instagram"><img width="30" src="{{asset('assets/imgs/theme/icons/icon-instagram.svg')}}" alt="Instagram Icon" title="Follow us on Instagram"></a>
                            <a  target="_blank" href="{{ sys_settings('pinterest') }}" title="Follow us on Pinterest"><img width="30" src="{{asset('assets/imgs/theme/icons/icon-pinterest.svg')}}" alt="Pinterest Icon" title="Follow us on Pinterest"></a>
                            <a  target="_blank" href="{{ sys_settings('youtube') }}" title="Follow us on YouTube"><img width="30" src="{{asset('assets/imgs/theme/icons/icon-youtube.svg')}}" alt="YouTube Icon" title="Follow us on YouTube"></a>
                        </div>
                        </span>
                </div>
                <div class="col-lg-2 col-md-6">
                    <span class="widget-title wow fadeIn animated">Quick Links</span>
                    <ul class="footer-list wow fadeIn animated mb-sm-5 mb-md-0">
                        <li><a title="About Us" href="{{ route('aboutUs') }}">About Us</a></li>
                        <li><a title="Contact Us" href="{{ route('contactUs') }}">Contact Us</a></li>
                        <li><a title="Privacy Policy" href="{{ route('SecurityAndPrivacy') }}">Privacy Policy</a></li>
                        <li><a title="Refund Policy" href="{{ route('refundPolicy') }}">Refund Policy</a></li>
                        <li><a title="Return Policy" href="{{ route('returnPolicy') }}">Return Policy</a></li>
                        <li><a title="Shipping Policy" href="{{ route('shippingPolicy') }}">Shipping Policy</a></li>
                        <li><a title="Terms &amp; Conditions" href="{{ route('termsAndCondition') }}">Terms &amp; Conditions</a></li>
                        <li><a title="Blogs" href="{{ route('blog.page') }}">Blogs</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="row">
                        <div class="col-md-8 col-lg-12">
                            <iframe title="contact us map" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d49609340.39256505!2d-73.966337!3d40.631782!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2458f5f0f88df%3A0xa323ea2a45d756d6!2sCustom%20Boxes%20Zone%20-%20Custom%20Packaging%20Wholesale!5e0!3m2!1sen!2sus!4v1744664946140!5m2!1sen!2sus" width="340" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="col-md-4 col-lg-12 mt-md-3 mt-lg-0">
                            <p class="mb-20 wow fadeIn animated">Secured Payment Gateways</p>
                            <img class="wow fadeIn animated" src="{{asset('assets/imgs/theme/payment-method.png')}}" title="payment method images" alt="payment method images">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container pb-20 wow fadeIn animated">
        <div class="row">
            <div class="col-12 mb-20">
                <div class="footer-bottom"></div>
            </div>
            <div class="col-lg-12 text-center">
                <p class="float-md-left font-sm mb-0" style="color: #1A3D52;">&copy; <?php echo date("Y"); ?>, {!! sys_settings('footer_content') !!} </p>
            </div>

        </div>
    </div>
</footer>
