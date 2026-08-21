<div class="cbz-page">
    <div class="container">

        <div class="cbz-page__head">
            <div>
                <h1 class="cbz-page__title">Checkout</h1>
                <p class="cbz-page__subtitle">
                    Confirm your contact details and we'll send your custom quote — no payment is taken now.
                </p>
            </div>

            <ol class="cbz-steps" aria-label="Checkout progress">
                <li class="cbz-steps__item is-done"><span>1</span> Configure</li>
                <li class="cbz-steps__item is-done"><span>2</span> Cart</li>
                <li class="cbz-steps__item is-current"><span>3</span> Checkout</li>
            </ol>
        </div>

        @if (session()->has('error'))
            <div class="cbz-alert cbz-alert--error" role="alert">
                <i class="fi-rs-cross-circle" aria-hidden="true"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (empty($items))

            <div class="cbz-empty">
                <div class="cbz-empty__icon" aria-hidden="true"><i class="fi-rs-shopping-cart"></i></div>
                <h2 class="cbz-empty__title">Nothing to check out</h2>
                <p class="cbz-empty__text">Your cart is empty. Add a box to continue.</p>
                <a href="{{ route('allproducts') }}" class="cbz-btn cbz-btn--primary">
                    <i class="fi-rs-box-alt" aria-hidden="true"></i> Browse All Products
                </a>
            </div>

        @else

            <div class="cbz-checkout">
                <div class="cbz-checkout__form">
                    <div class="cbz-card">
                        <div class="cbz-card__head">
                            <h2 class="cbz-card__title">Your Details</h2>
                            <p class="cbz-card__subtitle">We'll use these to send your quote and confirm the artwork.</p>
                        </div>

                        <div class="cbz-card__body">
                            <form wire:submit.prevent="placeOrder" novalidate>
                                <div class="cbz-field-grid cbz-field-grid--2">
                                    <div class="cbz-field">
                                        <label for="co-name" class="cbz-label">Full Name <span class="cbz-req">*</span></label>
                                        <input type="text" id="co-name" wire:model.lazy="name" value="{{ $name }}" autocomplete="name"
                                               class="cbz-input @error('name') is-invalid @enderror" placeholder="John Smith">
                                        @error('name') <span class="cbz-error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="cbz-field">
                                        <label for="co-email" class="cbz-label">Email Address <span class="cbz-req">*</span></label>
                                        <input type="email" id="co-email" wire:model.lazy="email" value="{{ $email }}" autocomplete="email"
                                               class="cbz-input @error('email') is-invalid @enderror" placeholder="you@company.com">
                                        @error('email') <span class="cbz-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="cbz-field">
                                    <label for="co-phone" class="cbz-label">Phone Number <span class="cbz-req">*</span></label>
                                    <input type="tel" id="co-phone" wire:model.lazy="phone" value="{{ $phone }}" autocomplete="tel"
                                           class="cbz-input @error('phone') is-invalid @enderror" placeholder="+1 (000) 000-0000">
                                    @error('phone') <span class="cbz-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="cbz-field">
                                    <label for="co-notes" class="cbz-label">Order Notes <span class="cbz-label__note">(optional)</span></label>
                                    <textarea id="co-notes" rows="5" wire:model.defer="notes"
                                              class="cbz-input cbz-textarea @error('notes') is-invalid @enderror"
                                              placeholder="Finishes, materials, deadlines, delivery address — anything that helps us quote accurately.">{{ $notes }}</textarea>
                                    @error('notes') <span class="cbz-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="cbz-actions cbz-actions--split">
                                    <a href="{{ route('cart.quote') }}" class="cbz-btn cbz-btn--ghost">
                                        <i class="fi-rs-arrow-small-left" aria-hidden="true"></i> Back to Cart
                                    </a>

                                    <button type="submit" class="cbz-btn cbz-btn--primary"
                                            wire:loading.attr="disabled" wire:target="placeOrder">
                                        <span wire:loading.remove wire:target="placeOrder">
                                            <i class="fi-rs-shield-check" aria-hidden="true"></i> Submit Order
                                        </span>
                                        <span wire:loading wire:target="placeOrder">
                                            <span class="cbz-spinner" aria-hidden="true"></span> Submitting…
                                        </span>
                                    </button>
                                </div>

                                <p class="cbz-privacy">
                                    <i class="fi-rs-lock" aria-hidden="true"></i>
                                    Your details are used only to prepare your quote. We never share them.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>

                <aside class="cbz-checkout__summary" aria-label="Order summary">
                    <div class="cbz-summary">
                        <h2 class="cbz-summary__title">Order Summary</h2>

                        <ul class="cbz-summary__items">
                            @foreach ($items as $rowId => $item)
                                <li class="cbz-summary__item" wire:key="summary-{{ $rowId }}">
                                    <span class="cbz-summary__thumb">
                                        @if (!empty($item['image']))
                                            <img src="{{ $item['image'] }}" alt="{{ $item['product_name'] }}"
                                                 width="56" height="56" loading="lazy" decoding="async">
                                        @else
                                            <i class="fi-rs-box-alt" aria-hidden="true"></i>
                                        @endif
                                    </span>
                                    <span class="cbz-summary__detail">
                                        <strong>{{ $item['product_title'] ?: $item['product_name'] }}</strong>
                                        @if (!empty($item['category']))
                                            <small>{{ $item['category'] }}</small>
                                        @endif
                                        @php
                                            $dimensions = array_filter([$item['length'] ?? null, $item['width'] ?? null, $item['height'] ?? null]);
                                        @endphp
                                        <small>
                                            @if (count($dimensions))
                                                {{ implode(' × ', $dimensions) }} {{ $item['units'] ?? '' }} ·
                                            @endif
                                            Qty {{ number_format($item['quantity']) }}
                                        </small>
                                    </span>
                                </li>
                            @endforeach
                        </ul>

                        <dl class="cbz-summary__list">
                            <div>
                                <dt>Products</dt>
                                <dd>{{ count($items) }}</dd>
                            </div>
                            <div>
                                <dt>Total units</dt>
                                <dd>{{ number_format($totalQuantity) }}</dd>
                            </div>
                            <div>
                                <dt>Pricing</dt>
                                <dd class="cbz-summary__quote">Custom quote</dd>
                            </div>
                        </dl>

                        <a href="{{ route('cart.quote') }}" class="cbz-link-btn cbz-link-btn--center">
                            <i class="fi-rs-edit" aria-hidden="true"></i> Edit cart
                        </a>
                    </div>
                </aside>
            </div>

        @endif

    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('https://ipinfo.io/json?token=a31372f75ab60a')
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (window.Livewire) {
                        window.Livewire.emit('locationDetected', {
                            ip: data.ip,
                            city: data.city,
                            region: data.region,
                            country: data.country,
                            postal: data.postal
                        });
                    }
                })
                .catch(function () { /* location is optional */ });
        });
    </script>
@endpush
