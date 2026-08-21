<div class="cbz-page">
    <div class="container">

        <div class="cbz-page__head">
            <div>
                <h1 class="cbz-page__title">Your Cart</h1>
                <p class="cbz-page__subtitle">
                    @if (count($items))
                        {{ count($items) }} {{ Str::plural('box', count($items)) }} configured &middot;
                        {{ number_format($totalQuantity) }} units in total
                    @else
                        Review your configured boxes before requesting a final quote.
                    @endif
                </p>
            </div>

            @if (count($items))
                <ol class="cbz-steps" aria-label="Checkout progress">
                    <li class="cbz-steps__item is-done"><span>1</span> Configure</li>
                    <li class="cbz-steps__item is-current"><span>2</span> Cart</li>
                    <li class="cbz-steps__item"><span>3</span> Checkout</li>
                </ol>
            @endif
        </div>

        @if (session()->has('cart_success'))
            <div class="cbz-alert cbz-alert--success" role="status">
                <i class="fi-rs-check" aria-hidden="true"></i>
                <span>{{ session('cart_success') }}</span>
            </div>
        @endif

        @if ($notice)
            <div class="cbz-alert cbz-alert--info" role="status" wire:key="cart-notice">
                <i class="fi-rs-info" aria-hidden="true"></i>
                <span>{{ $notice }}</span>
            </div>
        @endif

        @if (empty($items))

            <div class="cbz-empty">
                <div class="cbz-empty__icon" aria-hidden="true"><i class="fi-rs-shopping-cart"></i></div>
                <h2 class="cbz-empty__title">Your cart is empty</h2>
                <p class="cbz-empty__text">
                    Browse our packaging range, set your size and quantity, then add it here to request a
                    combined quote for everything at once.
                </p>
                <a href="{{ route('allproducts') }}" class="cbz-btn cbz-btn--primary">
                    <i class="fi-rs-box-alt" aria-hidden="true"></i> Browse All Products
                </a>
            </div>

        @else

            <div class="cbz-cart">
                <div class="cbz-cart__items">
                    <div class="cbz-cart__header" aria-hidden="true">
                        <span>Product</span>
                        <span>Specification</span>
                        <span>Quantity</span>
                        <span></span>
                    </div>

                    @foreach ($items as $rowId => $item)
                        <div class="cbz-cart__row" wire:key="cart-row-{{ $rowId }}">
                            <div class="cbz-cart__product">
                                <a href="{{ route('page', $item['product_slug']) }}" class="cbz-cart__thumb">
                                    @if (!empty($item['image']))
                                        <img src="{{ $item['image'] }}" alt="{{ $item['product_name'] }}"
                                             width="90" height="90" loading="lazy" decoding="async">
                                    @else
                                        <span class="cbz-cart__thumb--empty"><i class="fi-rs-box-alt" aria-hidden="true"></i></span>
                                    @endif
                                </a>
                                <div class="cbz-cart__info">
                                    @if (!empty($item['category']))
                                        <span class="cbz-chip">{{ $item['category'] }}</span>
                                    @endif
                                    <a href="{{ route('page', $item['product_slug']) }}" class="cbz-cart__name">
                                        {{ $item['product_title'] ?: $item['product_name'] }}
                                    </a>
                                    @if (!empty($item['artwork']))
                                        <span class="cbz-cart__meta">
                                            <i class="fi-rs-clip" aria-hidden="true"></i>
                                            {{ count($item['artwork']) }} artwork {{ Str::plural('file', count($item['artwork'])) }} attached
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="cbz-cart__spec">
                                <span class="cbz-cart__label">Specification</span>
                                @php
                                    $dimensions = array_filter([$item['length'] ?? null, $item['width'] ?? null, $item['height'] ?? null]);
                                @endphp
                                @if (count($dimensions))
                                    <span class="cbz-cart__dims">
                                        {{ implode(' × ', $dimensions) }} {{ $item['units'] ?? '' }}
                                    </span>
                                    <span class="cbz-cart__meta">Length × Width × Height</span>
                                @else
                                    <span class="cbz-cart__dims cbz-cart__dims--muted">Standard size</span>
                                    <span class="cbz-cart__meta">Our team will confirm sizing</span>
                                @endif
                            </div>

                            <div class="cbz-cart__qty">
                                <span class="cbz-cart__label">Quantity</span>
                                <div class="cbz-stepper cbz-stepper--sm">
                                    <button type="button" class="cbz-stepper__btn" aria-label="Decrease quantity"
                                            onclick="cbzStep('qty-{{ $rowId }}', -50)">&minus;</button>
                                    <input type="number" min="1" id="qty-{{ $rowId }}"
                                           class="cbz-input cbz-stepper__input"
                                           wire:model.defer="quantities.{{ $rowId }}"
                                           value="{{ $quantities[$rowId] ?? $item['quantity'] }}"
                                           aria-label="Quantity for {{ $item['product_name'] }}">
                                    <button type="button" class="cbz-stepper__btn" aria-label="Increase quantity"
                                            onclick="cbzStep('qty-{{ $rowId }}', 50)">+</button>
                                </div>
                                <button type="button" class="cbz-link-btn"
                                        wire:click="updateRow('{{ $rowId }}')"
                                        wire:loading.attr="disabled" wire:target="updateRow('{{ $rowId }}')">
                                    <span wire:loading.remove wire:target="updateRow('{{ $rowId }}')">
                                        <i class="fi-rs-refresh" aria-hidden="true"></i> Update
                                    </span>
                                    <span wire:loading wire:target="updateRow('{{ $rowId }}')">Updating…</span>
                                </button>
                            </div>

                            <div class="cbz-cart__remove">
                                <button type="button" class="cbz-icon-btn"
                                        wire:click="remove('{{ $rowId }}')"
                                        wire:loading.attr="disabled" wire:target="remove('{{ $rowId }}')"
                                        aria-label="Remove {{ $item['product_name'] }} from cart">
                                    <i class="fi-rs-trash" aria-hidden="true"></i>
                                    <span class="cbz-icon-btn__text">Remove</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <aside class="cbz-cart__summary" aria-label="Cart summary">
                    <div class="cbz-summary">
                        <h2 class="cbz-summary__title">Order Summary</h2>

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

                        <p class="cbz-summary__note">
                            Every box is priced to your exact specification. Submit your order and a specialist
                            will send a full quote within 24 hours — no payment is taken now.
                        </p>

                        <button type="button" wire:click="checkout" class="cbz-btn cbz-btn--primary cbz-btn--block">
                            <i class="fi-rs-shield-check" aria-hidden="true"></i> Proceed to Checkout
                        </button>

                        <a href="{{ route('allproducts') }}" class="cbz-btn cbz-btn--outline cbz-btn--block">
                            <i class="fi-rs-arrow-small-left" aria-hidden="true"></i> Continue Shopping
                        </a>

                        @if ($confirmingClear)
                            <div class="cbz-confirm" role="alertdialog" aria-label="Confirm clearing the cart">
                                <p>Remove all {{ count($items) }} {{ Str::plural('item', count($items)) }} from your cart?</p>
                                <div class="cbz-confirm__actions">
                                    <button type="button" class="cbz-btn cbz-btn--danger cbz-btn--sm" wire:click="clear">
                                        Yes, clear cart
                                    </button>
                                    <button type="button" class="cbz-btn cbz-btn--ghost cbz-btn--sm" wire:click="cancelClear">
                                        Keep items
                                    </button>
                                </div>
                            </div>
                        @else
                            <button type="button" wire:click="confirmClear" class="cbz-btn cbz-btn--ghost cbz-btn--block">
                                <i class="fi-rs-trash" aria-hidden="true"></i> Clear Cart
                            </button>
                        @endif

                        <ul class="cbz-trust cbz-trust--stack">
                            <li><i class="fi-rs-check" aria-hidden="true"></i> Free design support</li>
                            <li><i class="fi-rs-check" aria-hidden="true"></i> No die &amp; plate charges</li>
                            <li><i class="fi-rs-check" aria-hidden="true"></i> Response within 24 hours</li>
                        </ul>
                    </div>
                </aside>
            </div>

        @endif

    </div>
</div>

@push('scripts')
    <script>
        if (typeof window.cbzStep !== 'function') {
            window.cbzStep = function (inputId, step) {
                var input = document.getElementById(inputId);
                if (!input) return;
                var next = (parseInt(input.value, 10) || 0) + step;
                input.value = next < 1 ? 1 : next;
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
            };
        }
    </script>
@endpush
