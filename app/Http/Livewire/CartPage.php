<?php

namespace App\Http\Livewire;

use App\Services\CartService;
use Livewire\Component;

/**
 * Cart screen - review, adjust and remove configured boxes before checkout.
 */
class CartPage extends Component
{
    /** @var array<string, int> Quantity inputs keyed by cart row id. */
    public $quantities = [];

    public $confirmingClear = false;

    public $notice;

    public function mount(CartService $cart)
    {
        $this->syncQuantities($cart);
    }

    public function updateRow($rowId, CartService $cart)
    {
        $quantity = (int) ($this->quantities[$rowId] ?? 0);

        if ($quantity < 1) {
            $this->notice = 'Quantity must be at least 1. Use Remove to delete the item.';
            $this->syncQuantities($cart);

            return;
        }

        $item = $cart->get($rowId);
        $cart->updateQuantity($rowId, $quantity);
        $this->syncQuantities($cart);

        $this->notice = ($item['product_title'] ?? 'Item') . ' updated to ' . number_format($quantity) . ' units.';
        $this->emit('cartUpdated');
    }

    public function remove($rowId, CartService $cart)
    {
        $item = $cart->get($rowId);
        $cart->remove($rowId);
        $this->syncQuantities($cart);

        $this->notice = ($item['product_title'] ?? 'Item') . ' removed from your cart.';
        $this->emit('cartUpdated');
    }

    public function confirmClear()
    {
        $this->confirmingClear = true;
    }

    public function cancelClear()
    {
        $this->confirmingClear = false;
    }

    public function clear(CartService $cart)
    {
        $cart->clear();
        $this->confirmingClear = false;
        $this->syncQuantities($cart);

        $this->notice = 'Your cart has been cleared.';
        $this->emit('cartUpdated');
    }

    public function checkout(CartService $cart)
    {
        if ($cart->isEmpty()) {
            $this->notice = 'Your cart is empty. Add a box before checking out.';

            return null;
        }

        return redirect()->route('checkout.page');
    }

    protected function syncQuantities(CartService $cart): void
    {
        $this->quantities = collect($cart->items())
            ->map(fn ($item) => (int) $item['quantity'])
            ->all();
    }

    public function render(CartService $cart)
    {
        return view('livewire.cart-page', [
            'items' => $cart->items(),
            'totalQuantity' => $cart->totalQuantity(),
        ]);
    }
}
