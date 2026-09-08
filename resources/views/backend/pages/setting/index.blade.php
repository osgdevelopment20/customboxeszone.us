@extends('backend.layouts.app')
@section('title')
   Site Settings
@endsection
@section('contents')

    <form method="post" action="{{route('dashboard.setting.store')}}" class="p-4 product_form" novalidate enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Setting</h4>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" placeholder="Phone"
                                           name="phone"
                                           value="{{sys_settings('phone')}}" >
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="col-12 mt-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                           id="email" placeholder="Email"
                                           name="email" value="{{sys_settings('email')}}" >
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                           id="address" placeholder="Address"
                                           name="address" value="{{sys_settings('address')}}" >
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="facebook" class="form-label">Facebook URL</label>
                                    <input type="text" class="form-control @error('facebook') is-invalid @enderror"
                                           id="facebook" placeholder="Facebook URL"
                                           name="facebook" value="{{sys_settings('facebook')}}" >
                                    @error('facebook')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="linkedin" class="form-label">LinkedIn URL</label>
                                    <input type="text" class="form-control @error('linkedin') is-invalid @enderror"
                                           id="linkedin" placeholder="LinkedIn URL"
                                           name="linkedin" value="{{sys_settings('linkedin')}}" >
                                    @error('linkedin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="instagram" class="form-label">Instagram URL</label>
                                    <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                                           id="instagram" placeholder="Instagram URL"
                                           name="instagram" value="{{sys_settings('instagram')}}" >
                                    @error('instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="youtube" class="form-label">YouTube URL</label>
                                    <input type="text" class="form-control @error('youtube') is-invalid @enderror"
                                           id="youtube" placeholder="YouTube URL"
                                           name="youtube" value="{{sys_settings('youtube')}}" >
                                    @error('youtube')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="x" class="form-label">X URL</label>
                                    <input type="text" class="form-control @error('x') is-invalid @enderror"
                                           id="x" placeholder="X URL"
                                           name="x" value="{{sys_settings('x')}}" >
                                    @error('x')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="pinterest" class="form-label">Pinterest URL</label>
                                    <input type="text" class="form-control @error('pinterest') is-invalid @enderror"
                                           id="pinterest" placeholder="Pinterest URL"
                                           name="pinterest" value="{{sys_settings('pinterest')}}" >
                                    @error('pinterest')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="footer_content" class="form-label">Footer Content</label>
                                    <input type="text" class="form-control @error('footer_content') is-invalid @enderror"
                                           id="footer_content" placeholder="Footer Content"
                                           name="footer_content" value="{{sys_settings('footer_content')}}" >
                                    @error('footer_content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-12 mt-3">
                                    <label for="about_company" class="form-label">About Company</label>
                                    <textarea class="form-control @error('about_company') is-invalid @enderror"
                                              id="about_company" placeholder="About Company"
                                              name="about_company">{{sys_settings('about_company')}}</textarea>
                                    @error('about_company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="header_content" class="form-label">Header Content</label>
                                    <x-text-editor name="header_content" :value="sys_settings('header_content')" height="200px" />

                                    @error('header_content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="homepage_content" class="form-label">Homepage Content</label>
                                    <x-text-editor name="homepage_content" :value="sys_settings('homepage_content')" height="200px" />
                                    @error('homepage_content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="site_meta_title" class="form-label">Site Meta Title</label>
                                    <textarea class="form-control @error('site_meta_title') is-invalid @enderror"
                                              id="site_meta_title" placeholder="Site Meta Title"
                                              name="site_meta_title">{{sys_settings('site_meta_title')}}</textarea>
                                    @error('site_meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="site_meta_description" class="form-label">Site Meta Description</label>
                                    <textarea class="form-control @error('site_meta_description') is-invalid @enderror"
                                              id="site_meta_description" placeholder="Site Meta Description"
                                              name="site_meta_description">{{sys_settings('site_meta_description')}}</textarea>
                                    @error('site_meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="col-12 mt-3">
                                    <label for="blogs_meta_title" class="form-label">Blogs Meta Title</label>
                                    <textarea class="form-control @error('blogs_meta_title') is-invalid @enderror"
                                              id="blogs_meta_title" placeholder="Blogs Meta Title"
                                              name="blogs_meta_title">{{sys_settings('blogs_meta_title')}}</textarea>
                                    @error('blogs_meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="blogs_meta_description" class="form-label">Blogs Meta Description</label>
                                    <textarea class="form-control @error('blogs_meta_description') is-invalid @enderror"
                                              id="blogs_meta_description" placeholder="Blogs Meta Description"
                                              name="blogs_meta_description">{{sys_settings('blogs_meta_description')}}</textarea>
                                    @error('blogs_meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="faqs_meta_title" class="form-label">FAQs Meta Title</label>
                                    <textarea class="form-control @error('faqs_meta_title') is-invalid @enderror"
                                              id="faqs_meta_title" placeholder="FAQs Meta Title"
                                              name="faqs_meta_title">{{sys_settings('faqs_meta_title')}}</textarea>
                                    @error('faqs_meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="faqs_meta_description" class="form-label">FAQs Meta Description</label>
                                    <textarea class="form-control @error('faqs_meta_description') is-invalid @enderror"
                                              id="faqs_meta_description" placeholder="FAQs Meta Description"
                                              name="faqs_meta_description">{{sys_settings('faqs_meta_description')}}</textarea>
                                    @error('faqs_meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                 {{--About Us--}}
                                <div class="col-12 mt-3">
                                    <label for="about_meta_title" class="form-label">About Us Meta Title</label>
                                    <textarea class="form-control @error('about_meta_title') is-invalid @enderror"
                                              id="about_meta_title" placeholder="About Us Meta Title"
                                              name="about_meta_title">{{sys_settings('about_meta_title')}}</textarea>
                                    @error('about_meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="about_meta_description" class="form-label">About Us Meta Description</label>
                                    <textarea class="form-control @error('about_meta_description') is-invalid @enderror"
                                              id="about_meta_description" placeholder="About Us Meta Description"
                                              name="about_meta_description">{{sys_settings('about_meta_description')}}</textarea>
                                    @error('about_meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="about_us" class="form-label">About Us</label>
                                    <x-text-editor name="about_us" :value="sys_settings('about_us')" height="200px" />

                                    @error('about_us')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                 {{-- Contact Us--}}
                                <div class="col-12 mt-3">
                                    <label for="contact_meta_title" class="form-label">Contact Us Meta Title</label>
                                    <textarea class="form-control @error('contact_meta_title') is-invalid @enderror"
                                              id="contact_meta_title" placeholder="Contact Us Meta Title"
                                              name="contact_meta_title">{{sys_settings('contact_meta_title')}}</textarea>
                                    @error('contact_meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="contact_meta_description" class="form-label">Contact Us Meta Description</label>
                                    <textarea class="form-control @error('contact_meta_description') is-invalid @enderror"
                                              id="contact_meta_description" placeholder="Contact Us Meta Description"
                                              name="contact_meta_description">{{sys_settings('contact_meta_description')}}</textarea>
                                    @error('contact_meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="contact_us" class="form-label">Contact Us</label>
                                    <x-text-editor name="contact_us" :value="sys_settings('contact_us')" height="200px" />

                                    @error('contact_us')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                               {{--  Privacy Policy  --}}
                                <div class="col-12 mt-3">
                                    <label for="policy_meta_title" class="form-label">Privacy Policy Meta Title</label>
                                    <textarea class="form-control @error('policy_meta_title') is-invalid @enderror"
                                              id="policy_meta_title" placeholder="Privacy Policy Meta Title"
                                              name="policy_meta_title">{{sys_settings('policy_meta_title')}}</textarea>
                                    @error('policy_meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="policy_meta_description" class="form-label">Privacy Policy Meta Description</label>
                                    <textarea class="form-control @error('policy_meta_description') is-invalid @enderror"
                                              id="policy_meta_description" placeholder="Privacy Policy Meta Description"
                                              name="policy_meta_description">{{sys_settings('policy_meta_description')}}</textarea>
                                    @error('policy_meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="privacy_policy" class="form-label">Privacy Policy</label>
                                    <x-text-editor name="privacy_policy" :value="sys_settings('privacy_policy')" height="200px" />

                                    @error('privacy_policy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{--Terms and Conditions--}}
                                <div class="col-12 mt-3">
                                    <label for="tnc_meta_title" class="form-label">Terms and Conditions Meta Title</label>
                                    <textarea class="form-control @error('tnc_meta_title') is-invalid @enderror"
                                              id="tnc_meta_title" placeholder="Terms and Conditions Meta Title"
                                              name="tnc_meta_title">{{sys_settings('tnc_meta_title')}}</textarea>
                                    @error('tnc_meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="tnc_meta_description" class="form-label">Terms and Conditions Meta Description</label>
                                    <textarea class="form-control @error('tnc_meta_description') is-invalid @enderror"
                                              id="tnc_meta_description" placeholder="Terms and Conditions Meta Description"
                                              name="tnc_meta_description">{{sys_settings('tnc_meta_description')}}</textarea>
                                    @error('tnc_meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="terms_and_condition" class="form-label">Terms and Conditions</label>
                                    <x-text-editor name="terms_and_condition" :value="sys_settings('terms_and_condition')" height="200px" />

                                    @error('terms_and_condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{--Refund Policy--}}
                                <div class="col-12 mt-3">
                                    <label for="refund_policy_meta_title" class="form-label">Refund Policy Meta Title</label>
                                    <textarea class="form-control @error('refund_policy_meta_title') is-invalid @enderror"
                                              id="refund_policy_meta_title" placeholder="Refund Policy Meta Title"
                                              name="refund_policy_meta_title">{{sys_settings('refund_policy_meta_title')}}</textarea>
                                    @error('refund_policy_meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="refund_policy_meta_description" class="form-label">Refund Policy Meta Description</label>
                                    <textarea class="form-control @error('refund_policy_meta_description') is-invalid @enderror"
                                              id="refund_policy_meta_description" placeholder="Refund Policy Meta Description"
                                              name="refund_policy_meta_description">{{sys_settings('refund_policy_meta_description')}}</textarea>
                                    @error('refund_policy_meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="refund_policy" class="form-label">Refund Policy</label>
                                    <x-text-editor name="refund_policy" :value="sys_settings('refund_policy')" height="200px" />

                                    @error('refund_policy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{--Shipping Policy--}}
                                <div class="col-12 mt-3">
                                    <label for="shipping_policy_meta_title" class="form-label">Shipping Policy Meta Title</label>
                                    <textarea class="form-control @error('shipping_policy_meta_title') is-invalid @enderror"
                                              id="shipping_policy_meta_title" placeholder="Shipping Policy Meta Title"
                                              name="shipping_policy_meta_title">{{sys_settings('shipping_policy_meta_title')}}</textarea>
                                    @error('shipping_policy_meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="shipping_policy_meta_description" class="form-label">Shipping Policy Meta Description</label>
                                    <textarea class="form-control @error('shipping_policy_meta_description') is-invalid @enderror"
                                              id="shipping_policy_meta_description" placeholder="Shipping Policy Meta Description"
                                              name="shipping_policy_meta_description">{{sys_settings('shipping_policy_meta_description')}}</textarea>
                                    @error('shipping_policy_meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="shipping_policy" class="form-label">Shipping Policy</label>
                                    <x-text-editor name="shipping_policy" :value="sys_settings('shipping_policy')" height="200px" />

                                    @error('shipping_policy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                    </div>
                        <button type="submit" class="my-4 btn btn-primary" >Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@push('scripts')
    <script>
        var product_base_image = new FileUploadWithPreview('image')
    </script>
@endpush
