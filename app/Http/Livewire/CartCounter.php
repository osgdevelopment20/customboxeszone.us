<?php

namespace App\Http\Livewire;

use App\Services\CartService;
use Livewire\Component;

/**
 * Header cart link with a live item count.
 */
class CartCounter extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render(CartService $cart)
    {
        return view('livewire.cart-counter', [
            'count' => $cart->count(),
            'totalQuantity' => $cart->totalQuantity(),
        ]);
    }
}
