<div class="cbz-cartlink-wrap">
    <a href="{{ route('cart.quote') }}" class="cbz-cartlink"
       title="{{ $count ? $count . ' item(s), ' . number_format($totalQuantity) . ' units' : 'Your cart is empty' }}"
       aria-label="Cart, {{ $count }} {{ Str::plural('item', $count) }}">
        <i class="fi-rs-shopping-cart" aria-hidden="true"></i>
        <span class="cbz-cartlink__text">Cart</span>
        @if ($count > 0)
            <span class="cbz-cartlink__badge">{{ $count > 99 ? '99+' : $count }}</span>
        @endif
    </a>
</div>
