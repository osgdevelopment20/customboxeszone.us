<section class="mt-30 mb-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="single-page pr-30">
                    <div class="single-header style-2">
                        <h2>Business Transparency</h2>
                    </div>
                    <div class="single-content">
                        <p>
                            {{ config('app.name') }} is a custom packaging supplier serving customers across the United States.
                            Product pages show packaging styles and options; final pricing, production time, shipping cost,
                            and artwork approval are confirmed in writing before payment is collected.
                        </p>
                        <p>
                            Customers can contact us before or after placing a quote request at
                            <a href="mailto:{{ merchant_contact_email() }}">{{ merchant_contact_email() }}</a>
                            or <a href="tel:{{ merchant_contact_phone() }}">{{ merchant_contact_phone() }}</a>.
                        </p>
                        @php($addresses = business_addresses())
                        @if (count($addresses) === 1)
                            <p>Business address: {{ $addresses[0] }}.</p>
                        @elseif (count($addresses) > 1)
                            <p class="mb-2">Business addresses:</p>
                            <ul class="mb-3">
                                @foreach ($addresses as $address)
                                    <li>{{ $address }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
